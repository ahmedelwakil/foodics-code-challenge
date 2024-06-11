<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductIngredientPivot extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_ingredients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'ingredient_id',
        'amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
    ];

    /**
     * The attributes that are retrieved in pivot.
     *
     * @var array<int, string>
     */
    const PIVOT_ATTRIBUTES = [
        'product_id',
        'ingredient_id',
        'amount',
    ];
}