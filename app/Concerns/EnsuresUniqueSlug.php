<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait EnsuresUniqueSlug
{
    public static function bootEnsuresUniqueSlug(): void
    {
        static::saving(function ($model) {
            $slug = trim((string) ($model->slug ?? ''));

            if ($slug === '') {
                $source = $model->title ?? $model->name ?? '';
                $slug = Str::slug((string) $source);
            }

            if ($slug === '') {
                $slug = 'item-'.Str::random(8);
            }

            $base = $slug;
            $i = 2;
            $query = static::query();

            if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($model), true)) {
                $query->withTrashed();
            }

            if ($model->exists) {
                $query->where($model->getKeyName(), '!=', $model->getKey());
            }

            while ((clone $query)->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }

            $model->slug = $slug;
        });
    }

    public function scopeHasPublicSlug($query)
    {
        return $query->whereNotNull('slug')->where('slug', '!=', '');
    }
}
