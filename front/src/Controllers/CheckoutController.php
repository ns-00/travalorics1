<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Exceptions\Unauthorized;
use Travalorics\Common\Services\CheckoutService;
use Travalorics\Common\Services\StateMachineService;
use Travalorics\Front\Requests\CheckoutConfirmRequest;

class CheckoutController extends Controller
{
    /**
     * Get checkout data and render page.
     *
     * @return mixed
     * @throws Throwable
     */
    public function index(): mixed
    {
        try {
            $checkout = CheckoutService::getInstance();
            $result   = $checkout->getCheckoutResult();
            if (empty($result['cart_list'])) {
                return redirect(front_route('carts.index'))->withErrors(['error' => 'Empty Cart']);
            }

            return travalorics_view('checkout.index', $result);
        } catch (Unauthorized $e) {
            session(['front_redirect_uri' => front_route('checkout.index')]);
            return redirect(front_route('login.index'))->withErrors(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            return redirect(front_route('carts.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update checkout, include shipping address, shipping method, billing address, billing method
     *
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function update(Request $request): mixed
    {
        try {
            $data     = $request->all();
            $checkout = CheckoutService::getInstance();
            $checkout->updateValues($data);
            $result = $checkout->getCheckoutResult();

            return json_success(__('front/common.updated_success'), $result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Apply a coupon to the checkout
     */
    public function applyCoupon(Request $request): mixed
    {
        try {
            $couponCode = $request->input('coupon_code');
            $checkout = CheckoutService::getInstance();
            
            if (empty($couponCode)) {
                $checkout->updateValues(['coupon_code' => null, 'coupon_discount' => 0]);
                return json_success('Coupon removed', $checkout->getCheckoutResult());
            }

            $coupon = \Travalorics\Common\Models\Coupon::where('code', $couponCode)->where('active', true)->first();
            if (!$coupon) {
                throw new Exception('Invalid or expired coupon code.');
            }

            $checkout->updateValues(['coupon_code' => $couponCode]);
            $result = $checkout->getCheckoutResult();
            
            // Check if coupon was actually applied (if it wasn't, the Fee service would have removed it)
            if (empty($result['checkout']['coupon_code'])) {
                throw new Exception('Coupon is not applicable to this order (minimum amount not met, etc).');
            }

            return json_success('Coupon applied successfully', $result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Confirm checkout and place order
     *
     * @param  CheckoutConfirmRequest  $request
     * @return mixed
     * @throws Throwable
     */
    public function confirm(CheckoutConfirmRequest $request): mixed
    {
        try {
            $checkout = CheckoutService::getInstance();
            $data     = $request->all();
            unset($data['reference']);
            if ($data) {
                $checkout->updateValues($data);
            }

            $order = $checkout->confirm();
            StateMachineService::getInstance($order)->changeStatus(StateMachineService::UNPAID, '', true);

            return json_success(front_trans('common.submitted_success'), $order);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
