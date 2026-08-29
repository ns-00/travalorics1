<?php

namespace Travalorics\Common\Models;

class Coupon extends BaseModel
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_to',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    public function isValid($cartAmount = 0)
    {
        if (!$this->active) {
            return false;
        }

        if ($this->usage_limit > 0 && $this->used_count >= $this->usage_limit) {
            return false;
        }

        $now = now()->startOfDay();
        if ($this->valid_from && $now->lt($this->valid_from->copy()->startOfDay())) {
            return false;
        }

        if ($this->valid_to && $now->gt($this->valid_to->copy()->startOfDay())) {
            return false;
        }

        if ($this->min_order_amount > 0 && $cartAmount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    public function getDiscountAmount($cartAmount)
    {
        if ($this->type === 'percentage') {
            $discount = $cartAmount * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        return min($discount, $cartAmount);
    }
}
