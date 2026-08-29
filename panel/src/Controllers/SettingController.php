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
use Travalorics\Common\Repositories\CategoryRepo;
use Travalorics\Common\Repositories\CurrencyRepo;
use Travalorics\Common\Repositories\MailRepo;
use Travalorics\Common\Repositories\PageRepo;
use Travalorics\Common\Repositories\SettingRepo;
use Travalorics\Common\Repositories\WeightClassRepo;
use Travalorics\Common\Services\AI\AIServiceManager;
use Travalorics\Panel\Repositories\ContentAIRepo;
use Travalorics\Panel\Repositories\ThemeRepo;

class SettingController
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function index(): mixed
    {
        $data = [
            'locales'        => locales()->toArray(),
            'currencies'     => CurrencyRepo::getInstance()->enabledList()->toArray(),
            'weight_classes' => WeightClassRepo::getInstance()->withActive()->all()->toArray(),
            'categories'     => CategoryRepo::getInstance()->getTwoLevelCategories(),
            'pages'          => PageRepo::getInstance()->withActive()->builder()->get(),
            'themes'         => ThemeRepo::getInstance()->getListFromPath(),
            'mail_engines'   => MailRepo::getInstance()->getEngines(),
            'ai_models'      => AIServiceManager::getInstance()->getModelsForSelect(),
            'ai_prompts'     => ContentAIRepo::getInstance()->getPrompts(),
        ];

        return travalorics_view('panel::settings.index', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function update(Request $request): mixed
    {
        $settings = $request->all();

        $oldAdminName = panel_name();
        $settingUrl   = panel_route('settings.index');
        
        try {
            SettingRepo::getInstance()->updateValues($settings);
            $newAdminName = $settings['panel_name'] ?? 'panel';
            $settingUrl   = str_replace($oldAdminName, $newAdminName, $settingUrl);

            return redirect($settingUrl)
                ->with('instance', $settings)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect($settingUrl)->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
