<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RestaurantFood extends Model
{
    use HasFactory;

    protected $table = 'restaurant_foods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'restaurant_id',
        'food_id',
    ];

    /**
     * List of meals with the restaurant's dishes
     * 
     * @return BelongsToMany
     */
    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class, 'restaurant_food_meals', 'restaurant_food_id', 'meal_id');
    }
}
