<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\PanelApiControllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return read_json_success(Auth::guard('admin')->user());
    }
}
