<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantFoodMeal extends Model
{
    use HasFactory;

    protected $table = 'restaurant_food_meals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'restaurant_food_id',
        'meal_id',
    ];
}
