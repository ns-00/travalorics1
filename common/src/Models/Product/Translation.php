<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Product;

use Travalorics\Common\Models\BaseModel;

class Translation extends BaseModel
{
    protected $table = 'product_translations';

    protected $fillable = [
        'product_id', 'locale', 'name', 'summary', 'content', 'selling_point', 'meta_title', 'meta_description',
        'meta_keywords',
    ];
}
