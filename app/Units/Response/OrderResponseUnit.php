<?php

namespace App\Units\Response;

use App\Models\Order;
use Illuminate\Contracts\Support\Arrayable;

class OrderResponseUnit implements Arrayable
{
    /**
     * @var Order
     */
    private $order;

    /**
     * OrderResponseUnit constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $data['id'] = $this->order->id;
        $data['status'] = $this->order->status;
        foreach ($this->order->products as $product) {
            $data['products'][] = [
                'name' => $product->name,
                'quantity' => $product->pivot->quantity
            ];
        }
        return $data;
    }
}