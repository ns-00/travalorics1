<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Page;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Models\Page;

class Translation extends BaseModel
{
    protected $table = 'page_translations';

    protected $fillable = [
        'page_id', 'locale', 'title', 'content', 'template', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    /**
     * @return BelongsTo
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
