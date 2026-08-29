@extends('panel::layouts.app')
@section('title', __('panel/coupon.coupons'))

@section('page-title-right')
<a href="{{ panel_route('coupons.create') }}" class="btn btn-primary">
  <i class="bi bi-plus-square"></i> {{ __('panel/coupon.create_coupon') }}
</a>
@endsection

@section('content')
  <div class="premium-dashboard-card p-0 mb-4">
    <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center bg-transparent pt-4 pb-0 px-4">
      <h5 class="mb-0 fw-bold"><i class="bi bi-tags text-primary me-2"></i>{{ __('panel/coupon.list') }}</h5>
    </div>
    <div class="card-body px-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">{{ __('panel/coupon.code') }}</th>
              <th>{{ __('panel/coupon.type') }}</th>
              <th>{{ __('panel/coupon.min_amount') }}</th>
              <th>{{ __('panel/coupon.usage_limit') }}</th>
              <th>{{ __('panel/coupon.validity') }}</th>
              <th>{{ __('panel/common.status') }}</th>
              <th class="text-end pe-4">{{ __('panel/common.actions') }}</th>
            </tr>
          </thead>
          <tbody>
          @foreach($coupons as $coupon)
            <tr>
              <td class="ps-4"><span class="badge bg-primary text-uppercase">{{ $coupon->code }}</span></td>
              <td>
                <div class="fw-bold">{{ $coupon->type === 'percentage' ? $coupon->value . '%' : currency_format($coupon->value) }}</div>
                <small class="text-muted">{{ $coupon->type === 'percentage' ? __('panel/coupon.percentage') : __('panel/coupon.fixed') }}</small>
              </td>
              <td>{{ currency_format($coupon->min_order_amount) }}</td>
              <td>
                <span class="text-dark">{{ $coupon->usage_count }}</span> / 
                <span class="text-muted">{{ $coupon->usage_limit ?: __('panel/coupon.unlimited') }}</span>
              </td>
              <td>
                <small class="text-muted d-block">{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d H:i') : __('panel/coupon.anytime') }} -</small>
                <small class="text-muted">{{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d H:i') : __('panel/coupon.no_expiry') }}</small>
              </td>
              <td>
                <span class="badge bg-{{ $coupon->active ? 'success' : 'danger' }}">{{ $coupon->active ? __('panel/coupon.active') : __('panel/coupon.inactive') }}</span>
              </td>
              <td class="text-end pe-4">
                <a href="{{ panel_route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-light">{{ __('panel/common.edit') }}</a>
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ panel_route('coupons.destroy', $coupon->id) }}">{{ __('panel/common.delete') }}</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      </div>
    </div>
    @if ($coupons->hasPages())
      <div class="card-footer bg-transparent p-4">
        {{ $coupons->withQueryString()->links() }}
      </div>
    @endif
  </div>
@endsection
