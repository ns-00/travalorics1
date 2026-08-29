@extends('layouts.app')
@section('body-class', 'page-checkout-success')

@section('content')

  <x-front-breadcrumb type="static" value="{{ front_route('orders.pay', ['number' => $order->number]) }}"
    title="{{ $order->number }}" />

  @push('header')
    <style>
      /* Force Global Fonts Override (Fix for Arabic) */
      [dir="rtl"] .premium-pay-container, [dir="rtl"] .premium-pay-container * { font-family: 'Cairo', sans-serif !important; letter-spacing: normal !important; }
      .premium-pay-container, .premium-pay-container * { font-family: 'Inter', sans-serif !important; }

      .premium-pay-container {
        padding: 40px 15px 80px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #fdfdfd 0%, #f4f6f8 100%);
      }
      
      .premium-pay-box {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
      }
      
      .pay-header {
        color: #1B4D3E;
        font-weight: 800;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
      }

      .order-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        text-align: left;
        background: #f8f9fa;
        padding: 30px;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.03);
      }

      html[dir="rtl"] .order-details-grid {
        text-align: right;
      }
      
      .order-detail-item p {
        margin: 0;
        color: #6c757d;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 8px;
      }
      .order-detail-item h5 {
        margin: 0;
        color: #212529;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
      }

      @media (max-width: 576px) {
        .order-details-grid {
          grid-template-columns: 1fr;
          gap: 15px;
          padding: 20px;
        }
        .premium-pay-box {
          padding: 25px 20px;
          border-radius: 20px;
        }
      }
    </style>
  @endpush

  @hookinsert('order.pay.top')

  <div class="premium-pay-container">
    <div class="container position-relative z-1">
      @error('error')
        <div class="alert alert-danger border-0 rounded-3 shadow-sm w-max-800 mx-auto mb-4">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $message }}
        </div>
      @enderror

      @if(isset($error))
        <div class="alert alert-danger border-0 rounded-3 shadow-sm w-max-800 mx-auto mb-4">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}
        </div>
      @endif

      <div class="premium-pay-box w-max-800 mx-auto">
        <h4 class="pay-header mb-4 text-center"><i class="bi bi-shield-lock-fill text-success"></i>{{ __('front/checkout.checkout') }}</h4>
        
        <div class="order-details-grid">
          <div class="order-detail-item">
            <p>{{ __('front/order.order_number') }}</p>
            <h5>#{{ $order->number }}</h5>
          </div>
          <div class="order-detail-item">
            <p>{{ __('front/order.order_billing') }}</p>
            <h5><span class="badge bg-light text-dark border px-3">{{ $order->billing_method_name }}</span></h5>
          </div>
          <div class="order-detail-item">
            <p>{{ __('front/order.order_total') }}</p>
            <h5 class="text-success fs-4">{{ currency_format($order->total) }}</h5>
          </div>
          <div class="order-detail-item">
            <p>{{ __('front/order.order_status') }}</p>
            <h5><span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">{!! $order->status_format !!}</span></h5>
          </div>
        </div>
      </div>

      <div class="d-flex flex-column justify-content-center w-max-800 mx-auto">
        @if(isset($view_path) && isset($view_data))
          @include($view_path, $view_data)
        @endif
      </div>
    </div>
  </div>

  @hookinsert('order.pay.bottom')

@endsection
