<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpParser\Error as ParseError;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use PhpParser\ParserFactory;

/**
 * Converts FileUpload::make('x')->image() chains in Filament Resources to
 * MediaPicker::forField('x', 'folder'), wires the HasMediaAssets trait onto
 * models, adds syncFieldFromAsset() hooks to Create/Edit pages, and generates
 * one consolidated migration for the {field}_asset_id columns.
 *
 * AST-based (nikic/php-parser). Dry-run by default. Idempotent. Never touches
 * Blade files, MediaAssetResource, Spatie Settings pages, or non-image fields.
 */
class MigrateMediaFieldsCommand extends Command
{
    protected $signature = 'media:migrate-fields
        {--dry-run : Report only (default behavior even without this flag)}
        {--force : Actually apply changes (asks for confirmation)}
        {--resource= : Limit to one resource, e.g. PartnerLogo or PartnerLogoResource}';

    protected $description = 'Migrate image FileUpload fields to MediaPicker (dry-run by default)';

    private const FILE_UPLOAD_CLASS = 'Filament\Forms\Components\FileUpload';

    private const MEDIA_PICKER_CLASS = 'App\Filament\Forms\Components\MediaPicker';

    private const HARD_EXCLUDED_FILES = ['MediaAssetResource.php'];

    /** Chained methods we understand. Anything else => NEEDS REVIEW. */
    private const KNOWN_METHODS = [
        'image', 'imageEditor', 'maxSize', 'acceptedFileTypes', 'nullable',
        'required', 'label', 'helperText', 'imagePreviewHeight',
        'fetchFileInformation', 'getUploadedFileUsing', 'saveUploadedFileUsing',
        'columnSpan', 'columnSpanFull',
    ];

    /** Methods carried over onto the MediaPicker. */
    private const PRESERVED_METHODS = ['label', 'required', 'helperText', 'columnSpan', 'columnSpanFull'];

    private \PhpParser\Parser $parser;

    private array $logLines = [];

    public function handle(): int
    {
        $this->parser = (new ParserFactory)->createForNewestSupportedVersion();

        $apply = (bool) $this->option('force') && ! $this->option('dry-run');

        $files = $this->resourceFiles();

        if ($files === []) {
            $this->error('No matching resource files found.');

            return self::FAILURE;
        }

        $safe = [];
        $needsReview = [];
        $skipped = [];

        foreach ($files as $file) {
            $result = $this->analyzeResource($file);

            match ($result['status']) {
                'safe' => $safe[] = $result,
                'review' => $needsReview[] = $result,
                default => $skipped[] = $result,
            };
        }

        $this->renderReport($files, $safe, $needsReview, $skipped);

        if ($safe === []) {
            $this->info('Nothing to migrate.');
            $this->writeLog($apply);

            return self::SUCCESS;
        }

        if (! $apply) {
            $this->newLine();
            $this->comment('Dry-run only. Re-run with --force to apply the changes above.');

            return self::SUCCESS;
        }

        $this->newLine();

        if (! $this->confirm('Apply the changes listed above?')) {
            $this->comment('Aborted. No files were modified.');

            return self::SUCCESS;
        }

        foreach ($safe as $result) {
            $this->applyResource($result);
        }

        $this->generateMigration($safe);
        $this->writeLog(true);

        $this->newLine();
        $this->info('Done. Run "php artisan migrate" to add the new columns.');

        return self::SUCCESS;
    }

    // ---------------------------------------------------------------
    // Discovery
    // ---------------------------------------------------------------

    /** @return string[] */
    private function resourceFiles(): array
    {
        $dir = app_path('Filament/Resources');
        $only = $this->option('resource');

        if ($only !== null) {
            $only = Str::finish($only, 'Resource');
        }

        $files = [];

        foreach (File::files($dir) as $file) {
            $name = $file->getFilename();

            if (! str_ends_with($name, 'Resource.php')) {
                continue;
            }

            if (in_array($name, self::HARD_EXCLUDED_FILES, true)) {
                continue;
            }

            if ($only !== null && $name !== $only.'.php') {
                continue;
            }

            $files[] = $file->getPathname();
        }

        return $files;
    }

