@extends('layouts.app')
@section('body-class', 'page-wallet')
@section('content')
  <x-front-breadcrumb type="route" value="account.wallet.withdrawals.create"
    title="{{ __('front/withdrawal.apply_withdrawal') }}" />
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

      .wallet-balance-overview {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
      }

      .withdrawal-form .form-control,
      .withdrawal-form .form-select {
        border-radius: 12px;
        padding: 12px 16px;
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
      }

      .withdrawal-form .form-control:focus,
      .withdrawal-form .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        background: #fff;
      }

      .input-group-text {
        background: #f8f9fa;
        border-color: #e9ecef;
        font-weight: bold;
        color: var(--dark);
      }
    </style>
  @endpush
  @hookinsert('account.withdrawals_create.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card withdrawal-create-box">
          <div class="withdrawal-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/withdrawal.apply_withdrawal') }}</h4>
          </div>
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          @if (session('error'))
            <x-common-alert type="danger" msg="{{ session('error') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          <!-- Balance information -->
          <div class="wallet-balance-overview shadow-sm">
            <div class="balance-header d-flex align-items-center mb-3">
              <i class="bi bi-wallet2 fs-4 text-primary me-2"></i>
              <span class="fw-bold fs-5 text-dark">{{ __('front/withdrawal.wallet_balance') }}</span>
            </div>
            <div class="balance-content d-flex justify-content-between align-items-end flex-wrap gap-3">
              <div class="balance-main">
                <div class="available-balance">
                  <div class="amount fs-1 fw-bold text-success" style="line-height: 1;">
                    {{ currency_format($available_balance) }}</div>
                  <div class="label text-muted fw-bold text-uppercase small mt-2">
                    {{ __('front/withdrawal.available_balance') }}</div>
                </div>
              </div>
              <div class="balance-note text-muted small bg-white px-3 py-2 rounded-3 border shadow-sm">
                <i class="bi bi-info-circle text-primary me-1"></i>
                <span>{{ __('front/withdrawal.balance_note') }}</span>
              </div>
            </div>
          </div>
          @if($has_pending_withdrawal)
            <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center p-4">
              <i class="bi bi-exclamation-triangle fs-3 me-3"></i>
              <span class="fw-bold">{{ __('front/withdrawal.has_pending_withdrawal') }}</span>
            </div>
          @else
            <form action="{{ account_route('wallet.withdrawals.store') }}" method="POST" class="withdrawal-form">
              @csrf

              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="mb-4">
                    <label for="amount"
                      class="form-label fw-bold text-dark required">{{ __('front/withdrawal.withdrawal_amount') }}</label>
                    <div class="input-group">
                      <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount"
                        name="amount" step="0.01" min="0.01" max="{{ $available_balance }}" value="{{ old('amount') }}"
                        placeholder="{{ __('front/withdrawal.withdrawal_amount') }}" required>
                      <span class="input-group-text">{{ current_currency_code() }}</span>
                    </div>
                    @error('amount')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted fw-bold mt-2"><i
                        class="bi bi-check2-circle text-success me-1"></i>{{ __('front/withdrawal.available_balance') }}:
                      <span class="text-dark">{{ currency_format($available_balance) }}</span></div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="mb-4">
                    <label for="account_type"
                      class="form-label fw-bold text-dark required">{{ __('front/withdrawal.account_type') }}</label>
                    <select class="form-select @error('account_type') is-invalid @enderror" id="account_type"
                      name="account_type" required>
                      <option value="">{{ __('front/common.please_choose') }}</option>
                      @foreach($account_types as $type)
                        <option value="{{ $type['value'] }}" {{ old('account_type') == $type['value'] ? 'selected' : '' }}>
                          {{ $type['label'] }}
                        </option>
                      @endforeach
                    </select>
                    @error('account_type')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="mb-4">
                    <label for="account_number"
                      class="form-label fw-bold text-dark required">{{ __('front/withdrawal.account_number') }}</label>
                    <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                      id="account_number" name="account_number" value="{{ old('account_number') }}"
                      placeholder="{{ __('front/withdrawal.account_number') }}" required>
                    @error('account_number')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="mb-4 bank-info" style="display: none;">
                    <label for="bank_name"
                      class="form-label fw-bold text-dark">{{ __('front/withdrawal.bank_name') }}</label>
                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                      name="bank_name" value="{{ old('bank_name') }}" placeholder="{{ __('front/withdrawal.bank_name') }}">
                    @error('bank_name')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="mb-4 bank-info" style="display: none;">
                    <label for="bank_account"
                      class="form-label fw-bold text-dark">{{ __('front/withdrawal.bank_account') }}</label>
                    <input type="text" class="form-control @error('bank_account') is-invalid @enderror" id="bank_account"
                      name="bank_account" value="{{ old('bank_account') }}"
                      placeholder="{{ __('front/withdrawal.bank_account') }}">
                    @error('bank_account')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="mb-5">
                    <label for="comment" class="form-label fw-bold text-dark">{{ __('front/withdrawal.comment') }}</label>
                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment"
                      rows="3" placeholder="{{ __('front/withdrawal.comment') }}">{{ old('comment') }}</textarea>
                    @error('comment')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="form-actions border-top pt-4 text-end">
                <a href="{{ account_route('wallet.withdrawals.index') }}"
                  class="btn btn-light rounded-pill px-4 fw-bold me-2 border shadow-sm">
                  {{ __('front/common.cancel') }}
                </a>
                <button type="submit" class="btn btn-gradient-primary shadow-sm">
                  <i class="bi bi-check-circle me-1"></i> {{ __('front/withdrawal.submit_application') }}
                </button>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.withdrawals_create.bottom')
@endsection
@push('footer')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const accountTypeSelect = document.getElementById('account_type');
      const bankInfoElements = document.querySelectorAll('.bank-info');

      function toggleBankInfo() {
        const isBank = accountTypeSelect.value === 'bank';
        bankInfoElements.forEach(element => {
          element.style.display = isBank ? 'block' : 'none';
        });
      }

      accountTypeSelect.addEventListener('change', toggleBankInfo);

      // Initialize display state
      toggleBankInfo();
    });
  </script>
@endpush
