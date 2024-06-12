<?php

namespace App\Services;

use App\Exceptions\EntityNotFoundException;
use App\Jobs\ProcessOrderJob;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Utils\OrderStatusUtil;
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
     * @var StockService
     */
    protected $stockService;

    /**
     * @var StockTransactionService
     */
    protected $stockTransactionService;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param OrderProductsService $orderProductsService
     * @param StockService $stockService
     * @param StockTransactionService $stockTransactionService
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderProductsService $orderProductsService,
        StockService $stockService,
        StockTransactionService $stockTransactionService)
    {
        parent::__construct($orderRepository);
        $this->orderProductsService = $orderProductsService;
        $this->stockService = $stockService;
        $this->stockTransactionService = $stockTransactionService;
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
            dispatch(new ProcessOrderJob($order->id));
            return $order;
        });
    }

    /**
     * @param int $orderId
     * @throws EntityNotFoundException
     * @throws Throwable
     */
    public function processOrder(int $orderId)
    {
        $order = $this->find($orderId);
        $stockLevels = $this->calculateStockLevels($order);
        $stocks = $this->stockService->findByIngredientIds(array_keys($stockLevels));

        if ($this->validateStockLevels($stocks, $stockLevels)) {
            $this->applyInTransaction(function () use ($order, $stocks, $stockLevels) {
                $stockTransactions = [];
                foreach ($stockLevels as $ingredientId => $level) {
                    $stock = $stocks[$ingredientId];
                    $stockTransactions[] = [
                        'order_id' => $order->id,
                        'stock_id' => $stock->id,
                        'amount' => $level
                    ];
                    $this->stockService->update($stock->id, ['amount' => $stock->amount - $level]);
                    $this->stockService->checkThreshold($stock, $stock->amount - $level);
                }
                $this->stockTransactionService->insert($stockTransactions);
                $this->repository->update($order->id, ['status' => OrderStatusUtil::CONFIRMED]);
            });
        } else {
            $this->repository->update($order->id, ['status' => OrderStatusUtil::REJECTED]);
        }
    }

    /**
     * @param Order $order
     * @return array
     */
    private function calculateStockLevels(Order $order)
    {
        $stockLevels = [];
        foreach ($order->products as $product) {
            foreach ($product->ingredients as $ingredient) {
                if (isset($stockLevels[$ingredient->id]))
                    $stockLevels[$ingredient->id] += $ingredient->pivot->amount * $product->pivot->quantity;
                else
                    $stockLevels[$ingredient->id] = $ingredient->pivot->amount * $product->pivot->quantity;
            }
        }
        return $stockLevels;
    }

    /**
     * @param $stocks
     * @param array $stockLevels
     * @return bool
     */
    public function validateStockLevels($stocks, array $stockLevels): bool
    {
        $isValid = true;
        foreach ($stockLevels as $ingredientId => $level) {
            $stock = $stocks[$ingredientId];
            if ($stock->amount < $level) {
                $isValid = false;
                break;
            }
        }
        return $isValid;
    }
}