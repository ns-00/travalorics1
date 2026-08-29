<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers;

use Exception;
use Illuminate\Http\Request;
use Travalorics\Front\Services\SitemapService;

class SitemapController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        try {
            return SitemapService::getInstance()->response($request);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
