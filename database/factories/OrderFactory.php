<?php

namespace Database\Factories;

use App\Utils\OrderStatusUtil;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => OrderStatusUtil::getAllStatuses()[rand(0, 2)],
        ];
    }
}