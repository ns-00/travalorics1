<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models;

class State extends BaseModel
{
    protected $table = 'states';

    protected $fillable = [
        'country_id', 'country_code', 'name', 'code', 'position', 'active',
    ];
}
