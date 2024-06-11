<?php

namespace App\Repositories;

use App\Models\Ingredient;

class IngredientRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Ingredient::class;
    }
}