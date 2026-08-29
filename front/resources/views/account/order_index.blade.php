@extends('layouts.app')
@section('body-class', 'page-order')
@section('content')
  <x-front-breadcrumb type="route" value="account.orders.index" title="{{ __('front/account.orders') }}" />
  @push('header')
    <style>
      .premium-dashboard-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        padding: 30px;
        margin-bottom: 24px;
      }

      .nav-pills-custom .nav-link {
        color: #6c757d;
        font-weight: 600;
        border-radius: 30px;
        padding: 8px 20px;
        margin-right: 8px;
        margin-bottom: 8px;
        transition: all 0.3s;
        background: #f8f9fa;
        border: 1px solid transparent;
      }

      .nav-pills-custom .nav-link:hover {
        border-color: #dee2e6;
      }

      .nav-pills-custom .nav-link.active {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.3);
        border-color: var(--primary);
      }

      .search-form .form-control {
        border-radius: 30px;
        padding: 12px 20px;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
      }

      .search-form .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
        background: #fff;
      }

      .search-form .btn-primary {
        border-radius: 30px;
        padding: 12px 24px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        border: none;
      }

      .account-table-box th {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .account-table-box td {
        vertical-align: middle;
        padding: 16px 8px;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
      }

      .product-thumb-wrap {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #eee;
        margin-right: -10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 1;
        transition: transform 0.2s;
      }

      [dir="rtl"] .product-thumb-wrap {
        margin-right: 0;
        margin-left: -10px;
      }

      .product-thumb-wrap:hover {
        transform: translateY(-2px);
        z-index: 2;
      }

      .product-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .child-order-row {
        background: #fbfbfc;
      }
    </style>
  @endpush
  @hookinsert('account.order_index.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card order-box">
          <div class="account-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/order.order') }}</h4>
          </div>
          <ul class="nav nav-pills nav-pills-custom mb-4 tabs-plus">
            <li class="nav-item">
              <a class="nav-link {{ request('status') == '' ? 'active' : '' }}"
                href="{{ account_route('orders.index') }}">{{ __('front/order.all') }}</a>
            </li>
            @foreach ($filter_statuses as $status)
              <li class="nav-item">
                <a class="nav-link {{ request('status') == $status ? 'active' : '' }}"
                  href="{{ account_route('orders.index', ['status' => $status]) }}">
                  {{ __('front/order.' . $status) }}</a>
              </li>
            @endforeach
          </ul>
          <form method="GET" action="{{ account_route('orders.index') }}" class="mb-4 d-flex search-form"
            style="max-width: 450px;">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="text" name="number" class="form-control me-2 ms-2 shadow-sm"
              placeholder="{{ __('front/order.order_number') }}" value="{{ request('number') }}">
            <button class="btn btn-primary text-nowrap shadow-sm" type="submit"><i
                class="bi bi-search me-1"></i>{{ __('front/common.search') }}</button>
          </form>
          @if ($orders->count())
            <div class="table-responsive">
              <table class="table account-table-box table-borderless">
                <thead>
                  <tr>
                    <th>{{ __('front/order.order_number') }}</th>
                    <th>{{ __('front/order.order_items') }}</th>
                    <th>{{ __('front/order.order_date') }}</th>
                    <th>{{ __('front/checkout.billing_methods') }}</th>
                    <th>{{ __('front/checkout.shipping_methods') }}</th>
                    <th>{{ __('front/order.order_status') }}</th>
                    <th>{{ __('front/order.order_total') }}</th>
                    <th class="text-end">{{ __('front/common.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orders as $order)
                    <tr>
                      <td data-title="{{ __('front/order.order_number') }}" class="fw-bold text-dark">
                        @if ($order->children->count())
                          <a class="btn btn-light btn-sm rounded-circle me-2 p-1" data-bs-toggle="collapse"
                            href="#collapse{{ $order->id }}" role="button">
                            <i class="bi bi-chevron-down"></i>
                          </a>
                        @endif
                        #{{ $order->number }}
                      </td>
                      <td data-title="{{ __('front/order.order_items') }}">
                        <div class="d-flex align-items-center">
                          @foreach ($order->items->take(5) as $product)
                            <div class="product-thumb-wrap">
                              <img src="{{ image_resize($product->image, 50, 50) }}" alt="{{ $product->name }}">
                            </div>
                          @endforeach
                          @if($order->items->count() > 5)
                            <div class="ms-3 text-muted small">+{{ $order->items->count() - 5 }}</div>
                          @endif
                        </div>
                      </td>
                      <td data-title="{{ __('front/order.order_date') }}" class="text-muted">{{ $order->created_at->format('Y-m-d') }}</td>
                      <td data-title="{{ __('front/checkout.billing_methods') }}"><span class="badge bg-light text-dark border">{{ $order->billing_method_name }}</span></td>
                      <td data-title="{{ __('front/checkout.shipping_methods') }}"><span class="badge bg-light text-dark border">{{ $order->shipping_method_name }}</span></td>
                      <td data-title="{{ __('front/order.order_status') }}">
                        <span
                          class="badge rounded-pill px-3 py-2 fw-bold bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }}">
                          {{ $order->status_format }}
                        </span>
                      </td>
                      <td data-title="{{ __('front/order.order_total') }}" class="fw-bold" style="color: var(--primary);">{{ $order->total_format }}</td>
                      <td data-title="{{ __('front/common.action') }}" class="text-end">
                        <a href="{{ account_route('orders.number_show', $order->number) }}"
                          class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-dark border shadow-sm hover-bg-white me-1"
                          role="button">{{ __('front/common.view') }}</a>
                        @if ($order->status == 'shipped')
                          <button data-number="{{ $order->number }}"
                            class="btn btn-success btn-sm rounded-pill px-3 fw-bold btn-shipped shadow-sm">{{ __('front/account.signed') }}</button>
                        @endif
                        @hookinsert('account.order_index.actions.after', $order)
                      </td>
                    </tr>
                    @if ($order->children->count())
                      <tr class="p-0 child-order-row">
                        <td colspan="8" class="p-0 border-0">
                          <div class="collapse" id="collapse{{ $order->id }}">
                            <div class="p-3 bg-light rounded-3 m-2">
                              <table class="table table-sm mb-0 table-borderless">
                                <thead class="text-muted small">
                                  <tr>
                                    <th>{{ __('front/order.order_number') }}</th>
                                    <th>{{ __('front/order.order_items') }}</th>
                                    <th>{{ __('front/order.order_date') }}</th>
                                    <th>{{ __('front/checkout.billing_methods') }}</th>
                                    <th>{{ __('front/checkout.shipping_methods') }}</th>
                                    <th>{{ __('front/order.order_status') }}</th>
                                    <th>{{ __('front/order.order_total') }}</th>
                                    <th class="text-end">{{ __('front/common.action') }}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($order->children as $child)
                                    <tr>
                                      <td data-title="{{ __('front/order.order_number') }}" class="fw-bold text-dark">#{{ $child->number }}</td>
                                      <td data-title="{{ __('front/order.order_items') }}">
                                        <div class="d-flex">
                                          @foreach ($child->items->take(5) as $product)
                                            <div class="product-thumb-wrap" style="width:35px; height:35px;">
                                              <img src="{{ image_resize($product->image, 40, 40) }}" alt="{{ $product->name }}">
                                            </div>
                                          @endforeach
                                        </div>
                                      </td>
                                      <td data-title="{{ __('front/order.order_date') }}" class="text-muted">{{ $child->created_at->format('Y-m-d') }}</td>
                                      <td data-title="{{ __('front/checkout.billing_methods') }}"><span class="badge bg-light text-dark border">{{ $child->billing_method_name }}</span></td>
                                      <td data-title="{{ __('front/checkout.shipping_methods') }}"><span class="badge bg-light text-dark border">{{ $child->shipping_method_name }}</span></td>
                                      <td data-title="{{ __('front/order.order_status') }}">
                                        <span
                                          class="badge rounded-pill bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }}">{{ $order->status_format }}</span>
                                      </td>
                                      <td data-title="{{ __('front/order.order_total') }}" class="fw-bold" style="color: var(--primary);">{{ $child->total_format }}</td>
                                      <td data-title="{{ __('front/common.action') }}" class="text-end">
                                        <a href="{{ account_route('orders.number_show', $child->number) }}"
                                          class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-dark border shadow-sm me-1"
                                          role="button">{{ __('front/common.view') }}</a>
                                        @if ($child->status == 'shipped')
                                          <button data-number="{{ $child->number }}"
                                            class="btn btn-success btn-sm rounded-pill px-3 fw-bold btn-shipped">{{ __('front/account.signed') }}</button>
                                        @endif
                                      </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-4">
              {{ $orders->links('panel::vendor/pagination/bootstrap-4') }}
            </div>
          @else
            <x-common-no-data />
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.order_index.bottom')
@endsection
@push('footer')
  <script>
    $(document).ready(function () {
      $('.btn-shipped').click(function () {
        var button = $(this);
        var number = $(this).data('number');
        axios.post(`${urls.api_base}/orders/${number}/complete`, {
          number: number
        }).then(function (response) {
          inno.msg(__('front/account.signed_success'));
          button.fadeOut(300, function () {
            $(this).remove();
          });
          window.location.reload();
        }).catch(function (error) {
          inno.msg(__('front/account.signed_failed'));
        });
      });
    });
  </script>
@endpush
