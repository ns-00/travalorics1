<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\OrderReturn;
use Travalorics\Common\Repositories\OrderReturnRepo;
use Travalorics\Common\Services\ReturnStateService;

class OrderReturnController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();
        $data    = [
            'criteria'      => OrderReturnRepo::getCriteria(),
            'order_returns' => OrderReturnRepo::getInstance()->list($filters),
        ];

        return travalorics_view('panel::order_returns.index', $data);
    }

    /**
     * OrderReturn creation page.
     *
     * @return mixed
     * @throws Exception
     */
    public function create(): mixed
    {
        return $this->form(new OrderReturn);
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data        = $request->all();
            $orderReturn = OrderReturnRepo::getInstance()->create($data);

            return redirect(panel_route('order_returns.index'))
                ->with('instance', $orderReturn)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  OrderReturn  $order_return
     * @return mixed
     * @throws Exception
     */
    public function edit(OrderReturn $order_return): mixed
    {
        return $this->form($order_return);
    }

    /**
     * @param  $orderReturn
     * @return mixed
     * @throws Exception
     */
    public function form($orderReturn): mixed
    {
        $data = [
            'next_statuses' => ReturnStateService::getInstance($orderReturn)->nextBackendStatuses(),
            'order_return'  => $orderReturn,
        ];

        return travalorics_view('panel::order_returns.form', $data);
    }

    /**
     * @param  Request  $request
     * @param  OrderReturn  $orderReturn
     * @return RedirectResponse
     */
    public function update(Request $request, OrderReturn $orderReturn): RedirectResponse
    {
        try {
            $data        = $request->all();
            $orderReturn = OrderReturnRepo::getInstance()->update($orderReturn, $data);

            return redirect(panel_route('order_returns.index'))
                ->with('instance', $orderReturn)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  OrderReturn  $order_return
     * @return RedirectResponse
     */
    public function destroy(OrderReturn $order_return): RedirectResponse
    {
        try {
            OrderReturnRepo::getInstance()->destroy($order_return);

            return back()->with('success', panel_trans('common.deleted_success'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Request  $request
     * @param  OrderReturn  $orderReturn
     * @return mixed
     */
    public function changeStatus(Request $request, OrderReturn $orderReturn): mixed
    {
        $status  = $request->get('status');
        $comment = $request->get('comment');
        try {
            ReturnStateService::getInstance($orderReturn)->changeStatus($status, $comment, true);

            return json_success(panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
