<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Travalorics\Common\Models\Attribute\Group;
use Travalorics\Common\Models\Attribute\Value;
use Travalorics\Common\Traits\Translatable;

class Attribute extends BaseModel
{
    use Translatable;

    protected $table = 'attributes';

    protected $fillable = [
        'category_id', 'attribute_group_id', 'position',
    ];

    /**
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }

    /**
     * @return HasMany
     */
    public function productAttributes(): HasMany
    {
        return $this->hasMany(Product\Attribute::class, 'attribute_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'attribute_group_id', 'id');
    }
}
