<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Attribute;

use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Traits\Translatable;

class Group extends BaseModel
{
    use Translatable;

    protected $table = 'attribute_groups';

    protected $fillable = [
        'position',
    ];

    public function getForeignKey(): string
    {
        return 'attribute_group_id';
    }
}
