<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Customer;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Models\Customer;
use Travalorics\Common\Models\Product;

class Favorite extends BaseModel
{
    protected $table = 'customer_favorites';

    protected $fillable = [
        'customer_id', 'product_id',
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
