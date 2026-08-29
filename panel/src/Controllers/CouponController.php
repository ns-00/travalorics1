<?php

namespace Travalorics\Panel\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Travalorics\Common\Models\Coupon;
use Travalorics\Common\Repositories\CouponRepo;
use Travalorics\Panel\Requests\CouponRequest;

class CouponController extends BaseController
{
    public function index(Request $request)
    {
        $filters = $request->all();
        $coupons = CouponRepo::getInstance()->list($filters);
        return view('panel::coupons.index', compact('coupons', 'filters'));
    }

    public function create()
    {
        $coupon = new Coupon();
        return view('panel::coupons.form', compact('coupon'));
    }

    public function store(CouponRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        CouponRepo::getInstance()->create($data);
        return redirect(panel_route('coupons.index'))
            ->with('success', panel_trans('common.created_success') ?? __('panel/common.created_successfully'));
    }

    public function edit(Coupon $coupon)
    {
        return view('panel::coupons.form', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        CouponRepo::getInstance()->update($coupon, $data);
        return redirect(panel_route('coupons.index'))
            ->with('success', panel_trans('common.updated_success') ?? __('panel/common.updated_successfully'));
    }

    public function destroy(Coupon $coupon): RedirectResponse|JsonResponse
    {
        CouponRepo::getInstance()->destroy($coupon);
        return delete_json_success();
    }
}
