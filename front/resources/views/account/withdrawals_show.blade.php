@extends('layouts.app')
@section('content')
  <x-front-breadcrumb type="route" value="account.wallet.withdrawals.index" title="{{ __('front/withdrawal.withdrawal_detail') }}"/>
  @push('header')
    <style>
      .premium-dashboard-card {
          background: #fff;
          border-radius: 20px;
          border: 1px solid rgba(0,0,0,0.05);
          box-shadow: 0 4px 15px rgba(0,0,0,0.03);
          padding: 30px;
          margin-bottom: 24px;
      }
      .withdrawal-detail-table td {
          border-bottom: 1px dashed #f1f1f1;
      }
      .withdrawal-detail-table tr:last-child td {
          border-bottom: none;
      }
    </style>
  @endpush
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card withdrawal-detail-box mb-4">
          <div class="withdrawal-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >{{ __('front/withdrawal.withdrawal_detail') }}</h4>
            <a href="{{ account_route('wallet.withdrawals.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
              <i class="bi bi-arrow-left me-1"></i> {{ __('common/base.back') }}
            </a>
          </div>
          <div class="withdrawal-info mt-3">
            <div class="row mb-4">
              <div class="col-12 text-center">
                <div class="status-badge mb-4 mt-2">
                  @switch($withdrawal->status)
                    @case('pending')
                      <span class="badge bg-warning fs-5 px-4 py-3 rounded-pill shadow-sm">
                        <i class="bi bi-clock me-1"></i> {{ $withdrawal->status_format }}
                      </span>
                      @break
                    @case('approved')
                      <span class="badge bg-info fs-5 px-4 py-3 rounded-pill shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> {{ $withdrawal->status_format }}
                      </span>
                      @break
                    @case('paid')
                      <span class="badge bg-success fs-5 px-4 py-3 rounded-pill shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ $withdrawal->status_format }}
                      </span>
                      @break
                    @case('rejected')
                      <span class="badge bg-danger fs-5 px-4 py-3 rounded-pill shadow-sm">
                        <i class="bi bi-x-circle me-1"></i> {{ $withdrawal->status_format }}
                      </span>
                      @break
                    @default
                      <span class="badge bg-secondary fs-5 px-4 py-3 rounded-pill">{{ $withdrawal->status_format }}</span>
                  @endswitch
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-md-10">
                <table class="table table-borderless withdrawal-detail-table mt-3 bg-light rounded-4 p-4 d-block">
                  <tbody class="d-block p-3">
                    <tr>
                      <td class="label fw-bold text-muted py-3" style="width: 250px;">{{ __('front/withdrawal.withdrawal_amount') }}</td>
                      <td class="value py-3">
                        <span class="fw-bold text-primary fs-4">{{ currency_format($withdrawal->amount) }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.account_type') }}</td>
                      <td class="value py-3"><span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">{{ $withdrawal->account_type_format }}</span></td>
                    </tr>
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.account_number') }}</td>
                      <td class="value py-3">
                        <code class="fs-5 text-dark bg-white px-3 py-1 rounded shadow-sm border">{{ $withdrawal->account_number }}</code>
                      </td>
                    </tr>
                    @if($withdrawal->bank_name)
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.bank_name') }}</td>
                      <td class="value py-3 fw-bold text-dark">{{ $withdrawal->bank_name }}</td>
                    </tr>
                    @endif
                    @if($withdrawal->bank_account)
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.bank_account') }}</td>
                      <td class="value py-3 fw-bold text-dark">{{ $withdrawal->bank_account }}</td>
                    </tr>
                    @endif
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.created_at') }}</td>
                      <td class="value py-3 text-muted">{{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.status') }}</td>
                      <td class="value py-3 fw-bold text-dark">{{ $withdrawal->status_format }}</td>
                    </tr>
                    @if($withdrawal->comment)
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.comment') }}</td>
                      <td class="value py-3 text-muted">{{ $withdrawal->comment }}</td>
                    </tr>
                    @endif
                    @if($withdrawal->admin_comment)
                    <tr>
                      <td class="label fw-bold text-muted py-3">{{ __('front/withdrawal.admin_comment') }}</td>
                      <td class="value py-3">
                        <div class="alert alert-info mb-0 shadow-sm border-0 rounded-3">
                          <i class="bi bi-info-circle-fill me-2"></i>
                          {{ $withdrawal->admin_comment }}
                        </div>
                      </td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
