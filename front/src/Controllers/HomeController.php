<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers;

use App\Http\Controllers\Controller;
use Travalorics\Common\Repositories\ProductRepo;
use Travalorics\Front\Repositories\HomeRepo;

class HomeController extends Controller
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public function index(): mixed
    {
        $bestSeller  = ProductRepo::getInstance()->getBestSellerProducts();
        $newArrivals = ProductRepo::getInstance()->getLatestProducts();
        $tabProducts = [
            ['tab_title' => trans('front/home.bestseller'), 'products' => $bestSeller, 'is_bestseller' => true],
            ['tab_title' => trans('front/home.new_arrival'), 'products' => $newArrivals, 'is_bestseller' => false],
        ];

        $data = [
            'slideshow'    => HomeRepo::getInstance()->getSlideShow(),
            'tab_products' => $tabProducts,
        ];

        $data = fire_hook_filter('home.index.data', $data);

        return travalorics_view('home', $data);
    }
}