    // ---------------------------------------------------------------
    // Analysis
    // ---------------------------------------------------------------

    private function analyzeResource(string $path): array
    {
        $code = File::get($path);
        $base = basename($path);

        try {
            $ast = $this->parseWithMeta($code);
        } catch (ParseError $e) {
            return $this->skip($path, "parse error: {$e->getMessage()} — file left untouched");
        }

        $finder = new NodeFinder;

        $chains = $this->findFileUploadChains($ast, $finder);

        if ($chains === []) {
            $reason = str_contains($code, 'MediaPicker')
                ? 'already uses MediaPicker'
                : 'no FileUpload fields';

            return $this->skip($path, $reason);
        }

        $fieldNames = [];
        $fields = [];

        foreach ($chains as $chain) {
            $field = $this->analyzeChain($chain, $code);

            if ($field['status'] === 'skip') {
                continue; // non-image FileUpload: leave untouched, not a blocker
            }

            if (in_array($field['name'], $fieldNames, true)) {
                return $this->review($path, "duplicate field name '{$field['name']}' in same resource");
            }

            $fieldNames[] = $field['name'];

            if ($field['status'] === 'review') {
                return $this->review($path, "field '{$field['name']}': {$field['reason']}");
            }

            $fields[] = $field;
        }

        if ($fields === []) {
            return $this->skip($path, 'FileUpload fields present but none use ->image()');
        }

        $modelClass = $this->detectModelClass($ast, $finder);

        if ($modelClass === null) {
            return $this->review($path, 'could not detect the model class');
        }

        $modelPath = app_path('Models/'.class_basename($modelClass).'.php');

        if (! File::exists($modelPath)) {
            return $this->review($path, "model file not found: {$modelPath}");
        }

        return [
            'status' => 'safe',
            'path' => $path,
            'file' => $base,
            'code' => $code,
            'fields' => $fields,
            'model_class' => $modelClass,
            'model_path' => $modelPath,
            'resource_class' => Str::before($base, '.php'),
        ];
    }

    /** @return array<int, array{root: Node\Expr\StaticCall, outer: Node\Expr}> chains rooted at FileUpload::make() */
    private function findFileUploadChains(array $ast, NodeFinder $finder): array
    {
        $makes = $finder->find($ast, function (Node $node): bool {
            if (! $node instanceof Node\Expr\StaticCall) {
                return false;
            }

            if (! $node->name instanceof Node\Identifier || $node->name->toString() !== 'make') {
                return false;
            }

            $class = $node->class;

            return $class instanceof Node\Name
                && ($class->getAttribute('resolvedName')?->toString() ?? $class->toString()) === self::FILE_UPLOAD_CLASS;
        });

        $chains = [];

        foreach ($makes as $make) {
            $node = $make;

            while (($parent = $node->getAttribute('parent')) instanceof Node\Expr\MethodCall && $parent->var === $node) {
                $node = $parent;
            }

            $chains[] = ['root' => $make, 'outer' => $node];
        }

        return $chains;
    }

