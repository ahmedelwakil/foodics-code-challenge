<?php

namespace App\Services;

use App\Repositories\IngredientRepository;

class IngredientService extends BaseService
{
    /**
     * @var IngredientRepository
     */
    protected $repository;

    /**
     * IngredientService constructor.
     * @param IngredientRepository $ingredientRepository
     */
    public function __construct(IngredientRepository $ingredientRepository)
    {
        parent::__construct($ingredientRepository);
    }
}