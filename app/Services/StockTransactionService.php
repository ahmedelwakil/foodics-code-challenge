<?php

namespace App\Services;

use App\Repositories\StockTransactionRepository;

class StockTransactionService extends BaseService
{
    /**
     * @var StockTransactionRepository
     */
    protected $repository;

    /**
     * StockTransactionService constructor.
     * @param StockTransactionRepository $stockTransactionRepository
     */
    public function __construct(StockTransactionRepository $stockTransactionRepository)
    {
        parent::__construct($stockTransactionRepository);
    }
}