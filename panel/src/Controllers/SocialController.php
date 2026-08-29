<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Repositories\Customer\SocialRepo;
use Travalorics\Common\Repositories\SettingRepo;

class SocialController extends BaseController
{
    public function index()
    {
        $data = [
            'providers' => SocialRepo::getInstance()->getProviders(),
            'socials'   => system_setting('social', []),
        ];

        return travalorics_view('panel::socials.index', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(Request $request): mixed
    {
        try {
            $data = $request->all();
            SettingRepo::getInstance()->updateSystemValue('social', $data);

            return update_json_success();
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
