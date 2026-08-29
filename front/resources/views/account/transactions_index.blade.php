@extends('layouts.app')
@section('body-class', 'page-wallet')
@section('content')
  <x-front-breadcrumb type="route" value="account.wallet.transactions.index"
    title="{{ __('front/account.transactions') }}" />
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

      .transaction-table-box th {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .transaction-table-box td {
        vertical-align: middle;
        padding: 16px 8px;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
        font-weight: 500;
      }
    </style>
  @endpush
  @hookinsert('account.transaction_index.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card transaction-card-box">
          <div class="account-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/transaction.transaction') }}</h4>
          </div>
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          @if (session('error'))
            <x-common-alert type="danger" msg="{{ session('error') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          @if ($transactions->count())
            <div class="table-responsive">
              <table class="table align-middle transaction-table-box table-borderless">
                <thead>
                  <tr>
                    <th class="text-center">{{ __('front/transaction.type') }}</th>
                    <th class="text-center">{{ __('front/transaction.amount') }}</th>
                    <th class="text-center">{{ __('front/transaction.comment') }}</th>
                    <th class="text-center">{{ __('front/common.date') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transactions as $transaction)
                    <tr>
                      <td class="text-center">
                        <span
                          class="badge bg-light text-dark border px-3 py-2 rounded-pill">{{ $transaction->type_format }}</span>
                      </td>
                      <td class="text-center fw-bold fs-5 {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->amount > 0 ? '+' : '' }}{{ currency_format($transaction->amount) }}
                      </td>
                      <td class="text-center text-muted">{{ $transaction->comment }}</td>
                      <td class="text-center text-muted small">{{ $transaction->created_at }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-4">
              {{ $transactions->withQueryString()->links('panel::vendor/pagination/bootstrap-4') }}
            </div>
          @else
            <div class="text-center py-5">
              <i class="bi bi-clock-history text-muted display-1 opacity-25 mb-3 d-block"></i>
              <p class="text-muted fw-bold">{{ __('front/transaction.no_transactions') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.transaction_index.bottom')
@endsection
