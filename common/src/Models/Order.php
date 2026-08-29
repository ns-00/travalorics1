<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Throwable;
use Travalorics\Common\Models\Order\Fee;
use Travalorics\Common\Models\Order\History;
use Travalorics\Common\Models\Order\Item;
use Travalorics\Common\Models\Order\Payment;
use Travalorics\Common\Models\Order\Shipment;
use Travalorics\Common\Notifications\OrderNewNotification;
use Travalorics\Common\Notifications\OrderUpdateNotification;
use Travalorics\Common\Services\CartService;
use Travalorics\Common\Services\OrderService;
use Travalorics\Common\Services\StateMachineService;

class Order extends BaseModel
{
    use Notifiable;

    protected $table = 'orders';

    protected $fillable = [
        'number', 'customer_id', 'customer_group_id', 'shipping_address_id', 'billing_address_id', 'customer_name',
        'email', 'calling_code', 'telephone', 'total', 'locale', 'currency_code', 'currency_value', 'ip', 'user_agent',
        'status', 'shipping_method_code', 'shipping_method_name', 'shipping_customer_name', 'shipping_calling_code',
        'shipping_telephone', 'shipping_country', 'shipping_country_id', 'shipping_state_id', 'shipping_state',
        'shipping_city', 'shipping_address_1', 'shipping_address_2', 'shipping_zipcode', 'billing_method_code',
        'billing_method_name', 'billing_customer_name', 'billing_calling_code', 'billing_telephone', 'billing_country',
        'billing_country_id', 'billing_state_id', 'billing_state', 'billing_city', 'billing_address_1',
        'billing_address_2', 'billing_zipcode', 'comment', 'admin_note', 'coupon_code', 'coupon_discount',
    ];

    protected $appends = [
        'total_format',
        'status_format',
    ];

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Order::class, 'parent_id', 'id')->whereRaw('id != parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'parent_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function shippingCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Order items.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'order_id', 'id');
    }

    /**
     * Order fees.
     *
     * @return HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'order_id', 'id');
    }

    /**
     * Order histories.
     *
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'order_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'order_id', 'id');
    }

    /**
     * Order payments.
     *
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    /**
     * Calculate order subtotal.
     *
     * @return float
     */
    public function calcSubtotal(): float
    {
        return round($this->items->sum('subtotal'), 2);
    }

    /**
     * Calculate order total.
     *
     * @return float
     */
    public function calcTotal(): float
    {
        return round($this->fees->sum('value'), 2);
    }

    /**
     * Format total by currency.
     *
     * @return string
     */
    public function getTotalFormatAttribute(): string
    {
        return currency_format($this->total, $this->currency_code, $this->currency_value);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getStatusColorAttribute(): string
    {
        $statusCode = $this->status;
        if ($statusCode == null) {
            return '';
        }

        if ($statusCode == StateMachineService::UNPAID) {
            return 'warning';
        } elseif (in_array($statusCode, [StateMachineService::CREATED, StateMachineService::CANCELLED])) {
            return 'danger';
        } else {
            return 'success';
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getStatusFormatAttribute(): string
    {
        $statusCode = $this->status;
        if ($statusCode == null) {
            return '';
        }

        $statusMap = array_column(StateMachineService::getAllStatuses(), 'name', 'status');

        return $statusMap[$statusCode] ?? '';
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function addToCart(): void
    {
        CartService::getInstance($this->customer_id)->addOrderToCart($this);
    }

    /**
     * @return string
     */
    public function getBillingMethodNameAttribute($value): string
    {
        try {
            $plugin = plugin($this->billing_method_code);
            if ($plugin) {
                return $plugin->getLocaleName();
            }
        } catch (Exception $e) {
            // Ignore and fallback to original value
        }

        return (string) $value;
    }

    /**
     * @return string
     */
    public function getShippingMethodNameAttribute($value): string
    {
        try {
            $plugin = plugin($this->shipping_method_code);
            if ($plugin) {
                return $plugin->getLocaleName();
            }
        } catch (Exception $e) {
            // Ignore and fallback to original value
        }

        return (string) $value;
    }

    /**
     * @return Order
     * @throws Throwable
     */
    public function reorder(): Order
    {
        return OrderService::getInstance($this->id)->reorder();
    }

    /**
     * Send a new order notification.
     *
     * @return void
     */
    public function notifyNewOrder(): void
    {
        try {
            $useQueue = system_setting('use_queue', false);
            if ($useQueue) {
                $this->notify(new OrderNewNotification($this));
            } else {
                $this->notifyNow(new OrderNewNotification($this));
            }
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
        }
    }

    /**
     * Send an order status update notification.
     *
     * @param  $fromCode
     * @return void
     */
    public function notifyUpdateOrder($fromCode): void
    {
        try {
            $useQueue = system_setting('use_queue', false);
            if ($useQueue) {
                $this->notify(new OrderUpdateNotification($this, $fromCode));
            } else {
                $this->notifyNow(new OrderUpdateNotification($this, $fromCode));
            }
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
        }
    }
}
