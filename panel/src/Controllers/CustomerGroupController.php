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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\Customer\Group as CustomerGroup;
use Travalorics\Common\Repositories\Customer;
use Travalorics\Panel\Requests\CustomerGroupRequest;

class CustomerGroupController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();
        $data    = [
            'criteria' => Customer\GroupRepo::getCriteria(),
            'groups'   => Customer\GroupRepo::getInstance()->list($filters),
        ];

        return travalorics_view('panel::customer_groups.index', $data);
    }

    /**
     * CustomerGroup creation page.
     *
     * @return mixed
     * @throws Exception
     */
    public function create(): mixed
    {
        return $this->form(new CustomerGroup);
    }

    /**
     * @param  CustomerGroupRequest  $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(CustomerGroupRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();

            $customerGroup = Customer\GroupRepo::getInstance()->create($data);

            return redirect(panel_route('customer_groups.index'))
                ->with('instance', $customerGroup)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect(panel_route('customer_groups.index'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  CustomerGroup  $customerGroup
     * @return mixed
     * @throws Exception
     */
    public function edit(CustomerGroup $customerGroup): mixed
    {
        return $this->form($customerGroup);
    }

    /**
     * @param  CustomerGroup  $customerGroup
     * @return mixed
     */
    public function form(CustomerGroup $customerGroup): mixed
    {
        $customerGroup->load(['translations']);
        $data = [
            'group' => $customerGroup,
        ];

        return travalorics_view('panel::customer_groups.form', $data);
    }

    /**
     * @param  CustomerGroupRequest  $request
     * @param  CustomerGroup  $customerGroup
     * @return RedirectResponse
     */
    public function update(CustomerGroupRequest $request, CustomerGroup $customerGroup): RedirectResponse
    {
        try {
            $data = $request->all();
            Customer\GroupRepo::getInstance()->update($customerGroup, $data);

            return redirect(panel_route('customer_groups.index'))
                ->with('instance', $customerGroup)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect(panel_route('customer_groups.index'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  CustomerGroup  $customerGroup
     * @return RedirectResponse
     */
    public function destroy(CustomerGroup $customerGroup): RedirectResponse
    {
        try {
            $customerGroup->translations()->delete();
            $customerGroup->delete();

            return redirect(panel_route('customer_groups.index'))
                ->with('success', panel_trans('common.deleted_success'));
        } catch (Exception $e) {
            return redirect(panel_route('customer_groups.index'))
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
