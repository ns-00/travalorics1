@extends('layouts.app')
@section('body-class', 'page-edit')
@section('content')
  <x-front-breadcrumb type="route" value="account.password.index" title="{{ __('front/account.password') }}" />
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

      .edit-form .form-control {
        border-radius: 12px;
        padding: 12px 16px;
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
      }

      .edit-form .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        background: #fff;
      }
    </style>
  @endpush
  @hookinsert('account.password.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card addresses-box">
          @if (session()->has('errors'))
            <x-common-alert type="danger" msg="{{ session('errors')->first() }}"
              class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          <div class="account-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/password.password') }}</h4>
          </div>
          <form action="{{ account_route('password.update') }}" class="needs-validation edit-form mt-4" novalidate
            method="POST">
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
              <div class="col-md-8">
                <div class="mb-3">
                  <x-common-form-input name="old_password" title="{{ __('front/password.old_password') }}" value=""
                    type="password" required="required" placeholder="{{ __('front/password.old_password') }}" />
                </div>
                <div class="mb-3">
                  <x-common-form-input name="new_password" title="{{ __('front/password.new_password') }}" value=""
                    type="password" required="required" placeholder="{{ __('front/password.new_password') }}" />
                </div>
                <div class="mb-4">
                  <x-common-form-input name="new_password_confirmation"
                    title="{{ __('front/password.confirm_password') }}" value="" type="password" required="required"
                    placeholder="{{ __('front/password.confirm_password') }}" />
                </div>

                <div class="text-center mt-5">
                  <button type="submit" class="btn btn-gradient-primary btn-lg submit-form w-100 shadow-sm">
                    <i class="bi bi-shield-lock me-1"></i>{{ __('front/common.submit') }}
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.password.bottom')
@endsection
@push('footer')
  <script></script>
@endpush
