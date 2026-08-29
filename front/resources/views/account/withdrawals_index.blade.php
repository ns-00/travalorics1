@extends('layouts.app')
@section('body-class', 'page-wallet')
@section('content')
  <x-front-breadcrumb type="route" value="account.wallet.withdrawals.index" title="{{ __('front/withdrawal.my_withdrawals') }}"/>
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
      .btn-gradient-primary {
          background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
          border: none;
          color: #fff;
          border-radius: 30px;
          padding: 8px 20px;
          font-weight: 600;
          transition: all 0.3s ease;
      }
      .btn-gradient-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(27, 77, 62, 0.4);
          color: #fff;
      }
      .withdrawal-table-box th {
          text-transform: uppercase;
          font-size: 0.8rem;
          letter-spacing: 1px;
          color: #adb5bd;
          border-bottom: 2px solid #f8f9fa;
          padding-bottom: 12px;
      }
      .withdrawal-table-box td {
          vertical-align: middle;
          padding: 16px 8px;
          border-bottom: 1px solid #f8f9fa;
          color: #495057;
          font-weight: 500;
      }
    </style>
  @endpush
  @hookinsert('account.withdrawals_index.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card withdrawal-card-box">
          <div class="withdrawal-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >{{ __('front/withdrawal.withdrawal_history') }}</h4>
            <a href="{{ account_route('wallet.withdrawals.create') }}" class="btn btn-gradient-primary shadow-sm">
              <i class="bi bi-plus-circle me-1"></i> {{ __('front/withdrawal.apply_withdrawal') }}
            </a>
          </div>
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mb-4 rounded-4 shadow-sm border-0"/>
          @endif
          @if (session('error'))
            <x-common-alert type="danger" msg="{{ session('error') }}" class="mb-4 rounded-4 shadow-sm border-0"/>
          @endif
          @if ($withdrawals->count())
            <div class="table-responsive">
              <table class="table align-middle withdrawal-table-box table-borderless">
                <thead>
                <tr>
                  <th class="text-center">{{ __('front/withdrawal.amount') }}</th>
                  <th class="text-center">{{ __('front/withdrawal.account_type') }}</th>
                  <th class="text-center">{{ __('front/withdrawal.account_number') }}</th>
                  <th class="text-center">{{ __('front/withdrawal.status') }}</th>
                  <th class="text-center">{{ __('front/withdrawal.created_at') }}</th>
                  <th class="text-center">{{ __('front/common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($withdrawals as $withdrawal)
                  <tr>
                    <td class="text-center">
                      <span class="fw-bold text-primary fs-5">{{ currency_format($withdrawal->amount) }}</span>
                    </td>
                    <td class="text-center"><span class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $withdrawal->account_type_format }}</span></td>
                    <td class="text-center">
                      <span class="text-muted fw-bold">{{ substr($withdrawal->account_number, 0, 6) }}****{{ substr($withdrawal->account_number, -4) }}</span>
                    </td>
                    <td class="text-center">
                      @switch($withdrawal->status)
                        @case('pending')
                          <span class="badge bg-warning px-3 py-2 rounded-pill shadow-sm">{{ $withdrawal->status_format }}</span>
                          @break
                        @case('approved')
                          <span class="badge bg-info px-3 py-2 rounded-pill shadow-sm">{{ $withdrawal->status_format }}</span>
                          @break
                        @case('paid')
                          <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">{{ $withdrawal->status_format }}</span>
                          @break
                        @case('rejected')
                          <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">{{ $withdrawal->status_format }}</span>
                          @break
                        @default
                          <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $withdrawal->status_format }}</span>
                      @endswitch
                    </td>
                    <td class="text-center text-muted small">{{ $withdrawal->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-center">
                      <a href="{{ account_route('wallet.withdrawals.show', $withdrawal->id) }}" 
                         class="btn btn-light btn-sm rounded-circle shadow-sm border text-primary p-2">
                        <i class="bi bi-eye"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-4">
                {{ $withdrawals->withQueryString()->links('panel::vendor/pagination/bootstrap-4') }}
            </div>
          @else
            <div class="text-center py-5">
                <i class="bi bi-wallet2 text-muted display-1 opacity-25 mb-3 d-block"></i>
                <p class="text-muted fw-bold">{{ __('front/withdrawal.no_withdrawals') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.withdrawals_index.bottom')
@endsection
