<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Order;

use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Models\Order;

class Payment extends BaseModel
{
    protected $table = 'order_payments';

    protected $fillable = [
        'order_id', 'charge_id', 'amount', 'handling_fee', 'paid', 'reference', 'certificate',
    ];

    protected $appends = [
        'amount_format',
        'handling_fee_format',
        'paid_format',
        'status_format',
    ];

    protected $casts = [
        'reference' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * Format amount by currency.
     *
     * @return string
     */
    public function getAmountFormatAttribute(): string
    {
        $order = $this->order;

        return currency_format($this->amount, $order->currency_code ?? '', $order->currency_value ?? 1);
    }

    /**
     * Format handling fee by currency.
     *
     * @return string
     */
    public function getHandlingFeeFormatAttribute(): string
    {
        $order = $this->order;

        return currency_format($this->handling_fee, $order->currency_code ?? '', $order->currency_value ?? 1);
    }

    /**
     * Format paid status.
     *
     * @return string
     */
    public function getPaidFormatAttribute(): string
    {
        return $this->paid ? trans('panel/order.paid') : trans('panel/order.unpaid');
    }

    /**
     * Get status format for display.
     *
     * @return string
     */
    public function getStatusFormatAttribute(): string
    {
        return $this->paid ? trans('panel/order.payment_success') : trans('panel/order.payment_pending');
    }
}
