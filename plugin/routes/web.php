<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

use Illuminate\Support\Facades\Route;
use Travalorics\Plugin\Controllers\PluginController;
use Travalorics\Plugin\Controllers\SettingController;

Route::post('/plugins/enabled', [PluginController::class, 'updateStatus'])->name('plugins.update_status');
Route::get('/plugins/settings', [SettingController::class, 'index'])->name('plugins.settings');
Route::put('/plugins/settings', [SettingController::class, 'update'])->name('plugins.settings.update');
Route::resource('/plugins', PluginController::class);


