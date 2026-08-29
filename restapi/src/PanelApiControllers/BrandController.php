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
use Travalorics\Common\Repositories\BrandRepo;
use Travalorics\Common\Resources\BrandName;
use Travalorics\Common\Resources\BrandSimple;
use Travalorics\RestAPI\FrontApiControllers\BaseController;

class BrandController extends BaseController
{
    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->all();
        $perPage = $request->get('per_page', 15);

        $categories = BrandRepo::getInstance()->builder($filters)->paginate($perPage);

        return BrandSimple::collection($categories);
    }

    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function names(Request $request): AnonymousResourceCollection
    {
        $brands = BrandRepo::getInstance()->getListByBrandIDs($request->get('ids'));

        return BrandName::collection($brands);
    }

    /**
     * Fuzzy search for auto complete.
     * /api/panel/brands/autocomplete?keyword=xxx
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function autocomplete(Request $request): AnonymousResourceCollection
    {
        $categories = BrandRepo::getInstance()->autocomplete($request->get('keyword') ?? '');

        return BrandSimple::collection($categories);
    }
}
