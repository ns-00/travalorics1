<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\FrontApiControllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Travalorics\Common\Models\Country;
use Travalorics\Common\Repositories\CountryRepo;
use Travalorics\Common\Repositories\StateRepo;
use Travalorics\Common\Resources\CountrySimple;
use Travalorics\Common\Resources\StateItem;

class CountryController extends BaseController
{
    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $countries = CountryRepo::getInstance()->builder($request->all())->get();

        return CountrySimple::collection($countries);
    }

    /**
     * @param  Country  $country
     * @return AnonymousResourceCollection
     */
    public function states(Country $country): AnonymousResourceCollection
    {
        $filters = [
            'country_id' => $country->id,
        ];
        $states = StateRepo::getInstance()->builder($filters)->get();

        return StateItem::collection($states);
    }
}
