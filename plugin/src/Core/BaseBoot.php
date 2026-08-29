<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Plugin\Core;

use Travalorics\Plugin\Resources\PluginResource;

abstract class BaseBoot
{
    protected Plugin $plugin;

    protected PluginResource $pluginResource;

    public function __construct()
    {
        $className            = static::class;
        $names                = explode('\\', $className);
        $spaceName            = $names[1];
        $this->plugin         = app('plugin')->getPlugin($spaceName);
        $this->pluginResource = new PluginResource($this->plugin);
    }

    abstract public function init();
}
