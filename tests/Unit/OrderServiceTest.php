<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Stock;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var OrderService */
    protected $service;

    /**
     * OrderServiceTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = resolve(OrderService::class);
    }

    public function test_adding_order()
    {
        $orderData = [
            'products' => [
                [
                    'product_id' => 1,
                    'quantity' => 5
                ],
                [
                    'product_id' => 2,
                    'quantity' => 2
                ]
            ]
        ];

        $this->service->add($orderData);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_products', 2);
    }

    public function test_stock_level_calculation()
    {
        /** @var Order $order **/
        $order = Order::factory()->create();
        $order->products()->attach([
            1 => ['quantity' => 5],
            2 => ['quantity' => 2]
        ]);

        $stockLevels = $this->service->calculateStockLevels($order);
        $this->assertEquals(750, $stockLevels[1]);
        $this->assertEquals(240, $stockLevels[2]);
        $this->assertEquals(190, $stockLevels[3]);
        $this->assertEquals(120, $stockLevels[4]);
    }

    public function test_stock_levels_validation()
    {
        $stocks = [
            1 => Stock::factory()->create(['ingredient_id' => 1, 'amount' => 500]),
            2 => Stock::factory()->create(['ingredient_id' => 2, 'amount' => 1000]),
        ];
        $stockLevels = [1 => 200, 2 => 600];
        $this->assertTrue($this->service->validateStockLevels($stocks, $stockLevels));
        $stockLevels = [1 => 600, 2 => 200];
        $this->assertFalse($this->service->validateStockLevels($stocks, $stockLevels));
        $stockLevels = [1 => 200, 2 => 1200];
        $this->assertFalse($this->service->validateStockLevels($stocks, $stockLevels));
        $stockLevels = [1 => 600, 2 => 1200];
        $this->assertFalse($this->service->validateStockLevels($stocks, $stockLevels));
    }
}
