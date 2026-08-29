<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models;

class TaxRule extends BaseModel
{
    protected $table = 'tax_rules';

    protected $fillable = [
        'tax_class_id', 'tax_rate_id', 'based', 'priority',
    ];
}
