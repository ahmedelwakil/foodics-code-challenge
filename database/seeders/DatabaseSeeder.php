<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** Seeding Products Table */
        $beefBurger = Product::factory()->create(['name' => 'Beef Burger']);
        $chickenBurger = Product::factory()->create(['name' => 'Chicken Burger']);

        /** Seeding Ingredients Table */
        $beef = Ingredient::factory()->create(['name' => 'Beef']);
        $chicken = Ingredient::factory()->create(['name' => 'Chicken']);
        $cheese = Ingredient::factory()->create(['name' => 'Cheese']);
        $onion = Ingredient::factory()->create(['name' => 'Onion']);

        /** Attaching Ingredients to Products */
        $beefBurger->ingredients()->attach([
            $beef->id => ['amount' => 150],
            $cheese->id => ['amount' => 30],
            $onion->id => ['amount' => 20],
        ]);

        $chickenBurger->ingredients()->attach([
            $chicken->id => ['amount' => 120],
            $cheese->id => ['amount' => 20],
            $onion->id => ['amount' => 10],
        ]);

        /** Seeding Stocks Table */
        Stock::factory()->create(['ingredient_id' => $beef->id, 'amount' => 20000, 'threshold' => 10000]);
        Stock::factory()->create(['ingredient_id' => $chicken->id, 'amount' => 20000, 'threshold' => 10000]);
        Stock::factory()->create(['ingredient_id' => $cheese->id, 'amount' => 5000, 'threshold' => 2500]);
        Stock::factory()->create(['ingredient_id' => $onion->id, 'amount' => 1000, 'threshold' => 500]);
    }
}
