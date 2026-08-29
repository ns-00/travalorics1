<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Product;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Models\Product;

class Image extends BaseModel
{
    protected $table = 'product_images';

    protected $fillable = ['path', 'is_cover', 'belong_sku', 'position'];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
