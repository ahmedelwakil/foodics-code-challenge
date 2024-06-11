<?php

namespace App\Models;

use App\Models\Pivots\ProductIngredientPivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
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
     * Define the Products Relation
     *
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, ProductIngredientPivot::class)
            ->withPivot(ProductIngredientPivot::PIVOT_ATTRIBUTES)
            ->withTimestamps();
    }
}