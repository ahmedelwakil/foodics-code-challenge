<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Order::class;
    }
}