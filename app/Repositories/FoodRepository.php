<?php

namespace App\Repositories;

use App\Models\Food;
use App\Repositories\Interface\IFoodRepository;

class FoodRepository extends BaseRepository implements IFoodRepository
{
    public function __construct(Food $model)
    {
        parent::__construct($model);
    }

    /**
     * Get a list of dishes by meal and restaurant in the session
     *
     * @return mixed
     */
    public function getListFoodsByOrder(): mixed
    {
        $orderSession = getOrderSession();
        $meal = optional($orderSession)['meal'];
        $restaurant = optional($orderSession)['restaurant'];

        return $this->model->select('foods.id', 'foods.name')
            ->join('restaurant_foods', 'foods.id', 'restaurant_foods.food_id')
            ->join('restaurant_food_meals', 'restaurant_foods.id', 'restaurant_food_meals.restaurant_food_id')
            ->where('restaurant_food_meals.meal_id', $meal)
            ->when(!empty($mean), function ($q) use ($meal) {
                $q->where('restaurant_food_meals.meal_id', $meal);
            })
            ->when(!empty($restaurant), function ($q) use ($restaurant) {
                $q->where('restaurant_foods.restaurant_id', $restaurant);
            })
            ->distinct()
            ->get();
    }
}
