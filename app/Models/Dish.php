<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'category_id', 'image', 'visible', 'position'];

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

}
