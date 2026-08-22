<?php

namespace App\Filament\Concerns;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Spatie\LaravelSettings\SettingsMapper;

trait EnsuresSettingsRowsExist
{
    protected function ensureAllSettingsProperties(object $settings, array $payload): array
    {
        $reflection = new ReflectionClass($settings);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();

            if (! array_key_exists($name, $payload)) {
                $payload[$name] = $settings->{$name} ?? $property->getDefaultValue();
            }
        }

        return $payload;
    }

    protected function ensureSettingsRowsExist(object $settings): void
    {
        $mapper = app(SettingsMapper::class);
        $getConfig = new ReflectionMethod($mapper, 'getConfig');
        $getConfig->setAccessible(true);
        $config = $getConfig->invoke($mapper, get_class($settings));

        $repo = $config->getRepository();
        $group = $config->getGroup();
        $existing = collect($repo->getPropertiesInGroup($group))->keys();

        foreach ($config->getReflectedProperties()->keys() as $name) {
            if (! $existing->contains($name)) {
                $repo->createProperty($group, $name, $settings->{$name} ?? null);
            }
        }
    }
}
