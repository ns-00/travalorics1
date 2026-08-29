<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Travalorics\Common\Models\Admin;
use Travalorics\Common\Models\BaseModel;

class Token extends BaseModel
{
    protected $table = 'admin_tokens';

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
