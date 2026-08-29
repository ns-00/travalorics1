<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

class ProductSelectorController extends BaseController
{
    /**
     * Selector page
     *
     * @return mixed
     */
    public function selectorPage(): mixed
    {
        return view('panel::product_selector.index');
    }
}
