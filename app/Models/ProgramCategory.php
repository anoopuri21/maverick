<?php

namespace App\Models;

use App\Concerns\EnsuresUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramCategory extends Model
{
    use EnsuresUniqueSlug;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}