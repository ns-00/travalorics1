<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Travalorics\Common\Models\OrderReturn\History;

class OrderReturn extends BaseModel
{
    protected $table = 'order_returns';

    protected $fillable = [
        'customer_id', 'order_id', 'order_item_id', 'product_id', 'number', 'order_number', 'product_name', 'product_sku',
        'opened', 'quantity', 'comment', 'status',
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(Order\Item::class, 'order_item_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'order_return_id', 'id')->orderByDesc('id');
    }

    /**
     * @return string
     */
    public function getStatusFormatAttribute(): string
    {
        return trans('common/rma.'.$this->status);
    }

    /**
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        $statusCode = $this->status;
        if ($statusCode == null) {
            return '';
        }
        $map = self::statusColorMap();

        return $map[$statusCode] ?? 'secondary';
    }

    /**
     * Get status color map.
     *
     * @return array
     */
    private static function statusColorMap(): array
    {
        return [
            \Travalorics\Common\Services\ReturnStateService::CREATED   => 'secondary',
            \Travalorics\Common\Services\ReturnStateService::PENDING   => 'warning',
            \Travalorics\Common\Services\ReturnStateService::REFUNDED  => 'info',
            \Travalorics\Common\Services\ReturnStateService::RETURNED  => 'success',
            \Travalorics\Common\Services\ReturnStateService::CANCELLED => 'danger',
        ];
    }
}
