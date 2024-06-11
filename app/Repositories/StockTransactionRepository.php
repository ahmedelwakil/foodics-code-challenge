<?php

namespace App\Repositories;

use App\Models\StockTransaction;

class StockTransactionRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return StockTransaction::class;
    }
}