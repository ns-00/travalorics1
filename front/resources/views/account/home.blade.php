@extends('layouts.app')
@section('body-class', 'page-account')
@section('content')
  <x-front-breadcrumb type="route" value="account.index" title="{{ __('front/account.account') }}" />
  @push('header')
    <style>
      .premium-sidebar-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border-radius: 20px;
        padding: 24px;
      }

      .account-links a {
        color: #495057;
        transition: all 0.3s ease;
      }

      .account-links li.active a {
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(27, 77, 62, 0.2);
      }

      .account-links li:not(.active) a:hover {
        background: #f8f9fa;
        color: var(--primary) !important;
        transform: translateX(4px);
      }

      [dir="rtl"] .account-links li:not(.active) a:hover {
        transform: translateX(-4px);
      }

      .premium-dashboard-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        padding: 30px;
        margin-bottom: 24px;
      }

      .stat-box {
        background: #fdfdfd;
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        border: 1px solid #f1f1f1;
        transition: transform 0.2s, box-shadow 0.2s;
      }

      .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        border-color: var(--primary);
      }

      .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 12px;
      }

      .stat-title {
        color: #6c757d;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        font-weight: 500;
      }

      @media (max-width: 576px) {
        .stat-box {
          padding: 15px 10px;
        }
        .stat-value {
          font-size: 1.5rem;
        }
        .stat-title {
          font-size: 0.8rem;
        }
        .premium-dashboard-card {
          padding: 20px 15px;
        }
      }
    </style>
  @endpush
  @hookinsert('account.home.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card account-info">
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <span class="fw-bold fs-4 text-dark"
              >{{ __('front/account.hello') }},
              {{ $customer->name }}</span>
            @hookupdate('account.home.edit_profile')
            <a href="{{ account_route('edit.index') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3">
              <i class="bi bi-pen me-1"></i>{{ __('front/account.edit') }}
            </a>
            @endhookupdate
          </div>
          @hookinsert('account.home.info.after')

          <div class="account-data mb-5">
            <div class="row g-4">
              <div class="col-6 col-md-4">
                <div class="stat-box h-100">
                  <div class="stat-value">{{ $order_total }}</div>
                  <div class="stat-title">{{ __('front/account.orders') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-4">
                <div class="stat-box h-100">
                  <div class="stat-value text-danger">{{ $fav_total }}</div>
                  <div class="stat-title">{{ __('front/account.favorites') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-4">
                <div class="stat-box h-100">
                  <div class="stat-value text-info">{{ $address_total }}</div>
                  <div class="stat-title">{{ __('front/account.addresses') }}</div>
                </div>
              </div>
            </div>
          </div>
          @hookinsert('account.home.analysis.after')
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4">
            <span class="fw-bold fs-5 text-dark">{{ __('front/account.orders') }}</span>
            <a href="{{ account_route('orders.index') }}" class="text-primary fw-bold text-decoration-none">
              {{ __('front/account.view_all') }} <i class="bi bi-arrow-right-short align-middle fs-5"></i>
            </a>
          </div>
          @if($latest_orders->count())
            <div class="table-responsive">
              <table class="table account-table-box table-borderless">
                <thead>
                  <tr>
                    <th>{{ __('front/order.order_number') }}</th>
                    <th>{{ __('front/order.order_date') }}</th>
                    <th>{{ __('front/order.order_billing') }}</th>
                    <th>{{ __('front/order.order_status') }}</th>
                    <th>{{ __('front/order.order_total') }}</th>
                    <th class="text-end">{{ __('front/common.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($latest_orders as $order)
                    <tr>
                      <td data-title="{{ __('front/order.order_number') }}" class="text-dark fw-bold">#{{ $order->number }}</td>
                      <td data-title="{{ __('front/order.order_date') }}" class="text-muted">{{ $order->created_at->format('Y-m-d') }}</td>
                      <td data-title="{{ __('front/checkout.billing_methods') }}">{{ $order->billing_method_name }}</td>
                      <td data-title="{{ __('front/order.order_status') }}">
                        <span class="badge rounded-pill bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }} px-3 py-2 fw-bold">
                          {{ $order->status_format }}
                        </span>
                      </td>
                      <td data-title="{{ __('front/order.order_total') }}" class="fw-bold" style="color: var(--primary);">{{ $order->total }}</td>
                      <td data-title="{{ __('front/common.action') }}" class="text-end">
                        <a href="{{ account_route('orders.number_show', $order->number) }}"
                          class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-dark border shadow-sm hover-bg-white"
                          role="button">
                          {{ __('front/common.view') }}
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="no-order alert alert-light border-0 shadow-sm text-center py-5 rounded-4 mt-4">
              <i class="bi bi-bag-x display-4 text-muted mb-3 d-block opacity-50"></i>
              <h5 class="fw-bold text-dark mb-3">{{ __('front/account.no_order') }}</h5>
              <a href="{{ front_route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold mt-2">
                {{ __('front/cart.continue') }}
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.home.bottom')
@endsection
