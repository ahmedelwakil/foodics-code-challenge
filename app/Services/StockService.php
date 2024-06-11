<?php

namespace App\Services;

use App\Repositories\StockRepository;

class StockService extends BaseService
{
    /**
     * @var StockRepository
     */
    protected $repository;

    /**
     * StockService constructor.
     * @param StockRepository $stockRepository
     */
    public function __construct(StockRepository $stockRepository)
    {
        parent::__construct($stockRepository);
    }
}