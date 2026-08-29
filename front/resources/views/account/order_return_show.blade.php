@extends('layouts.app')
@section('body-class', 'page-order')
@section('content')
  <x-front-breadcrumb type="order_return" :value="$order_return" />
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

      .return-detail-table td {
        border-bottom: 1px dashed #f1f1f1;
      }

      .return-detail-table tr:last-child td {
        border-bottom: none;
      }

      .history-table th {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .history-table td {
        vertical-align: middle;
        padding: 16px 8px;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
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
            <a href="{{ account_route('order_returns.index') }}"
              class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
              <i class="bi bi-arrow-left me-1"></i> {{ __('common/base.back') }}
            </a>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-10">
              <table class="table table-borderless return-detail-table bg-light rounded-4 p-4 d-block mt-3 mb-5">
                <tbody class="d-block p-3 w-100">
                  <tr>
                    <td class="label fw-bold text-muted py-3" style="width: 250px;">{{ __('front/return.number') }}</td>
                    <td class="value py-3"><span class="fw-bold text-primary fs-5">{{ $order_return->number }}</span></td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.order_number') }}</td>
                    <td class="value py-3"><code
                        class="fs-6 text-dark bg-white px-3 py-1 rounded shadow-sm border">{{ $order_return->order_number }}</code>
                    </td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.product_name') }}</td>
                    <td class="value py-3 fw-bold text-dark">{{ $order_return->product_name }}</td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.product_id') }}</td>
                    <td class="value py-3">{{ $order_return->product_id }}</td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.quantity') }}</td>
                    <td class="value py-3"><span
                        class="badge bg-secondary px-3 py-2 rounded-pill">{{ $order_return->quantity }}</span></td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.opened') }}</td>
                    <td class="value py-3">
                      @if($order_return->opened)
                        <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm"><i
                            class="bi bi-box-seam me-1"></i>{{ __('front/common.yes') }}</span>
                      @else
                        <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm"><i
                            class="bi bi-box me-1"></i>{{ __('front/common.no') }}</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.status') }}</td>
                    <td class="value py-3"><span
                        class="badge bg-info px-3 py-2 rounded-pill shadow-sm fs-6">{{ $order_return->status_format }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="label fw-bold text-muted py-3">{{ __('front/return.created_at') }}</td>
                    <td class="value py-3 text-muted">{{ $order_return->updated_at }}</td>
                  </tr>
                  @if($order_return->comment)
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/return.comment') }}</td>
                      <td class="value py-3 text-muted">{{ $order_return->comment }}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div class="table-responsive mt-2">
            <h5 class="fw-bold mb-4">{{ __('panel/order.history') }}</h5>
            <table class="table table-borderless history-table">
              <thead>
                <tr>
                  <th>{{ __('front/order.order_status') }}</th>
                  <th>{{ __('front/order.remark') }}</th>
                  <th>{{ __('front/order.order_date') }}</th>
                </tr>
              </thead>
              @if($histories->count())
                <tbody>
                  @foreach($histories as $history)
                    <tr>
                      <td><span
                          class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $history->status_format }}</span>
                      </td>
                      <td class="text-muted">{{ $history->comment }}</td>
                      <td class="text-muted small">{{ $history->created_at }}</td>
                    </tr>
                  @endforeach
                </tbody>
              @else
                <tbody>
                  <tr>
                    <td colspan="3" class="text-center py-4 text-muted">No history found.</td>
                  </tr>
                </tbody>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>
    @hookinsert('account.order_return_create.bottom')
@endsection
