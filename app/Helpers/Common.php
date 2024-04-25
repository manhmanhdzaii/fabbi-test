<?php

use App\Repositories\FoodRepository;
use App\Repositories\MealRepository;
use App\Repositories\RestaurantRepository;
use Illuminate\Support\Facades\Session;

/**
 * Get a list of meals
 * 
 * @return mixed
 */
function getListMeals(): mixed
{
    return app(MealRepository::class)->getAll();
}

/**
 * update session orders
 * 
 * @param array $data
 * @return mixed
 */
function updateSessionOrders(array $data): bool
{
    $orders = Session::get('orders') ?? [];
    $orders = $data + $orders;
    Session::put('orders', $orders);

    return true;
}

/**
 * Get the order session
 * 
 * @return mixed
 */
function getOrderSession(): mixed
{
    return Session::get('orders');
}

/**
 * Get a list of restaurants by meal condition in session
 * 
 * @return mixed
 */
function getRestaurantsByMeal(): mixed
{
    return app(RestaurantRepository::class)->getRestaurantsByMeal();
}

/**
 * Get a list of dishes by meal and restaurant in the session
 * 
 * @return mixed
 */
function getListFoodsByOrder(): mixed
{
    return app(FoodRepository::class)->getListFoodsByOrder();
}

/**
 * Get meal name by id
 * 
 * @param int $id
 * @return mixed
 */
function getNameMealById(int $id): mixed
{
    return app(MealRepository::class)->find($id)->name;
}

/**
 * Get restaurant name by id
 * 
 * @param int $id
 * @return mixed
 */
function getNameRestaurantById(int $id): mixed
{
    return app(RestaurantRepository::class)->find($id)->name;
}

/**
 * Get food name by id
 * 
 * @param int $id
 * @return mixed
 */
function getNameFootById(int $id)
{
    return app(FoodRepository::class)->find($id)->name;
}
