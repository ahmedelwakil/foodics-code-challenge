<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Arr;
use Throwable;

class OrderService extends BaseService
{
    /**
     * @var OrderRepository
     */
    protected $repository;

    /**
     * @var OrderProductsService
     */
    protected $orderProductsService;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param OrderProductsService $orderProductsService
     */
    public function __construct(OrderRepository $orderRepository, OrderProductsService $orderProductsService)
    {
        parent::__construct($orderRepository);
        $this->orderProductsService = $orderProductsService;
    }

    /**
     * @param mixed $data
     * @return Order|mixed
     * @throws Throwable
     */
    public function add($data)
    {
        return $this->applyInTransaction(function () use ($data) {
            $order = $this->repository->add(Arr::except($data, 'products'));
            $this->orderProductsService->attachProductsToOrder($order->id, $data['products']);
            return $order;
        });
    }
}