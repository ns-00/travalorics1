<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models;

class Locale extends BaseModel
{
    protected $table = 'settings';

    protected $fillable = [
        'name', 'code', 'image', 'position', 'active',
    ];
}
