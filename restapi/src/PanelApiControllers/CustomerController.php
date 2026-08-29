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
use Throwable;
use Travalorics\Common\Models\Customer;
use Travalorics\Common\Repositories\CustomerRepo;
use Travalorics\Common\Resources\CustomerSimple;
use Travalorics\Panel\Requests\CustomerRequest;

class CustomerController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();
        if (isset($filters['customer_ids'])) {
            $customerIds             = explode(',', $filters['customer_ids']);
            $filters['customer_ids'] = $customerIds;
        }

        $customers = CustomerRepo::getInstance()->builder($filters)->limit(10)->get();

        return CustomerSimple::collection($customers);
    }

    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function names(Request $request): AnonymousResourceCollection
    {
        $customers = CustomerRepo::getInstance()->getListByCustomerIDs($request->get('ids'));

        return CustomerSimple::collection($customers);
    }

    /**
     * @param  CustomerRequest  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(CustomerRequest $request): mixed
    {
        try {
            $data     = $request->all();
            $customer = CustomerRepo::getInstance()->create($data);

            return json_success(panel_trans('common.updated_success'), $customer);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  CustomerRequest  $request
     * @param  Customer  $customer
     * @return mixed
     */
    public function update(CustomerRequest $request, Customer $customer): mixed
    {
        try {
            $data = $request->all();
            CustomerRepo::getInstance()->update($customer, $data);

            return json_success(panel_trans('common.updated_success'), $customer);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  Customer  $customer
     * @return mixed
     */
    public function destroy(Customer $customer): mixed
    {
        try {
            CustomerRepo::getInstance()->destroy($customer);

            return json_success(panel_trans('common.deleted_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Fuzzy search for auto complete.
     * /api/panel/customers/autocomplete?keyword=xxx
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function autocomplete(Request $request): AnonymousResourceCollection
    {
        $keyword   = $request->get('keyword');
        $customers = CustomerRepo::getInstance()->autocomplete($keyword);

        return CustomerSimple::collection($customers);
    }
}