    private function analyzeChain(array $chain, string $code): array
    {
        /** @var Node\Expr\StaticCall $make */
        $make = $chain['root'];
        $outer = $chain['outer'];

        $nameArg = $make->args[0]->value ?? null;

        if (! $nameArg instanceof Node\Scalar\String_) {
            return ['status' => 'review', 'name' => '(dynamic)', 'reason' => 'field name is not a plain string'];
        }

        $name = $nameArg->value;

        if (str_contains($name, '.')) {
            return ['status' => 'review', 'name' => $name, 'reason' => 'dotted (relationship) state path — migrate manually'];
        }

        // Walk the chain from make() outwards collecting method calls.
        $calls = [];
        $node = $make->getAttribute('parent');

        while ($node instanceof Node\Expr\MethodCall) {
            if (! $node->name instanceof Node\Identifier) {
                return ['status' => 'review', 'name' => $name, 'reason' => 'dynamic method call in chain'];
            }

            $calls[] = $node;
            $next = $node->getAttribute('parent');

            if (! $next instanceof Node\Expr\MethodCall || $next->var !== $node) {
                break;
            }

            $node = $next;
        }

        $methodNames = array_map(fn (Node\Expr\MethodCall $c) => $c->name->toString(), $calls);

        if (! in_array('image', $methodNames, true)) {
            return ['status' => 'skip', 'name' => $name, 'reason' => 'no ->image() modifier'];
        }

        $unknown = array_diff($methodNames, self::KNOWN_METHODS);

        if ($unknown !== []) {
            return ['status' => 'review', 'name' => $name, 'reason' => 'unsupported config: ->'.implode('(), ->', $unknown).'()'];
        }

        $folder = $this->detectFolder($calls, $code);

        if ($folder === null) {
            return ['status' => 'review', 'name' => $name, 'reason' => 'could not detect Cloudinary folder from saveUploadedFileUsing'];
        }

        $preserved = [];
        $dropped = [];

        foreach ($calls as $call) {
            $method = $call->name->toString();

            if (in_array($method, self::PRESERVED_METHODS, true)) {
                $args = array_map(
                    fn ($arg) => substr($code, $arg->getStartFilePos(), $arg->getEndFilePos() - $arg->getStartFilePos() + 1),
                    $call->args
                );
                $preserved[] = '->'.$method.'('.implode(', ', $args).')';
            } else {
                $dropped[] = $method;
            }
        }

        return [
            'status' => 'safe',
            'name' => $name,
            'folder' => $folder,
            'preserved' => $preserved,
            'dropped' => $dropped,
            'start' => $outer->getStartFilePos(),
            'end' => $outer->getEndFilePos(),
        ];
    }

    /** @param Node\Expr\MethodCall[] $calls */
    private function detectFolder(array $calls, string $code): ?string
    {
        $finder = new NodeFinder;

        foreach ($calls as $call) {
            if ($call->name->toString() !== 'saveUploadedFileUsing') {
                continue;
            }

            $upload = $finder->findFirst($call->args, function (Node $node): bool {
                return $node instanceof Node\Expr\MethodCall
                    && $node->name instanceof Node\Identifier
                    && $node->name->toString() === 'uploadImage';
            });

            $folderArg = $upload?->args[1]->value ?? null;

            if ($folderArg instanceof Node\Scalar\String_) {
                return $folderArg->value;
            }
        }

        return null;
    }

    private function detectModelClass(array $ast, NodeFinder $finder): ?string
    {
        $prop = $finder->findFirst($ast, function (Node $node): bool {
            return $node instanceof Node\Stmt\Property
                && $node->props[0]->name->toString() === 'model';
        });

        $default = $prop?->props[0]->default;

        if ($default instanceof Node\Expr\ClassConstFetch && $default->class instanceof Node\Name) {
            return $default->class->getAttribute('resolvedName')?->toString() ?? $default->class->toString();
        }

        return null;
    }

    // ---------------------------------------------------------------
    // Reporting
    // ---------------------------------------------------------------

