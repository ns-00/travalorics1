<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\PanelApiControllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Travalorics\Common\Repositories\CategoryRepo;
use Travalorics\Common\Resources\CategoryName;
use Travalorics\Common\Resources\CategorySimple;
use Travalorics\RestAPI\FrontApiControllers\BaseController;

class CategoryController extends BaseController
{
    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->all();
        $perPage = $request->get('per_page', 15);

        $categories = CategoryRepo::getInstance()->builder($filters)->paginate($perPage);

        return CategorySimple::collection($categories);
    }

    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function names(Request $request): AnonymousResourceCollection
    {
        $products = CategoryRepo::getInstance()->getListByCategoryIDs($request->get('ids'));

        return CategoryName::collection($products);
    }

    /**
     * Fuzzy search for auto complete.
     * /api/panel/categories/autocomplete?keyword=xxx
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function autocomplete(Request $request): AnonymousResourceCollection
    {
        $categories = CategoryRepo::getInstance()->autocomplete($request->get('keyword') ?? '');

        return CategoryName::collection($categories);
    }
}
