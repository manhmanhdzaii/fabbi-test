<?php

namespace App\Repositories;

use App\Models\Meal;
use App\Repositories\Interface\IMealRepository;

class MealRepository extends BaseRepository implements IMealRepository
{
    public function __construct(Meal $model)
    {
        parent::__construct($model);
    }
}