    private function renderReport(array $files, array $safe, array $review, array $skipped): void
    {
        $this->line('==============================================');
        $this->line(' media:migrate-fields report');
        $this->line('==============================================');
        $this->log('Scanned '.count($files).' resource file(s) at '.now()->toDateTimeString());

        $this->newLine();
        $this->info('SAFE TO MIGRATE ('.count($safe).'):');

        foreach ($safe as $result) {
            foreach ($result['fields'] as $field) {
                $this->log("  [SAFE] {$result['file']} — field '{$field['name']}' → MediaPicker::forField('{$field['name']}', '{$field['folder']}')");

                if ($field['dropped'] !== []) {
                    $this->log('         dropped config: ->'.implode('(), ->', $field['dropped']).'()');
                }

                $this->renderDiff($result['code'], $field);
            }

            $this->log("         model: {$result['model_class']} (+ HasMediaAssets, + fillable)");
        }

        $this->newLine();
        $this->warn('NEEDS REVIEW — excluded, migrate manually ('.count($review).'):');

        foreach ($review as $result) {
            $this->log("  [REVIEW] {$result['file']}: {$result['reason']}");
        }

        $this->log('  [REVIEW] SeoFormFields.php: seo.og_image_url / seo.twitter_image_url — dotted relationship paths (always manual)');

        $this->newLine();
        $this->comment('SKIPPED ('.count($skipped).'):');

        foreach ($skipped as $result) {
            $this->log("  [SKIP] {$result['file']}: {$result['reason']}");
        }

        $this->log('  [SKIP] MediaAssetResource.php: media library ingest field (hard-excluded)');
        $this->log('  [SKIP] Spatie Settings pages (app/Filament/Pages): never automated — see docs/MEDIA_LIBRARY.md');
    }

    private function renderDiff(string $code, array $field): void
    {
        $before = substr($code, $field['start'], $field['end'] - $field['start'] + 1);
        $after = $this->buildReplacement($code, $field);

        $this->line('         --- before ---');

        foreach (explode("\n", $before) as $line) {
            $this->log('         - '.trim($line));
        }

        $this->line('         --- after ---');

        foreach (explode("\n", $after) as $line) {
            $this->log('         + '.trim($line));
        }
    }

    // ---------------------------------------------------------------
    // Code modification
    // ---------------------------------------------------------------

    private function buildReplacement(string $code, array $field): string
    {
        $indent = $this->indentAt($code, $field['start']);

        $out = "MediaPicker::forField('{$field['name']}', '{$field['folder']}')";

        foreach ($field['preserved'] as $call) {
            $out .= "\n{$indent}    {$call}";
        }

        return $out;
    }

    private function indentAt(string $code, int $pos): string
    {
        $lineStart = strrpos(substr($code, 0, $pos), "\n");
        $lineStart = $lineStart === false ? 0 : $lineStart + 1;
        $line = substr($code, $lineStart, $pos - $lineStart);

        return str_repeat(' ', strlen($line) - strlen(ltrim($line)));
    }

    private function applyResource(array $result): void
    {
        $path = $result['path'];
        $code = $result['code'];

        // Splice chains from last to first so earlier offsets stay valid.
        $fields = $result['fields'];
        usort($fields, fn ($a, $b) => $b['start'] <=> $a['start']);

        foreach ($fields as $field) {
            $replacement = $this->buildReplacement($code, $field);
            $code = substr($code, 0, $field['start']).$replacement.substr($code, $field['end'] + 1);
        }

        $code = $this->ensureImport($code, self::MEDIA_PICKER_CLASS);
        $code = $this->removeUnusedImports($code);

        $this->writePhp($path, $code);
        $this->log("MODIFIED {$path}");

        $this->applyModel($result);
        $this->applyPages($result);
    }

