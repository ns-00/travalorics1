<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Region;

use Travalorics\Common\Models\BaseModel;

class State extends BaseModel
{
    protected $table = 'region_states';

    protected $fillable = [
        'country_id', 'state_id',
    ];
}
