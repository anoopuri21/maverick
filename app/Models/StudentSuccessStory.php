<?php

namespace App\Models;

use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSuccessStory extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'role',
        'quote',
        'photo',
        'photo_asset_id',
        'stars',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'stars' => 'integer',
    ];

    public function cardPayload(): array
    {
        $name = $this->name ?? '';

        return [
            'name' => $name,
            'role' => $this->role ?? '',
            'quote' => $this->quote ?? '',
            'photo' => media_url($this->getMediaUrl('photo')),
            'initials' => collect(preg_split('/\s+/', $name))
                ->filter()
                ->map(fn ($w) => mb_substr($w, 0, 1))
                ->take(2)
                ->implode(''),
        ];
    }
}
