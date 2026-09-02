<?php

use Illuminate\Support\Str;

if (! function_exists('mlp_image_url')) {
    /**
     * Optimized image URL for MBA/Master's landing (MLP).
     * Cloudinary URLs get f_auto,q_auto,w_{n}; others fall through to media_url().
     *
     * @param  array{w?: int, width?: int, fallback?: string|null}  $opts
     */
    function mlp_image_url(?string $path, array $opts = []): ?string
    {
        $fallback = $opts['fallback'] ?? null;
        $url = media_url($path, $fallback);

        if (empty($url)) {
            return null;
        }

        if (! str_contains($url, '/upload/')) {
            return $url;
        }

        if (preg_match('#/upload/(?:[^/]+,)?f_auto[,/]#', $url)) {
            return $url;
        }

        $width = (int) ($opts['w'] ?? $opts['width'] ?? 1600);
        $transform = "f_auto,q_auto,w_{$width}";

        return preg_replace('#(/upload/)#', "$1{$transform}/", $url, 1);
    }
}

if (! function_exists('media_url')) {
    /**
     * Normalize a media URL for output.
     *
     * Site settings (e.g. logos) may store either a full absolute URL
     * (Cloudinary) or a relative path like "assets/images/logo.png".
     * Relative paths must be resolved with asset() so they work on sub-routes
     * (e.g. /programs/{slug}) and never produce /programs/assets/... links.
     *
     * @param  string|null  $path
     * @param  string|null  $fallback  local path used when $path is empty
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        $value = $path ?: $fallback;

        if (empty($value)) {
            return null;
        }

        // Already an absolute URL (http/https/data/blob) or protocol-relative.
        if (Str::startsWith($value, ['http://', 'https://', '//', 'data:', 'blob:'])) {
            return $value;
        }

        return cached_asset(ltrim($value, '/'));
    }
}

if (! function_exists('settings_media_url')) {
    /**
     * Resolve a media URL from a settings row (URL column or *_asset_id).
     *
     * @param  array<string, mixed>|object  $item
     */
    function settings_media_url(array|object $item, string $field): ?string
    {
        if (is_object($item) && method_exists($item, 'toArray')) {
            $item = $item->toArray();
        } elseif (is_object($item)) {
            $item = (array) $item;
        }

        if (filled($item[$field] ?? null)) {
            return media_url($item[$field]);
        }

        $assetId = $item["{$field}_asset_id"] ?? null;

        if (blank($assetId)) {
            return null;
        }

        $asset = \App\Models\MediaAsset::query()->find($assetId);

        return media_url($asset?->url);
    }
}

if (! function_exists('cached_asset')) {
    /**
     * Version a public file by mtime so far-future Cache-Control can stay immutable.
     */
    function cached_asset(string $path): string
    {
        $relative = ltrim($path, '/');
        $url = asset($relative);
        $full = public_path($relative);

        if (is_file($full)) {
            return $url.'?v='.filemtime($full);
        }

        return $url;
    }
}

