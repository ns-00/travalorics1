<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Services\Checkout;

use Travalorics\Common\Services\Fee\BalanceService;
use Travalorics\Common\Services\Fee\Shipping;
use Travalorics\Common\Services\Fee\Subtotal;
use Travalorics\Common\Services\Fee\Tax;
use Travalorics\Common\Services\Fee\Coupon;

class FeeService extends BaseService
{
    /**
     * @return void
     */
    public function calculate(): void
    {
        $classes = $this->getFeeMethodClasses();
        foreach ($classes as $class) {
            (new $class($this->checkoutService))->addFee();
        }
    }

    /**
     * Get order fee method classes
     * @return mixed
     */
    public function getFeeMethodClasses(): mixed
    {
        $classes = [
            Subtotal::class,
            Tax::class,
            Shipping::class,
            Coupon::class,
            BalanceService::class,
        ];

        return fire_hook_filter('service.checkout.fee.methods', $classes);
    }
}
