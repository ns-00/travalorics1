@extends('layouts.app')
@section('body-class', 'page-order')
@section('content')
  <x-front-breadcrumb type="route" value="account.order_returns.index" title="{{ __('front/account.order_returns') }}" />
  @push('header')
    <style>
      .premium-dashboard-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        padding: 40px;
        margin-bottom: 24px;
      }

      .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        border: none;
        color: #fff;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(27, 77, 62, 0.4);
        color: #fff;
      }

      .edit-form .form-control,
      .edit-form .form-select {
        border-radius: 12px;
        padding: 12px 16px;
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
      }

      .edit-form .form-control:focus,
      .edit-form .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        background: #fff;
      }

      .return-table-box th {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .return-table-box td {
        vertical-align: middle;
        padding: 16px 8px;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
        font-weight: 600;
      }
    </style>
  @endpush
  @hookinsert('account.order_return_create.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card order-box">
          @if (session()->has('errors'))
            <x-common-alert type="danger" msg="{{ session('errors')->first() }}"
              class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/account.order_returns') }}</h4>
            <span class="fs-6 text-muted fw-bold">Order: <a href="{{ account_route('orders.number_show', $number) }}"
                class="text-primary text-decoration-none border px-2 py-1 rounded bg-light">{{ $number }}</a></span>
          </div>
          <div class="table-responsive mb-5">
            <table class="table table-borderless return-table-box mb-0">
              <thead>
                <tr>
                  <th>{{__('common/rma.purchase_commodity')}}</th>
                  <th class="text-center">{{__('common/rma.purchase_quantity')}}</th>
                  <th class="text-center">{{__('common/rma.returned_quantity')}}</th>
                  <th class="text-center">{{__('common/rma.returnable_quantity')}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                  <tr>
                    <td class="text-dark">{{ $item->name . ' - ' . $item->product_sku }}</td>
                    <td class="text-center"><span
                        class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $item->quantity }}</span></td>
                    <td class="text-center"><span
                        class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">{{ $item->returns->sum('quantity') }}</span>
                    </td>
                    <td class="text-center"><span
                        class="badge bg-success px-3 py-2 rounded-pill shadow-sm">{{ $item->quantity - ($item->returns->sum('quantity')) }}</span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @if($item->returns->sum('quantity') < $item->quantity)
            <h5 class="fw-bold mb-4 mt-2">Submit Return Request</h5>
            <form class="needs-validation edit-form" action="{{ account_route('order_returns.store') }}" method="POST"
              novalidate>
              @csrf
              <div class="row">
                <div class="col-12 col-lg-6 mb-4">
                  <x-common-form-select title="{{__('front/cart.product')}}" name="order_item_id" :options="$options"
                    key="key" label="label" :emptyOption="false" required placeholder="{{__('front/cart.product')}}" />
                </div>
                <div class="col-12 col-lg-6 mb-4">
                  <x-common-form-input name="quantity" title="{{__('front/return.quantity')}}"
                    value="{{ old('quantity', 1) }}" required="required"
                    placeholder="{{ __('front/return.return_number') }}" />
                </div>
                <div class="col-12 col-lg-12 mb-4">
                  <x-common-form-switch-radio name="opened" title="{{__('front/return.opened')}}"
                    value="{{ old('opened', 1) }}" required="required"
                    placeholder="{{ __('front/return.return_number') }}" />
                </div>
                <div class="col-12 mb-4">
                  <x-common-form-textarea name="comment" title="{{__('front/return.comment')}}"
                    value="{{ old('comment', '') }}" required="required" />
                </div>
              </div>
              <div class="text-end border-top pt-4">
                <button type="submit" class="btn btn-gradient-primary shadow-sm"><i
                    class="bi bi-arrow-return-left me-1"></i> {{ __('front/common.submit') }}</button>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.order_return_create.bottom')
@endsection
