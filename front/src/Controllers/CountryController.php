<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Travalorics\Common\Models\Country;
use Travalorics\Common\Repositories\CountryRepo;
use Travalorics\Common\Repositories\StateRepo;
use Travalorics\Common\Resources\CountrySimple;
use Travalorics\Panel\Controllers\BaseController;

class CountryController extends BaseController
{
    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $countries = CountryRepo::getInstance()->getCountries($request->all());

        return CountrySimple::collection($countries);
    }

    /**
     * @param  string  $code
     * @return mixed
     */
    public function show(string $code): mixed
    {
        $country = Country::query()->where('code', $code)->orWhere('id', $code)->first();
        if (empty($country)) {
            return collect();
        }

        $filters = [
            'country_id' => $country->id,
        ];
        $countries = StateRepo::getInstance()->builder($filters)->get();

        return CountrySimple::collection($countries);
    }
}
