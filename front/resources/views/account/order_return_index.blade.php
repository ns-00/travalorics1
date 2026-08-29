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
        padding: 30px;
        margin-bottom: 24px;
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
        font-weight: 500;
      }
    </style>
  @endpush
  @hookinsert('account.order_return_index.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card order-box">
          <div class="account-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/account.order_returns') }}</h4>
          </div>
          @if ($order_returns->count())
            <div class="table-responsive">
              <table class="table return-table-box table-borderless">
                <thead>
                  <tr>
                    <th>{{ __('front/return.number') }}</th>
                    <th>{{ __('front/order.order_number') }}</th>
                    <th>{{ __('front/return.product_name') }}</th>
                    <th class="text-center">{{ __('front/return.quantity') }}</th>
                    <th>{{ __('front/common.created_at') }}</th>
                    <th class="text-center">{{ __('front/common.status') }}</th>
                    <th class="text-end">{{ __('front/common.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order_returns as $item)
                    <tr>
                      <td class="align-middle fw-bold text-primary">{{ $item->number }}</td>
                      <td class="align-middle"><span
                          class="badge bg-light text-dark border px-2 py-1 rounded">{{ $item->order_number }}</span></td>
                      <td class="align-middle fw-bold text-dark">{{ sub_string($item->product_name, 25) }}</td>
                      <td class="align-middle text-center"><span
                          class="badge bg-secondary rounded-pill px-3 py-2">{{ $item->quantity }}</span></td>
                      <td class="align-middle text-muted small">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                      <td class="align-middle text-center">
                        <span class="badge bg-info px-3 py-2 rounded-pill shadow-sm">{{ $item->status_format }}</span>
                      </td>
                      <td class="align-middle text-end">
                        <a href="{{ account_route('order_returns.show', ['order_return' => $item->id]) }}"
                          class="btn btn-light btn-sm rounded-circle shadow-sm border text-primary p-2"
                          title="{{ __('front/common.view') }}">
                          <i class="bi bi-eye"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5">
              <i class="bi bi-arrow-return-left text-muted display-1 opacity-25 mb-3 d-block"></i>
              <p class="text-muted fw-bold">{{ __('front/account.no_order') ?? 'No returned orders found.' }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.order_return_index.bottom')
@endsection
