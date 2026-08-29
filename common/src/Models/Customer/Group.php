<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Customer;

use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Traits\Translatable;

class Group extends BaseModel
{
    use Translatable;

    protected $table = 'customer_groups';

    protected $fillable = [
        'level', 'mini_cost', 'discount_rate',
    ];

    public function getForeignKey()
    {
        return 'customer_group_id';
    }
}
