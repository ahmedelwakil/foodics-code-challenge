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

    /**
     * @param array $ingredientIds
     * @return array
     */
    public function findByIngredientIds(array $ingredientIds)
    {
        return $this->repository->findByIngredientIds($ingredientIds);
    }
}