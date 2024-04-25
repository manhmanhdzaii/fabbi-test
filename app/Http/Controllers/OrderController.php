<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\getListFoodsRequest;
use App\Http\Requests\Order\getRestaurantByMealRequest;
use App\Http\Requests\Order\Step1Request;
use App\Http\Requests\Order\Step2Request;
use App\Http\Requests\Order\Step3Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    /**
     * Save session data in step 1
     *
     * @param Step1Request $request
     */
    public function step1(Step1Request $request)
    {
        return $this->success(updateSessionOrders($request->validated()));
    }

    /**
     * Save session data in step 2
     *
     * @param Step2Request $request
     */
    public function step2(Step2Request $request)
    {
        return $this->success(updateSessionOrders($request->validated()));
    }

    /**
     * Save session data in step 3
     *
     * @param Step3Request $request
     */
    public function step3(Step3Request $request)
    {
        $data = [
            'allDish' => json_decode($request->input('allDish'), true)
        ];

        return $this->success(updateSessionOrders($data));
    }

    /**
     * Get a list of restaurants by meal condition in session
     *
     * @param getRestaurantByMealRequest $request
     */
    public function getRestaurantsByMeal(getRestaurantByMealRequest $request)
    {
        updateSessionOrders($request->validated());

        return $this->success(getRestaurantsByMeal());
    }

    /**
     * Get a list of dishes by meal and restaurant in the session
     *
     * @param getListFoodsRequest $request
     */
    public function getListFoodsByOrder(getListFoodsRequest $request)
    {
        $orders = Session::get('orders') ?? [];
        $orders =  $request->validated() + $orders;
        Session::put('orders', $orders);

        $data = getListFoodsByOrder();
        if (!empty(optional($orders)['allDish'])) {
            $dishIds = $data->pluck('id')->toArray();
            $newDish = [];
            foreach ($orders['allDish'] as $item) {
                if (in_array($item['dish'], $dishIds)) {
                    $newDish[] = $item;
                }
            }

            $dataDish = [
                'allDish' => $newDish
            ];

            Session::put('orders', $dataDish + Session::get('orders'));
        }

        return $this->success([
            'dishes' => $data,
            'allDish' => @Session::get('orders')['allDish'] ?? []
        ]);
    }

    /**
     * Get preview order data
     *
     * @param getListFoodsRequest $request
     */
    public function getPreviewOrder()
    {
        $orders = Session::get('orders');
        $ordersPreview = [];
        $ordersPreview['meal'] = getNameMealById($orders['meal']);
        $ordersPreview['number_people'] = $orders['number_people'];

        $ordersPreview['restaurant'] = getNameRestaurantById($orders['restaurant']);

        foreach ($orders['allDish'] as $key => $dish) {
            $ordersPreview['allDish'][] = [
                'dish' => getNameFootById($dish['dish']),
                'ration' => $dish['ration']
            ];
        }

        return $this->success($ordersPreview);
    }
}
