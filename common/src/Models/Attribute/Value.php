<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Attribute;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Travalorics\Common\Models\BaseModel;

class Value extends BaseModel
{
    protected $table = 'attribute_values';

    public $fillable = [
        'attribute_id',
    ];

    /**
     * Define translations relationship
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        $class = \Travalorics\Common\Models\Attribute\Value\Translation::class;

        return $this->hasMany($class, 'attribute_value_id', 'id');
    }

    /**
     * Locale translation object
     *
     * @return mixed
     * @throws \Exception
     */
    public function translation(): mixed
    {
        $class = \Travalorics\Common\Models\Attribute\Value\Translation::class;

        return $this->hasOne($class, 'attribute_value_id', 'id')
            ->where('locale', locale_code());
    }
}
