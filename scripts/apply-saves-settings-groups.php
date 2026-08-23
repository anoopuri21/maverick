<?php

$files = glob(__DIR__ . '/../app/Filament/Pages/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);

    if (! str_contains($content, 'protected function saveSettingsGroup')) {
        continue;
    }

    $content = str_replace(
        "use App\Filament\Concerns\HandlesCloudinaryImageFields;\nuse App\Filament\Concerns\EnsuresSettingsRowsExist;\n",
        "use App\Filament\Concerns\SavesSettingsGroups;\n",
        $content
    );

    $content = str_replace(
        "use App\Filament\Concerns\EnsuresSettingsRowsExist;\nuse App\Filament\Concerns\HandlesCloudinaryImageFields;\n",
        "use App\Filament\Concerns\SavesSettingsGroups;\n",
        $content
    );

    $content = str_replace(
        "    use HandlesCloudinaryImageFields;\n    use EnsuresSettingsRowsExist;\n",
        "    use SavesSettingsGroups;\n",
        $content
    );

    $content = preg_replace(
        '/\n\s*protected function saveSettingsGroup\(string \$settingsClass, array \$payload\): void\s*\{.*?\n\s*\}\n/s',
        "\n",
        $content,
        1
    );

    file_put_contents($file, $content);
    echo "Updated: {$file}\n";
}
