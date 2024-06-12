<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class StockRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Stock::class;
    }

    /**
     * @param array $ingredientIds
     * @return Builder[]|Collection|Model[]|null[]
     */
    public function findByIngredientIds(array $ingredientIds)
    {
        return $this->model->whereIn('ingredient_id', $ingredientIds)->get()->keyBy('ingredient_id');
    }
}