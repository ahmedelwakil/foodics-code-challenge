<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Product::class;
    }
}