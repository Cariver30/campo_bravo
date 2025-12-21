<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CocktailCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'order'];

    public function items()
    {
        return $this->hasMany(Cocktail::class, 'category_id')->orderBy('position')->orderBy('id');
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            if (is_null($category->order)) {
                $category->order = (static::max('order') ?? 0) + 1;
            }
        });
    }
}
