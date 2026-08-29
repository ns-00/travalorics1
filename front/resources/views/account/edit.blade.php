@extends('layouts.app')
@section('body-class', 'page-edit')
@section('content')
  <x-front-breadcrumb type="route" value="account.edit.index" title="{{ __('front/account.edit') }}" />
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

      .edit-form .form-control,
      .edit-form .form-select {
        border-radius: 12px;
        padding: 12px 16px;
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        transition: all 0.3s;
      }

      .edit-form .form-control:focus,
      .edit-form .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1);
        background: #fff;
      }
    </style>
  @endpush
  @hookinsert('account.edit.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card addresses-box">
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          @if (session('error'))
            <x-common-alert type="danger" msg="{{ session('error') }}" class="mb-4 rounded-4 shadow-sm border-0" />
          @endif
          <div class="account-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >{{ __('front/edit.edit') }}
            </h4>
          </div>
          <form class="needs-validation edit-form mt-4" action="{{ account_route('edit.index') }}" method="POST"
            novalidate>
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
              <div class="col-md-8">
                <x-common-form-imagep name="avatar" title="{{ __('front/edit.avatar') }}"
                  value="{{ old('avatar', $customer->avatar) }}" />

                <div class="mb-3">
                  <x-common-form-input name="name" title="{{ __('front/edit.name') }}"
                    value="{{ old('name', $customer->name) }}" required="required"
                    placeholder="{{ __('front/edit.name') }}" />
                </div>

                <div class="mb-4">
                  <x-common-form-input name="email" title="{{ __('front/edit.email') }}"
                    value="{{ old('email', $customer->email) }}" required="required"
                    placeholder="{{ __('front/edit.email') }}" />
                </div>

                <div class="text-center mt-5">
                  <button type="submit" class="btn btn-gradient-primary btn-lg w-100 shadow-sm">
                    <i class="bi bi-check-lg me-1"></i>{{ __('front/common.submit') }}
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.edit.bottom')
@endsection
