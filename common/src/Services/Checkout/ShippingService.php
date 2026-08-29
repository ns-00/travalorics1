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
use Illuminate\Support\Str;
use Throwable;
use Travalorics\Common\Entities\ShippingEntity;
use Travalorics\Common\Services\CheckoutService;
use Travalorics\Plugin\Core\Plugin;
use Travalorics\Plugin\Repositories\PluginRepo;

class ShippingService
{
    public static ?array $shippingMethods = null;

    protected ?CheckoutService $checkoutService;

    protected ?ShippingEntity $shippingEntity;

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return new static;
    }

    /**
     * @param  CheckoutService  $checkoutService
     * @return ShippingService
     * @throws Throwable
     */
    public function setCheckoutService(CheckoutService $checkoutService): static
    {
        $this->checkoutService = $checkoutService;
        $this->setShippingEntity(ShippingEntity::getInstance()->setCheckoutService($checkoutService));

        return $this;
    }

    /**
     * @param  ShippingEntity  $entity
     * @return static
     */
    public function setShippingEntity(ShippingEntity $entity): static
    {
        $this->shippingEntity = $entity;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getMethods(): array
    {
        if (! is_null(self::$shippingMethods)) {
            return self::$shippingMethods;
        }

        $shippingPlugins = PluginRepo::getInstance()->getShippingMethods();

        $shippingMethods = [];
        foreach ($shippingPlugins as $shippingPlugin) {
            $plugin = $shippingPlugin->plugin;

            $bootClass = $this->getBootClass($plugin);
            if (! method_exists($bootClass, 'getQuotes')) {
                throw new Exception(front_trans('checkout.shipping_quote_error', ['classname' => $bootClass]));
            }

            $quotes = (new $bootClass)->getQuotes($this->shippingEntity);

            if ($quotes) {
                // Determine if we should forcefully use Custom Shipping and drop DHL
                if ($plugin->getCode() === 'custom_shipping' && isset($quotes[0]['cost']) && $quotes[0]['cost'] > 0) {
                    $hasForcedCustomShipping = true;
                }

                $shippingMethods[] = [
                    'code'   => $plugin->getCode(),
                    'name'   => $plugin->getLocaleName(),
                    'quotes' => $quotes,
                ];
            }
        }

        // If Custom Shipping has a cost > 0, remove DHL from options
        if (isset($hasForcedCustomShipping) && $hasForcedCustomShipping) {
            $shippingMethods = array_filter($shippingMethods, function ($method) {
                return $method['code'] !== 'dhl_shipping';
            });
            // Re-index array values after filter
            $shippingMethods = array_values($shippingMethods);
        }

        fire_hook_filter('common.service.checkout.shipping.methods', $shippingMethods);

        return self::$shippingMethods = $shippingMethods;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getQuoteCodes(): array
    {
        $quoteCodes      = [];
        $shippingMethods = $this->getMethods();
        foreach ($shippingMethods as $shippingMethod) {
            foreach ($shippingMethod['quotes'] as $quote) {
                $quoteCodes[] = $quote['code'];
            }
        }

        return $quoteCodes;
    }

    /**
     * @param  Plugin  $shippingPlugin
     * @return string
     */
    private function getBootClass(Plugin $shippingPlugin): string
    {
        $pluginCode = $shippingPlugin->getCode();
        $pluginName = Str::studly($pluginCode);

        return "Plugin\\{$pluginName}\\Boot";
    }
}
