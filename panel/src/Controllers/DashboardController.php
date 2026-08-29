<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Travalorics\Panel\Repositories\Dashboard\OrderRepo;
use Travalorics\Panel\Repositories\Dashboard\ProductRepo;
use Travalorics\Panel\Repositories\DashboardRepo;

class DashboardController extends BaseController
{
    /**
     * Dashboard for panel home page.
     *
     * @return mixed
     * @throws \Exception
     */
    public function index(): mixed
    {
        $data = [
            'cards' => DashboardRepo::getInstance()->getCards(),
            'order' => [
                'latest_week' => OrderRepo::getInstance()->getOrderCountLatestWeek(),
            ],
            'top_sale_products' => ProductRepo::getInstance()->getTopSaleProducts(),
        ];

        return travalorics_view('panel::dashboard', $data);
    }
}
