<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Meal;
use App\Models\Restaurant;
use App\Models\RestaurantFood;
use App\Models\RestaurantFoodMeal;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitDataSeeder extends Seeder
{
    protected $time;

    public function __construct()
    {
        $this->time = Carbon::now();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Food::truncate();
            Meal::truncate();
            Restaurant::truncate();
            RestaurantFood::truncate();
            RestaurantFoodMeal::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $dataRaw = file_get_contents(resource_path('data/dishes.json'));
            $data = json_decode($dataRaw)->dishes;

            # Create separate data for restaurants, dishes, and meals
            $foods = [];
            $restaurants = [];
            $meals = [];
            foreach ($data as $item) {
                if (!in_array($item->restaurant, $restaurants)) $restaurants[] = $item->restaurant;

                foreach ($item->availableMeals as $meal) {
                    if (!in_array($meal, $meals)) $meals[] = $meal;
                }

                if (!in_array($item->name, $foods)) $foods[] = $item->name;
            }

            Food::insert($this->createDataInsertWithName($foods));
            Meal::insert($this->createDataInsertWithName($meals));
            Restaurant::insert($this->createDataInsertWithName($restaurants));

            # Add intermediate data
            // get inserted data as array ['name' => id]
            $restaurants = Restaurant::whereIn('name', $restaurants)->pluck('id', 'name')->toArray();
            $meals = Meal::whereIn('name', $meals)->pluck('id', 'name')->toArray();

            // Handling relational data
            foreach ($data as $item) {
                $food = Food::where('name', $item->name)->first();
                $food->restaurants()->attach($restaurants[$item->restaurant]);

                $restaurantFood = RestaurantFood::where('restaurant_id', $restaurants[$item->restaurant])->where('food_id', $food->id)->first();

                $restaurantFood->meals()->attach(array_map(function ($mealName) use ($meals) {
                    return $meals[$mealName];
                }, $item->availableMeals));
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }

    /**
     * Create insert data with list of names
     * 
     * @return array
     */
    private function createDataInsertWithName(array $data): array
    {
        return array_map(function ($item) {
            return ['name' => $item, 'created_at' => $this->time, 'updated_at' => $this->time];
        }, $data);
    }
}
