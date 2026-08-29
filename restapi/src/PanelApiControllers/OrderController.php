<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\PanelApiControllers;

use Illuminate\Http\Request;
use Travalorics\Common\Models\Order;
use Travalorics\Common\Resources\OrderSimple;

class OrderController extends BaseController
{
    /**
     * @param  Order  $order
     * @param  Request  $request
     * @return mixed
     */
    public function updateNote(Order $order, Request $request): mixed
    {
        try {
            $adminNote = $request->get('admin_note');
            $order->update([
                'admin_note' => $adminNote,
            ]);

            return update_json_success(new OrderSimple($order));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }

    }
}
