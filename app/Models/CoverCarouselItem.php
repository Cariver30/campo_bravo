<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverCarouselItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link_label',
        'link_url',
        'position',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
