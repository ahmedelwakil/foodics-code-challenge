<?php

namespace App\Models;

use App\Models\Pivots\OrderProductsPivot;
use App\Models\Pivots\ProductIngredientPivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Define the Ingredients Relation
     *
     * @return BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, ProductIngredientPivot::class)
            ->withPivot(ProductIngredientPivot::PIVOT_ATTRIBUTES)
            ->withTimestamps();
    }

    /**
     * Define the Orders Relation
     *
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, OrderProductsPivot::class)
            ->withPivot(OrderProductsPivot::PIVOT_ATTRIBUTES)
            ->withTimestamps();
    }
}