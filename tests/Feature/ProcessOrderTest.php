<?php

namespace Tests\Feature;

use App\Mail\StockBelowThreshold;
use App\Models\Order;
use App\Models\Stock;
use App\Services\OrderService;
use App\Utils\OrderStatusUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProcessOrderTest extends TestCase
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

    public function test_process_order_1()
    {
        Mail::fake();

        /** @var Order $order **/
        $order = Order::factory()->create();
        $order->products()->attach([
            1 => ['quantity' => 5],
            2 => ['quantity' => 2]
        ]);

        $this->service->processOrder($order->id);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_products', 2);
        $this->assertDatabaseCount('stock_transactions', 4);

        $stocks = Stock::all()->keyBy('ingredient_id')->toArray();
        $this->assertEquals(19250, $stocks['1']['amount']);
        $this->assertEquals(19760, $stocks['2']['amount']);
        $this->assertEquals(4810, $stocks['3']['amount']);
        $this->assertEquals(880, $stocks['4']['amount']);

        $updatedOrder = Order::find($order->id);
        $this->assertEquals(OrderStatusUtil::CONFIRMED, $updatedOrder->status);

        Mail::assertNothingSent();
    }

    public function test_process_order_2()
    {
        Mail::fake();

        /** @var Order $order **/
        $order = Order::factory()->create();
        $order->products()->attach([
            1 => ['quantity' => 100]
        ]);

        $this->service->processOrder($order->id);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_products', 1);
        $this->assertDatabaseCount('stock_transactions', 0);

        $stocks = Stock::all()->keyBy('ingredient_id')->toArray();
        $this->assertEquals(20000, $stocks['1']['amount']);
        $this->assertEquals(20000, $stocks['2']['amount']);
        $this->assertEquals(5000, $stocks['3']['amount']);
        $this->assertEquals(1000, $stocks['4']['amount']);

        $updatedOrder = Order::find($order->id);
        $this->assertEquals(OrderStatusUtil::REJECTED, $updatedOrder->status);

        Mail::assertNothingSent();
    }

    public function test_process_order_3()
    {
        Mail::fake();

        /** @var Order $order **/
        $order = Order::factory()->create();
        $order->products()->attach([
            1 => ['quantity' => 30]
        ]);

        $this->service->processOrder($order->id);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_products', 1);
        $this->assertDatabaseCount('stock_transactions', 3);

        $stocks = Stock::all()->keyBy('ingredient_id')->toArray();
        $this->assertEquals(20000 - (30 * 150), $stocks['1']['amount']);
        $this->assertEquals(20000, $stocks['2']['amount']);
        $this->assertEquals(5000 - (30 * 30), $stocks['3']['amount']);
        $this->assertEquals(1000 - (30 * 20), $stocks['4']['amount']);

        $updatedOrder = Order::find($order->id);
        $this->assertEquals(OrderStatusUtil::CONFIRMED, $updatedOrder->status);

        $onionStock = Stock::where('ingredient_id', '=', 4)->first();
        $this->assertEquals(1, $onionStock->merchant_notified);

        Mail::hasSent(StockBelowThreshold::class);
    }
}