if (! function_exists('html_filled')) {
    function html_filled(?string $value): bool
    {
        $text = html_entity_decode(strip_tags($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\x{00A0}/u', ' ', $text ?? '');

        return filled(trim($text ?? ''));
    }
}

if (! function_exists('rich_html')) {
    /**
     * Render stored rich text. Empty HTML (including bare tags) yields nothing.
     * Legacy plain text is escaped and given line breaks so existing copy still shows.
     */
    function rich_html(?string $value): string
    {
        if (! html_filled($value)) {
            return '';
        }

        $value = $value ?? '';

        if (strip_tags($value) !== $value) {
            return $value;
        }

        return nl2br(e($value), false);
    }
}

if (! function_exists('normalize_rich_html_media')) {
    /**
     * Resolve relative image URLs inside stored rich HTML for front-end output.
     */
    function normalize_rich_html_media(?string $html): string
    {
        if (! html_filled($html)) {
            return '';
        }

        return preg_replace_callback(
            '/<img\b([^>]*?)\bsrc=(["\'])([^"\']+)\2/i',
            static function (array $matches): string {
                $src = $matches[3];

                if (Str::startsWith($src, ['http://', 'https://', '//', 'data:', 'blob:'])) {
                    return $matches[0];
                }

                $resolved = media_url($src);

                if (! $resolved) {
                    return $matches[0];
                }

                return '<img'.$matches[1].'src='.$matches[2].$resolved.$matches[2];
            },
            $html
        );
    }
}

if (! function_exists('edu_cta_class')) {
    function edu_cta_class(?string $style): string
    {
        return match ($style) {
            'secondary' => 'btn btn--secondary',
            'outline' => 'btn btn--outline',
            'ghost' => 'btn btn--ghost',
            default => 'btn btn--primary',
        };
    }
}

if (! function_exists('edu_href')) {
    function edu_href(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://', '//', 'mailto:', 'tel:', '#'])) {
            return $url;
        }

        return url($url);
    }
}

if (! function_exists('slug_href')) {
    function slug_href(?string $slug): ?string
    {
        $slug = trim((string) $slug, "/ \t\n\r\0\x0B");

        return $slug === '' ? null : url('/'.$slug);
    }
}

if (! function_exists('cloudinary_upload')) {
    function cloudinary_upload(?string $path, string $folder = 'general'): ?string
    {
        if (! filled($path) || ! is_readable($path)) {
            return null;
        }

        try {
            return app(\App\Services\CloudinaryService::class)->uploadImage($path, $folder);
        } catch (Throwable $e) {
            report($e);

            if (class_exists(\Filament\Notifications\Notification::class)) {
                \Filament\Notifications\Notification::make()
                    ->title('Image upload failed')
                    ->body('The existing image was kept. Please try again.')
                    ->danger()
                    ->send();
            }

            return null;
        }
    }
}

if (! function_exists('youtube_video_id')) {
    function youtube_video_id(?string $url): ?string
    {
        $url = trim($url ?? '');

        if ($url === '') {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        return null;
    }
}

if (! function_exists('youtube_embed_url')) {
    function youtube_embed_url(?string $url, bool $autoplay = false): ?string
    {
        $url = trim($url ?? '');

        if ($url === '') {
            return null;
        }

        if ($id = youtube_video_id($url)) {
            $query = $autoplay ? 'autoplay=1&rel=0' : 'rel=0';

            return 'https://www.youtube.com/embed/'.$id.'?'.$query;
        }

        if (preg_match('/vimeo\.com\/(?:.*\/)?(\d+)/i', $url, $matches)) {
            $query = $autoplay ? 'autoplay=1' : '';

            return 'https://player.vimeo.com/video/'.$matches[1].($query !== '' ? '?'.$query : '');
        }

        return $url;
    }
}

if (! function_exists('youtube_thumbnail_url')) {
    function youtube_thumbnail_url(?string $videoUrl, ?string $customThumb = null, bool $preferMaxRes = false): ?string
    {
        if (filled($customThumb)) {
            return media_url($customThumb) ?? $customThumb;
        }

        $id = youtube_video_id($videoUrl);

        if (! $id) {
            return null;
        }

        $quality = $preferMaxRes ? 'maxresdefault' : 'hqdefault';

        return "https://img.youtube.com/vi/{$id}/{$quality}.jpg";
    }
}

if (! function_exists('youtube_thumbnail_fallback')) {
    function youtube_thumbnail_fallback(?string $videoUrl): ?string
    {
        $id = youtube_video_id($videoUrl);

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }
}

if (! function_exists('settings_array')) {
    function settings_array(mixed $value): array
    {
        if ($value instanceof \Illuminate\Support\Collection) {
            $value = $value->all();
        }

        return array_values(is_array($value) ? $value : []);
    }
}

if (! function_exists('settings_fallback')) {
    function settings_fallback(string $class): object
    {
        $values = [];

        if (class_exists($class)) {
            try {
                $reflection = new ReflectionClass($class);

                foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                    if ($property->isStatic()) {
                        continue;
                    }

                    $name = $property->getName();
                    $type = $property->getType();

                    if ($property->hasDefaultValue()) {
                        $values[$name] = $property->getDefaultValue();

                        continue;
                    }

                    $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

                    $values[$name] = match ($typeName) {
                        'array' => [],
                        'string' => $type && $type->allowsNull() ? null : '',
                        'int' => $type && $type->allowsNull() ? null : 0,
                        'float' => $type && $type->allowsNull() ? null : 0.0,
                        'bool' => $type && $type->allowsNull() ? null : false,
                        default => null,
                    };
                }
            } catch (Throwable $e) {
                report($e);
            }
        }

        return new class($values)
        {
            public function __construct(private array $values)
            {
            }

            public function toArray(): array
            {
                return $this->values;
            }

            public function __get(string $name): mixed
            {
                return $this->values[$name] ?? null;
            }

            public function __set(string $name, mixed $value): void
            {
                $this->values[$name] = $value;
            }

            public function __isset(string $name): bool
            {
                return array_key_exists($name, $this->values);
            }
        };
    }
}

if (! function_exists('safe_settings')) {
    /**
     * Resolve a Spatie settings class once per HTTP request.
     * Spatie already scopes bindings; this also memoizes fallbacks when
     * composers and controllers share the same settings group.
     *
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    function safe_settings(string $class): object
    {
        $request = null;
        if (function_exists('app') && app()->bound('request')) {
            try {
                $request = request();
            } catch (Throwable) {
                $request = null;
            }
        }

        if ($request) {
            /** @var array<string, object> $memo */
            $memo = $request->attributes->get('_safe_settings', []);
            if (array_key_exists($class, $memo)) {
                return $memo[$class];
            }
        }

        try {
            $instance = app($class);
        } catch (Throwable $e) {
            report($e);
            $instance = settings_fallback($class);
        }

        if ($request) {
            $memo[$class] = $instance;
            $request->attributes->set('_safe_settings', $memo);
        }

        return $instance;
    }
}
