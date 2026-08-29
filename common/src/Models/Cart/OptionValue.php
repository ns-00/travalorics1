<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Cart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\CartItem;

/**
 * Shopping cart option value record model
 */
class OptionValue extends Model
{
    use HasFactory;

    protected $table = 'cart_option_values';

    protected $fillable = [
        'cart_item_id',
        'option_id',
        'option_value_id',
        'option_name',
        'option_value_name',
        'price_adjustment',
    ];

    protected $casts = [
        'option_name'       => 'array',
        'option_value_name' => 'array',
        'price_adjustment'  => 'decimal:2',
    ];

    /**
     * Get associated shopping cart items
     */
    public function cartItem(): BelongsTo
    {
        return $this->belongsTo(CartItem::class);
    }

    /**
     * Get localization option name
     */
    public function getLocalizedOptionName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return $this->option_name[$locale] ?? $this->option_name['en'] ?? '';
    }

    /**
     * Get localization option value name
     */
    public function getLocalizedOptionValueName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return $this->option_value_name[$locale] ?? $this->option_value_name['en'] ?? '';
    }

    /**
     * Get formatted price adjustments
     */
    public function getFormattedPriceAdjustment(): string
    {
        if ($this->price_adjustment == 0) {
            return '';
        }

        $prefix = $this->price_adjustment > 0 ? '+' : '';

        return $prefix.currency_format($this->price_adjustment);
    }
}
