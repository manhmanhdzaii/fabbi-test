<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Repositories\Interface\IRestaurantRepository;

class RestaurantRepository extends BaseRepository implements IRestaurantRepository
{
    public function __construct(Restaurant $model)
    {
        parent::__construct($model);
    }

    /**
     * Get a list of restaurants by meal condition in session
     *
     * @return mixed
     */
    public function getRestaurantsByMeal(): mixed
    {
        $orderSession = getOrderSession();
        $meal = optional($orderSession)['meal'];

        return $this->model->select('restaurants.id', 'restaurants.name')
            ->when(!empty($meal), function ($q) use ($meal) {
                $q->join('restaurant_foods', 'restaurants.id', 'restaurant_foods.restaurant_id')
                    ->join('restaurant_food_meals', 'restaurant_foods.id', 'restaurant_food_meals.restaurant_food_id')
                    ->where('restaurant_food_meals.meal_id', $meal);
            })
            ->distinct()
            ->get();
    }
}
