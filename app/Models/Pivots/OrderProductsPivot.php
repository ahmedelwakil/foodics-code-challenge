<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProductsPivot extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * The attributes that are retrieved in pivot.
     *
     * @var array<int, string>
     */
    const PIVOT_ATTRIBUTES = [
        'order_id',
        'product_id',
        'quantity',
    ];
}