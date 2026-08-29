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
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\Order\Payment;

class PaymentController extends BaseController
{
    /**
     * Toggle payment status (paid/unpaid)
     *
     * @param  Request  $request
     * @param  int  $id
     * @return mixed
     * @throws Exception|Throwable
     */
    public function active(Request $request, int $id): mixed
    {
        try {
            $payment = Payment::query()->findOrFail($id);

            $payment->paid = $request->get('status');
            $payment->saveOrFail();

            return json_success(panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
