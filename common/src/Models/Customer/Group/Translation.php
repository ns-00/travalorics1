<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Customer\Group;

use Travalorics\Common\Models\BaseModel;

class Translation extends BaseModel
{
    protected $table = 'customer_group_translations';

    protected $fillable = [
        'locale', 'name', 'description',
    ];
}
