<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Attribute\Group;

use Travalorics\Common\Models\BaseModel;

class Translation extends BaseModel
{
    protected $table = 'attribute_group_translations';

    protected $fillable = [
        'attribute_group_id', 'locale', 'name',
    ];
}
