<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\FrontApiControllers;

use Exception;

class SettingController extends BaseController
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function index(): mixed
    {
        $settings = setting('system');

        $settings['locales']    = locales()->select(['name', 'code']);
        $settings['currencies'] = currencies()->select(['name', 'code']);

        return read_json_success($settings);
    }
}
