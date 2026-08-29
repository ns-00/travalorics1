<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Attribute;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\Attribute;
use Travalorics\Common\Models\BaseModel;

class Translation extends BaseModel
{
    protected $table = 'attribute_translations';

    protected $fillable = [
        'locale', 'name',
    ];

    /**
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
