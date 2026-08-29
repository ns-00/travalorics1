<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Attribute\Value;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\Attribute\Value;
use Travalorics\Common\Models\BaseModel;

class Translation extends BaseModel
{
    protected $table = 'attribute_value_translations';

    protected $fillable = [
        'attribute_value_id', 'locale', 'name',
    ];

    /**
     * @return BelongsTo
     */
    public function value(): BelongsTo
    {
        return $this->belongsTo(Value::class, 'attribute_value_id', 'id');
    }
}
