<?php

namespace Tests\Unit;

use App\Mail\StockBelowThreshold;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var StockService */
    protected $service;

    /**
     * OrderServiceTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = resolve(StockService::class);
    }

    public function test_threshold_check()
    {
        Mail::fake();

        /** @var Stock $stock **/
        $stock = Stock::factory()->create([
            'ingredient_id' => 1,
            'amount' => 5000,
            'threshold' => 2500,
            'merchant_notified' => false
        ]);

        $this->service->checkThreshold($stock, 4000);
        Mail::assertNothingSent();

        $this->service->checkThreshold($stock, 2000);
        Mail::assertSent(StockBelowThreshold::class);

        $stock->merchant_notified = true;
        $stock->save();

        $this->service->checkThreshold($stock, 1000);
        Mail::assertSent(StockBelowThreshold::class, 1);
    }
}
