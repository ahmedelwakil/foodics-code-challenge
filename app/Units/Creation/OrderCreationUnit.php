<?php

namespace App\Units\Creation;

use App\Utils\OrderStatusUtil;
use Illuminate\Contracts\Support\Arrayable;

class OrderCreationUnit implements Arrayable
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var array
     */
    private $products;

    /**
     * OrderCreationUnit constructor.
     * @param array $products
     * @param string $status
     */
    public function __construct(array $products, string $status = OrderStatusUtil::PENDING)
    {
        $this->products = $products;
        $this->status = $status;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->status,
            'products' => $this->products
        ];
    }
}