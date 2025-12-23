<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cocktail;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'visible',
        'featured_on_cover',
        'position',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'featured_on_cover' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function foodPairings()
{
    return $this->belongsToMany(FoodPairing::class);
}
public function wines()
{
    return $this->belongsToMany(Wine::class);
}

public function cocktails()
{
    return $this->belongsToMany(Cocktail::class, 'cocktail_dish');
}

}
