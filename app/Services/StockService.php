<?php

namespace App\Services;

use App\Mail\StockBelowThreshold;
use App\Models\Stock;
use App\Repositories\StockRepository;
use Illuminate\Support\Facades\Mail;

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

    public function checkThreshold(Stock $stock, float $nemAmount)
    {
        if (!$stock->merchant_notified && $nemAmount <= $stock->threshold) {
            Mail::to('ahmed-tfelwakil@hotmail.com')->send(new StockBelowThreshold($stock));
            $this->repository->update($stock->id, ['merchant_notified' => true]);
        }
    }
}