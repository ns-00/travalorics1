<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\Country;
use Travalorics\Common\Repositories\CountryRepo;
use Travalorics\Panel\Requests\CountryRequest;

class CountryController extends BaseController
{
    protected string $modelClass = Country::class;

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();
        $data    = [
            'criteria'  => CountryRepo::getCriteria(),
            'countries' => CountryRepo::getInstance()->list($filters),
        ];

        return travalorics_view('panel::countries.index', $data);
    }

    /**
     * @param  Country  $country
     * @return Country
     */
    public function show(Country $country): Country
    {
        return $country;
    }

    /**
     * Country creation page.
     *
     * @return mixed
     * @throws Exception
     */
    public function create(): mixed
    {
        return $this->form(new Country);
    }

    /**
     * @param  CountryRequest  $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(CountryRequest $request): RedirectResponse
    {
        try {
            $data    = $request->all();
            $country = CountryRepo::getInstance()->create($data);

            return redirect(panel_route('countries.index'))
                ->with('instance', $country)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect(panel_route('countries.index'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Country  $country
     * @return mixed
     * @throws Exception
     */
    public function edit(Country $country): mixed
    {
        return $this->form($country);
    }

    /**
     * @param  $country
     * @return mixed
     * @throws Exception
     */
    public function form($country): mixed
    {
        $data = [
            'country' => $country,
        ];

        return travalorics_view('panel::countries.form', $data);
    }

    /**
     * @param  CountryRequest  $request
     * @param  Country  $country
     * @return JsonResponse
     */
    public function update(CountryRequest $request, Country $country): JsonResponse
    {
        try {
            $data = $request->all();
            CountryRepo::getInstance()->update($country, $data);

            return update_json_success($country);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  Country  $country
     * @return RedirectResponse
     */
    public function destroy(Country $country): RedirectResponse
    {
        try {
            CountryRepo::getInstance()->destroy($country);

            return redirect(panel_route('countries.index'))
                ->with('success', panel_trans('common.deleted_success'));
        } catch (Exception $e) {
            return redirect(panel_route('countries.index'))
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
