<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Exception;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Repositories\BrandRepo;
use Travalorics\Common\Repositories\CategoryRepo;
use Travalorics\Common\Repositories\PageRepo;
use Travalorics\Common\Repositories\SettingRepo;
use Travalorics\Common\Repositories\SpecialPageRepo;
use Travalorics\Panel\Repositories\ThemeRepo;

class ThemeController extends BaseController
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $data = [
            'themes' => ThemeRepo::getInstance()->getListFromPath(),
        ];

        return travalorics_view('panel::themes.index', $data);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function settings(): mixed
    {
        $data = [
            'categories' => CategoryRepo::getInstance()->getTwoLevelCategories(),
            'brands'     => BrandRepo::getInstance()->withActive()->builder()->get(),
            'specials'   => SpecialPageRepo::getInstance()->getOptions(),
            'pages'      => PageRepo::getInstance()->withActive()->builder()->get(),
            'themes'     => ThemeRepo::getInstance()->getListFromPath(),
        ];

        return travalorics_view('panel::themes.settings', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function updateSettings(Request $request): mixed
    {
        $settings   = $request->all();
        $settingUrl = panel_route('themes_settings.index');

        try {
            ThemeRepo::getInstance()->updateSetting($settings);

            return redirect($settingUrl)
                ->with('instance', $settings)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect($settingUrl)->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Request  $request
     * @param  string  $themeCode
     * @return mixed
     * @throws Throwable
     */
    public function enable(Request $request, string $themeCode): mixed
    {
        try {
            $status = $request->get('status');
            if (empty($status)) {
                SettingRepo::getInstance()->updateSystemValue('theme', '');
            } else {
                SettingRepo::getInstance()->updateSystemValue('theme', $themeCode);
            }

            return json_success(panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
