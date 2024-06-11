<?php

namespace App\Services;

use App\Repositories\OrderProductsRepository;
use Illuminate\Support\Arr;

class OrderProductsService extends BaseService
{
    /**
     * @var OrderProductsRepository
     */
    protected $repository;

    /**
     * OrderService constructor.
     * @param OrderProductsRepository $orderProductsRepository
     */
    public function __construct(OrderProductsRepository $orderProductsRepository)
    {
        parent::__construct($orderProductsRepository);
    }

    /**
     * @param int $orderId
     * @param array $products
     * @return void
     */
    public function attachProductsToOrder(int $orderId, array $products)
    {
        $data = [];
        foreach ($products as $product) {
            $product['order_id'] = $orderId;
            $data[] = $product;
        }
        $this->repository->insert($data);
    }
}