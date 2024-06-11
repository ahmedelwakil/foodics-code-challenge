<?php

namespace App\Repositories;

use App\Models\Pivots\OrderProductsPivot;

class OrderProductsRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return OrderProductsPivot::class;
    }
}