    private function applyModel(array $result): void
    {
        $path = $result['model_path'];
        $code = File::get($path);
        $changed = false;

        $code = $this->ensureImport($code, 'App\Concerns\HasMediaAssets', $changed);

        if (! preg_match('/use\s+HasMediaAssets\s*[;,]/', $code)) {
            // Insert the trait use as the first statement in the class body.
            if (! preg_match('/(class\s+\w+\s+extends\s+[\w\\\\]+\s*\{)/', $code, $m, PREG_OFFSET_CAPTURE)) {
                $this->failLoudly("could not locate class body in {$path}");
            }

            $insertAt = $m[1][1] + strlen($m[1][0]);
            $code = substr($code, 0, $insertAt)."\n    use HasMediaAssets;\n".substr($code, $insertAt);
            $changed = true;
        }

        foreach ($result['fields'] as $field) {
            $column = "{$field['name']}_asset_id";

            if (str_contains($code, "'{$column}'") || str_contains($code, "\"{$column}\"")) {
                continue;
            }

            if (! preg_match('/protected\s+\$fillable\s*=\s*\[/', $code, $m, PREG_OFFSET_CAPTURE)) {
                $this->warn("  {$path}: no \$fillable array found — add '{$column}' manually.");
                $this->logLines[] = "WARNING: {$path} has no \$fillable — add '{$column}' manually";

                continue;
            }

            $arrayStart = $m[0][1] + strlen($m[0][0]);
            $arrayEnd = strpos($code, ']', $arrayStart);
            $inner = substr($code, $arrayStart, $arrayEnd - $arrayStart);
            $entry = rtrim($inner);

            if ($entry !== '' && ! str_ends_with($entry, ',')) {
                $entry .= ',';
            }

            $entry .= "\n        '{$column}',\n    ";
            $code = substr($code, 0, $arrayStart).$entry.substr($code, $arrayEnd);
            $changed = true;
        }

        if ($changed) {
            $this->writePhp($path, $code);
            $this->log("MODIFIED {$path}");
        }
    }

    private function applyPages(array $result): void
    {
        $pagesDir = app_path('Filament/Resources/'.$result['resource_class'].'/Pages');

        if (! File::isDirectory($pagesDir)) {
            $this->warn("  no Pages directory for {$result['resource_class']} — add sync hooks manually.");

            return;
        }

        foreach (File::files($pagesDir) as $file) {
            $code = File::get($file->getPathname());

            $method = match (true) {
                str_contains($code, 'extends CreateRecord') => 'mutateFormDataBeforeCreate',
                str_contains($code, 'extends EditRecord') => 'mutateFormDataBeforeSave',
                default => null,
            };

            if ($method === null) {
                continue;
            }

            if (str_contains($code, 'syncFieldFromAsset')) {
                continue; // already migrated
            }

            $syncLines = '';

            foreach ($result['fields'] as $field) {
                $syncLines .= "        \$data = \\App\\Filament\\Forms\\Components\\MediaPicker::syncFieldFromAsset(\$data, '{$field['name']}');\n";
            }

            if (preg_match('/(protected\s+function\s+'.$method.'\s*\(array\s+\$data\)\s*:\s*array\s*\{\n)/', $code, $m, PREG_OFFSET_CAPTURE)) {
                $insertAt = $m[1][1] + strlen($m[1][0]);
                $code = substr($code, 0, $insertAt).$syncLines."\n".substr($code, $insertAt);
            } else {
                $classEnd = strrpos($code, '}');

                if ($classEnd === false) {
                    $this->failLoudly("could not locate class end in {$file->getPathname()}");
                }

                $methodCode = "\n    protected function {$method}(array \$data): array\n    {\n{$syncLines}\n        return \$data;\n    }\n";
                $code = substr($code, 0, $classEnd).$methodCode.substr($code, $classEnd);
            }

            $this->writePhp($file->getPathname(), $code);
            $this->log("MODIFIED {$file->getPathname()}");
        }
    }

    private function ensureImport(string $code, string $class, ?bool &$changed = null): string
    {
        if (preg_match('/^use\s+'.preg_quote($class, '/').'\s*;/m', $code)) {
            return $code;
        }

        if (! preg_match_all('/^use\s+[^;]+;$/m', $code, $m, PREG_OFFSET_CAPTURE)) {
            $this->failLoudly('could not find use statements to anchor the import');
        }

        $last = end($m[0]);
        $insertAt = $last[1] + strlen($last[0]);
        $changed = true;

        return substr($code, 0, $insertAt)."\nuse {$class};".substr($code, $insertAt);
    }

