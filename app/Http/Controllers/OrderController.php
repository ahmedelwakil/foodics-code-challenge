<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Units\Creation\OrderCreationUnit;
use App\Units\Response\OrderResponseUnit;
use App\Utils\HttpStatusCodeUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrderController extends BaseController
{
    /**
     * @var OrderService
     */
    protected $service;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        parent::__construct($orderService);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function place(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'numeric', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'gt:0'],
        ]);

        if ($validator->fails())
            return $this->response($validator->errors()->toArray(), HttpStatusCodeUtil::BAD_REQUEST, 'Validation Error!');

        $orderCreationUnit = new OrderCreationUnit($request->get('products', []));
        $order = $this->service->add($orderCreationUnit->toArray());
        $orderResponse = new OrderResponseUnit($order);
        return $this->response($orderResponse->toArray(), HttpStatusCodeUtil::OK, 'Order Dispatched Successfully!');
    }
}