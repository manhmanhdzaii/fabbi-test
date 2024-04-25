<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurant_food_meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_food_id');
            $table->unsignedBigInteger('meal_id');
            $table->foreign('restaurant_food_id')->references('id')->on('restaurant_foods');
            $table->foreign('meal_id')->references('id')->on('meals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_food_meals');
    }
};