    private function removeUnusedImports(string $code): string
    {
        foreach ([
            'Filament\Forms\Components\FileUpload',
            'Livewire\Features\SupportFileUploads\TemporaryUploadedFile',
            'App\Services\CloudinaryService',
        ] as $class) {
            $short = class_basename($class);
            $pattern = '/^use\s+'.preg_quote($class, '/').'\s*;\n/m';

            if (! preg_match($pattern, $code)) {
                continue;
            }

            $without = preg_replace($pattern, '', $code, 1);

            // Only remove when the short name no longer appears anywhere else.
            if (! preg_match('/\b'.preg_quote($short, '/').'\b/', $without)) {
                $code = $without;
            }
        }

        return $code;
    }

    /** Validate the rewritten code parses before writing. Fails loudly otherwise. */
    private function writePhp(string $path, string $code): void
    {
        try {
            $this->parser->parse($code);
        } catch (ParseError $e) {
            $this->failLoudly("rewritten {$path} would not parse ({$e->getMessage()}) — NOT written, aborting");
        }

        File::put($path, $code);
    }

    private function failLoudly(string $message): never
    {
        $this->error('FATAL: '.$message);
        $this->logLines[] = 'FATAL: '.$message;
        $this->writeLog(true);

        exit(self::FAILURE);
    }

    // ---------------------------------------------------------------
    // Migration generation
    // ---------------------------------------------------------------

    private function generateMigration(array $safe): void
    {
        $tables = [];

        foreach ($safe as $result) {
            $table = $this->tableForModel($result['model_path'], $result['model_class']);

            foreach ($result['fields'] as $field) {
                $tables[$table][] = "{$field['name']}_asset_id";
            }
        }

        $body = '';

        foreach ($tables as $table => $columns) {
            foreach (array_unique($columns) as $column) {
                $body .= <<<PHP

        if (! Schema::hasColumn('{$table}', '{$column}')) {
            Schema::table('{$table}', function (Blueprint \$table) {
                \$table->foreignId('{$column}')->nullable()
                    ->constrained('media_assets')->nullOnDelete();
            });
        }

PHP;
            }
        }

        $downBody = '';

        foreach ($tables as $table => $columns) {
            foreach (array_unique($columns) as $column) {
                $downBody .= <<<PHP

        if (Schema::hasColumn('{$table}', '{$column}')) {
            Schema::table('{$table}', function (Blueprint \$table) {
                \$table->dropConstrainedForeignId('{$column}');
            });
        }

PHP;
            }
        }

        $stamp = now()->format('Y_m_d_His');
        $path = database_path("migrations/{$stamp}_add_media_asset_id_columns.php");

        $contents = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {{$body}    }

    public function down(): void
    {{$downBody}    }
};

PHP;

        File::put($path, $contents);
        $this->log("CREATED {$path}");
    }

    private function tableForModel(string $modelPath, string $modelClass): string
    {
        $code = File::get($modelPath);

        if (preg_match('/protected\s+\$table\s*=\s*[\'"]([^\'"]+)[\'"]/', $code, $m)) {
            return $m[1];
        }

        return Str::snake(Str::pluralStudly(class_basename($modelClass)));
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function parseWithMeta(string $code): array
    {
        $ast = $this->parser->parse($code);

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new NameResolver(null, ['preserveOriginalNames' => true, 'replaceNodes' => false]));
        $traverser->addVisitor(new ParentConnectingVisitor);

        return $traverser->traverse($ast);
    }

    private function skip(string $path, string $reason): array
    {
        return ['status' => 'skip', 'path' => $path, 'file' => basename($path), 'reason' => $reason];
    }

    private function review(string $path, string $reason): array
    {
        return ['status' => 'review', 'path' => $path, 'file' => basename($path), 'reason' => $reason];
    }

    private function log(string $line): void
    {
        $this->line($line);
        $this->logLines[] = $line;
    }

    private function writeLog(bool $persist): void
    {
        if (! $persist || $this->logLines === []) {
            return;
        }

        $path = storage_path('logs/media-migration-'.now()->format('Y-m-d').'.log');
        File::append($path, implode("\n", $this->logLines)."\n\n");
        $this->comment("Log written to {$path}");
        $this->logLines = [];
    }
}
