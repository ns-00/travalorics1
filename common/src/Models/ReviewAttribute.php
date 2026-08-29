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

class ReviewAttribute extends BaseModel
{
    protected $table = 'review_attributes';

    protected $fillable = [
        'review_id', 'key', 'value',
    ];

    public $timestamps = true;

    /**
     * @return BelongsTo
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id', 'id');
    }
}
