<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'order',
        'background_color',
        'text_color',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dishes()
    {
        return $this->hasMany(Dish::class)->orderBy('position')->orderBy('id');
    }

    protected static function booted()
    {
        static::creating(function (Subcategory $subcategory) {
            if (is_null($subcategory->order)) {
                $max = static::where('category_id', $subcategory->category_id)->max('order') ?? 0;
                $subcategory->order = $max + 1;
            }
        });
    }
}
