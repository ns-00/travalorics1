<?php

namespace Travalorics\Common\Services\Fee;

use Travalorics\Common\Models\Coupon as CouponModel;

class Coupon extends BaseService
{
    /**
     * @return void
     * @throws \Exception
     */
    public function addFee(): void
    {
        $checkoutData = $this->checkoutService->getCheckoutData();
        $couponCode = $checkoutData['coupon_code'] ?? null;

        if (!$couponCode) {
            return;
        }

        $coupon = CouponModel::where('code', $couponCode)->where('active', true)->first();
        if (!$coupon) {
            $this->checkoutService->updateValues(['coupon_code' => null, 'coupon_discount' => 0]);
            return;
        }

        $subtotal = $this->checkoutService->getSubTotal();
        
        if (!$coupon->isValid($subtotal)) {
            $this->checkoutService->updateValues(['coupon_code' => null, 'coupon_discount' => 0]);
            return;
        }

        $discount = $coupon->getDiscountAmount($subtotal);
        if ($discount <= 0) {
            return;
        }

        $this->checkoutService->updateValues(['coupon_discount' => $discount]);

        $fee = [
            'code'   => 'coupon',
            'value'  => -$discount,
            'title'  => 'Discount (' . $coupon->code . ')',
            'total'  => -$discount,
            'sort'   => 50,
        ];

        $this->checkoutService->addFeeList($fee);
    }
}
