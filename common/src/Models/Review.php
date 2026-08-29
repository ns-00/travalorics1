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
use Travalorics\Common\Models\Order\Item;

class Review extends BaseModel
{
    protected $table = 'reviews';

    protected $fillable = [
        'customer_id', 'product_id', 'order_item_id', 'rating', 'content', 'like', 'dislike', 'active',
        'title', 'status', 'attribute_ratings', 'featured_rank',
    ];

    protected $casts = [
        'attribute_ratings' => 'array',
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
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'order_item_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ReviewAttribute::class, 'review_id', 'id');
    }
}
