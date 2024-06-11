<?php

namespace App\Models;

use App\Models\Pivots\OrderProductsPivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
    ];

    /**
     * Define the Products Relation
     *
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, OrderProductsPivot::class)
            ->withPivot(OrderProductsPivot::PIVOT_ATTRIBUTES)
            ->withTimestamps();
    }
}