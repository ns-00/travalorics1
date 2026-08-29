@extends('panel::layouts.app')
@section('title', $coupon->id ? __('panel/coupon.edit_coupon') : __('panel/coupon.create_coupon'))

<x-panel::form.right-btns />

@section('content')
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="premium-dashboard-card p-0 mb-4">
        <div class="card-header border-bottom-0 bg-transparent pt-4 px-4">
          <h5 class="mb-0 fw-bold">{{ $coupon->id ? __('panel/coupon.edit_coupon') : __('panel/coupon.create_coupon') }}</h5>
        </div>
        <div class="card-body p-4">
          <form class="needs-validation" novalidate id="app-form" action="{{ $coupon->id ? panel_route('coupons.update', $coupon->id) : panel_route('coupons.store') }}" method="POST">
            @csrf
            @method($coupon->id ? 'PUT' : 'POST')
            
            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.code') }} <span class="text-danger">*</span></label>
              <div class="col-sm-9">
                <input type="text" class="form-control text-uppercase" name="code" value="{{ old('code', $coupon->code) }}" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.type') }}</label>
              <div class="col-sm-9">
                <select class="form-select" name="type">
                  <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>{{ __('panel/coupon.percentage') }}</option>
                  <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>{{ __('panel/coupon.fixed') }}</option>
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.value') }} <span class="text-danger">*</span></label>
              <div class="col-sm-9">
                <input type="number" step="0.01" class="form-control" name="value" value="{{ old('value', $coupon->value) }}" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.min_amount') }}</label>
              <div class="col-sm-9">
                <input type="number" step="0.01" class="form-control" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                <small class="text-muted">{{ __('panel/coupon.zero_no_minimum') }}</small>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.usage_limit') }}</label>
              <div class="col-sm-9">
                <input type="number" class="form-control" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                <small class="text-muted">{{ __('panel/coupon.zero_unlimited') }}</small>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.valid_from') }}</label>
              <div class="col-sm-9">
                <input type="datetime-local" class="form-control" name="valid_from" value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d\TH:i') : '') }}">
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.valid_to') }}</label>
              <div class="col-sm-9">
                <input type="datetime-local" class="form-control" name="valid_to" value="{{ old('valid_to', $coupon->valid_to ? $coupon->valid_to->format('Y-m-d\TH:i') : '') }}">
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">{{ __('panel/coupon.active') }}</label>
              <div class="col-sm-9 pt-2">
                <x-common-form-switch-radio 
                    :title="''"
                    name="active"
                    :value="old('active', $coupon->active ?? true)"
                />
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
