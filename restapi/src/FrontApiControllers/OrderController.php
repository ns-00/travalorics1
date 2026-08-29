<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\FrontApiControllers;

use Exception;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\Order;
use Travalorics\Common\Repositories\OrderRepo;
use Travalorics\Common\Resources\OrderDetail;
use Travalorics\Common\Services\CartService;
use Travalorics\Common\Services\StateMachineService;
use Travalorics\Front\Services\PaymentService;

class OrderController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();

        $filters['customer_id'] = token_customer_id();

        $orders = OrderRepo::getInstance()->list($filters);

        return read_json_success($orders);
    }

    /**
     * Order detail
     *
     * @param  Order  $order
     * @return mixed
     */
    public function show(Order $order): mixed
    {
        if ($order->customer_id != token_customer_id()) {
            return json_fail('Unauthorized', null, 403);
        }
        $order->load(['items.review', 'fees']);
        $result = new OrderDetail($order);

        return read_json_success($result);
    }

    /**
     * Order detail
     *
     * @param  int  $number
     * @return mixed
     */
    public function numberShow(int $number): mixed
    {
        try {
            $order = OrderRepo::getInstance()->getOrderByNumber($number, true);
            if ($order->customer_id != token_customer_id()) {
                return json_fail('Unauthorized', null, 403);
            }

            $order->load(['items.review', 'fees']);
            $result = new OrderDetail($order);

            return read_json_success($result);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  int  $number
     * @return mixed
     */
    public function pay(int $number): mixed
    {
        try {
            $order = OrderRepo::getInstance()->getOrderByNumber($number);
            if ($order->customer_id != token_customer_id()) {
                return json_fail('Unauthorized', null, 403);
            }

            return PaymentService::getInstance($order)->apiPay();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  int  $number
     * @return mixed
     */
    public function cancel(int $number): mixed
    {
        try {
            $order = OrderRepo::getInstance()->getOrderByNumber($number);
            if ($order->customer_id != token_customer_id()) {
                return json_fail('Unauthorized', null, 403);
            }

            StateMachineService::getInstance($order)->changeStatus(StateMachineService::CANCELLED);

            return update_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  int  $number
     * @return mixed
     */
    public function complete(int $number): mixed
    {
        try {
            $order = OrderRepo::getInstance()->getOrderByNumber($number);
            if ($order->customer_id != token_customer_id()) {
                return json_fail('Unauthorized', null, 403);
            }

            StateMachineService::getInstance($order)->changeStatus(StateMachineService::COMPLETED);

            return update_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  int  $number
     * @return mixed
     * @throws Throwable
     */
    public function reorder(int $number): mixed
    {
        try {
            $customerID = token_customer_id();
            $order      = OrderRepo::getInstance()->getOrderByNumber($number);
            if ($order->customer_id != $customerID) {
                return json_fail('Unauthorized', null, 403);
            }

            CartService::getInstance($customerID)->reorder($order);

            return update_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
