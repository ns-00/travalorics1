<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers\Account;

use Exception;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\OrderReturn;
use Travalorics\Common\Repositories\Order\ItemRepo;
use Travalorics\Common\Repositories\OrderRepo;
use Travalorics\Common\Repositories\OrderReturnRepo;
use Travalorics\Front\Controllers\BaseController;

class OrderReturnController extends BaseController
{
    public function index(Request $request)
    {
        $filters = $request->all();

        $filters['customer_id'] = current_customer_id();

        $data = [
            'order_returns' => OrderReturnRepo::getInstance()->list($filters),
        ];

        return travalorics_view('account.order_return_index', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function create(Request $request): mixed
    {
        $number  = $request->get('order_number');
        $filters = [
            'number'      => $number,
            'customer_id' => current_customer_id(),
        ];
        $order   = OrderRepo::getInstance()->builder($filters)->firstOrFail();
        $options = ItemRepo::getInstance()->getOptions($order);

        $data = [
            'number'  => $number,
            'order'   => $order,
            'options' => $options,
        ];

        return travalorics_view('account.order_return_create', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception|Throwable
     */
    public function store(Request $request): mixed
    {
        $data = $request->all();
        try {
            $data['customer_id'] = current_customer_id();
            $orderReturn         = OrderReturnRepo::getInstance()->create($data);

            return redirect(account_route('order_returns.index'))
                ->with('instance', $orderReturn);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    /**
     * @param  OrderReturn  $orderReturn
     * @return mixed
     */
    public function show(OrderReturn $orderReturn): mixed
    {
        $data = [
            'order_return' => $orderReturn,
            'histories'    => $orderReturn->histories()->orderByDesc('id')->get(),
        ];

        return travalorics_view('account.order_return_show', $data);
    }

    /**
     * @param  OrderReturn  $order_return
     * @return mixed
     * @throws Exception
     */
    public function destroy(OrderReturn $order_return): mixed
    {
        $order_return->delete();

        return redirect(account_route('order_returns.index'));
    }
}
