<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\FoodRepository;
use App\Repositories\Interface\BaseRepositoryInterface;
use App\Repositories\Interface\IFoodRepository;
use App\Repositories\Interface\IMealRepository;
use App\Repositories\Interface\IRestaurantRepository;
use App\Repositories\MealRepository;
use App\Repositories\RestaurantRepository;
use App\Services\BaseService;
use App\Services\Interface\BaseServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->bindings($this->registerRepositories());
        $this->bindings($this->registerServices());
    }

    /**
     * Loop all register to binding
     *
     * @param array $classes
     */
    private function bindings(array $classes)
    {
        foreach ($classes as $interface => $implement) {
            $this->app->bind($interface, $implement);
        }
    }

    /**
     * Register repositories for binding
     *
     * @return string[]
     */
    private function registerRepositories(): array
    {
        return [
            BaseRepositoryInterface::class => BaseRepository::class,
            IRestaurantRepository::class => RestaurantRepository::class,
            IFoodRepository::class => FoodRepository::class,
            IMealRepository::class => MealRepository::class,
        ];
    }

    /**
     * Register services for binding
     *
     * @return string[]
     */
    private function registerServices(): array
    {
        return [
            BaseServiceInterface::class => BaseService::class
        ];
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
