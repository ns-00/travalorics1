<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Plugin\Repositories;

use Travalorics\Common\Repositories\SettingRepo as CommonSettingRepo;
use Travalorics\Plugin\Models\Setting;

class SettingRepo extends CommonSettingRepo
{
    /**
     * Get plugin active field.
     *
     * @return array
     */
    public function getPluginActiveField(): array
    {
        return [
            'name'     => 'active',
            'label'    => panel_trans('common.status'),
            'type'     => 'bool',
            'required' => true,
        ];
    }

    /**
     * Get billing plugin available field.
     *
     * @return array
     */
    public function getPluginAvailableField(): array
    {
        return [
            'name'    => 'available',
            'label'   => panel_trans('common.available'),
            'type'    => 'checkbox',
            'options' => [
                ['label' => 'PC WEB', 'value' => 'pc_web'],
                ['label' => 'Mobile Web', 'value' => 'mobile_web'],
                ['label' => 'WeChat Mini', 'value' => 'wechat_mini'],
                ['label' => 'WeChat Official', 'value' => 'wechat_official'],
                ['label' => 'APP', 'value' => 'app'],
            ],
            'required' => true,
            'rules'    => 'required',
        ];
    }

    /**
     * Get all fields by plugin code.
     *
     * @param  $pluginCode
     * @return mixed
     */
    public function getPluginFields($pluginCode): mixed
    {
        return Setting::query()
            ->where('space', $pluginCode)
            ->get()
            ->keyBy('name');
    }
}
