<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Services\Checkout;

use Exception;
use Travalorics\Plugin\Repositories\PluginRepo;
use Travalorics\Plugin\Resources\Checkout\PaymentMethodItem;

class BillingService
{
    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return new static;
    }

    /**
     * @throws Exception
     */
    public function getMethods(): array
    {
        $billingPlugins = PluginRepo::getInstance()->getBillingMethods();

        $methods = PaymentMethodItem::collection($billingPlugins)->jsonSerialize();

        return fire_hook_filter('service.checkout.billing.methods', $methods);
    }
}
