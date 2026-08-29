<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Models\Order;

use Exception;
use Travalorics\Common\Models\BaseModel;
use Travalorics\Common\Services\StateMachineService;

class History extends BaseModel
{
    protected $table = 'order_histories';

    protected $fillable = [
        'order_id', 'status', 'notify', 'comment',
    ];

    /**
     * @return string
     * @throws Exception
     */
    public function getStatusFormatAttribute(): string
    {
        $statusCode = $this->status;
        if ($statusCode == null) {
            return '';
        }

        $statusMap = array_column(StateMachineService::getAllStatuses(), 'name', 'status');

        return $statusMap[$statusCode] ?? '';
    }
}
