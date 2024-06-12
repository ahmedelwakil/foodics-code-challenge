<?php

namespace Tests\Unit;

use App\Models\Order;
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

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        Order::factory()->create();
        $this->assertDatabaseCount('orders', 1);
    }
}
