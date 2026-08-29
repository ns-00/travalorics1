@extends('layouts.app')
@section('body-class', 'page-wallet')
@section('content')
  <x-front-breadcrumb type="route" value="account.wallet.index" title="{{ __('front/account.wallet') }}" />
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

      .stat-box {
        background: #fdfdfd;
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        border: 1px solid #f1f1f1;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
      }

      .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        border-color: var(--primary);
      }

      .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
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

      .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        border: none;
        color: #fff;
        border-radius: 30px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(27, 77, 62, 0.4);
        color: #fff;
      }

      .wallet-table-box th {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .wallet-table-box td {
        vertical-align: middle;
        padding: 16px 8px;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
        font-weight: 500;
      }
    </style>
  @endpush
  @hookinsert('account.wallet_index.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">

        <!-- Balance Overview -->
        <div class="premium-dashboard-card wallet-balance">
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/account.balance_overview') }}</h4>
          </div>
          <div class="wallet-balance-data mb-4">
            <div class="row g-4">
              <div class="col-12 col-md-4">
                <div class="stat-box">
                  <div class="stat-value text-primary">{{ currency_format($balance) }}</div>
                  <div class="stat-title">{{ __('front/transaction.total') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-4">
                <div class="stat-box">
                  <div class="stat-value text-warning">{{ currency_format($freeze_balance) }}</div>
                  <div class="stat-title">{{ __('front/transaction.frozen') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-4">
                <div class="stat-box">
                  <div class="stat-value text-success">{{ currency_format($available_balance) }}</div>
                  <div class="stat-title">{{ __('front/transaction.available') }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="wallet-actions text-center text-md-start">
            <a href="{{ account_route('wallet.withdrawals.create') }}"
              class="btn btn-gradient-primary shadow-sm {{ $has_pending_withdrawal ? 'disabled opacity-50' : '' }}">
              <i class="bi bi-cash-coin me-1"></i> {{ __('front/withdrawal.apply_withdrawal') }}
            </a>
            @if($has_pending_withdrawal)
              <div class="text-warning fw-bold mt-2"><i
                  class="bi bi-info-circle me-1"></i>{{ __('front/withdrawal.has_pending_withdrawal') }}</div>
            @endif
          </div>
        </div>
        <!-- Withdrawal Statistics -->
        <div class="premium-dashboard-card wallet-withdrawals mt-4">
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h5 class="fw-bold mb-0 text-dark">{{ __('front/withdrawal.withdrawal_info') }}</h5>
            <a href="{{ account_route('wallet.withdrawals.index') }}" class="text-primary fw-bold text-decoration-none">
              {{ __('front/account.view_all') }} <i class="bi bi-arrow-right-short align-middle fs-5"></i>
            </a>
          </div>
          <div class="wallet-withdrawal-stats">
            <div class="row g-3">
              <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 text-center border">
                  <div class="fs-3 fw-bold text-warning mb-1">{{ $withdrawal_stats['pending'] }}</div>
                  <div class="text-muted small text-uppercase fw-bold">{{ __('front/withdrawal.pending') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 text-center border">
                  <div class="fs-3 fw-bold text-info mb-1">{{ $withdrawal_stats['approved'] }}</div>
                  <div class="text-muted small text-uppercase fw-bold">{{ __('front/withdrawal.approved') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 text-center border">
                  <div class="fs-3 fw-bold text-success mb-1">{{ $withdrawal_stats['paid'] }}</div>
                  <div class="text-muted small text-uppercase fw-bold">{{ __('front/withdrawal.paid') }}</div>
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded-3 text-center border">
                  <div class="fs-3 fw-bold text-danger mb-1">{{ $withdrawal_stats['rejected'] }}</div>
                  <div class="text-muted small text-uppercase fw-bold">{{ __('front/withdrawal.rejected') }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Recent Transactions -->
        <div class="premium-dashboard-card wallet-transactions mt-4">
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h5 class="fw-bold mb-0 text-dark">{{ __('front/account.transactions') }}</h5>
            <a href="{{ account_route('wallet.transactions.index') }}" class="text-primary fw-bold text-decoration-none">
              {{ __('front/account.view_all') }} <i class="bi bi-arrow-right-short align-middle fs-5"></i>
            </a>
          </div>
          @if ($recent_transactions->count())
            <div class="table-responsive">
              <table class="table align-middle wallet-table-box table-borderless">
                <thead>
                  <tr>
                    <th class="text-center">{{ __('front/transaction.type') }}</th>
                    <th class="text-center">{{ __('front/transaction.amount') }}</th>
                    <th class="text-center">{{ __('front/transaction.comment') }}</th>
                    <th class="text-center">{{ __('front/common.date') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recent_transactions as $transaction)
                    <tr>
                      <td class="text-center">
                        <span
                          class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $transaction->type_format }}</span>
                      </td>
                      <td class="text-center fw-bold fs-5 {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->amount > 0 ? '+' : '' }}{{ currency_format($transaction->amount) }}
                      </td>
                      <td class="text-center text-muted">{{ $transaction->comment }}</td>
                      <td class="text-center text-muted small">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="bi bi-clock-history text-muted display-4 opacity-25 mb-3 d-block"></i>
              <p class="text-muted fw-bold">{{ __('front/transaction.no_transactions') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.wallet_index.bottom')
@endsection
