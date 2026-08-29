<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\PanelApiControllers;

use Illuminate\Http\Request;
use Travalorics\Common\Repositories\AttributeRepo;
use Travalorics\Common\Resources\AttributeSimple;

class AttributeController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        $filters    = $request->all();
        $attributes = AttributeRepo::getInstance()->all($filters);
        $items      = AttributeSimple::collection($attributes);

        return read_json_success($items);
    }
}
