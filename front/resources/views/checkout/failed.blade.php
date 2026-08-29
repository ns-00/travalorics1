@extends('layouts.app')
@section('body-class', 'page-checkout-failed')

@push('header')
<style>
  .failed-container {
    padding: 60px 15px;
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F9EBEA 0%, #F5B7B1 100%);
  }
  .failed-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 24px;
    padding: 50px 40px;
    box-shadow: 0 20px 40px rgba(176, 58, 46, 0.1);
    text-align: center;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
  }
  .failed-icon-wrap {
    width: 100px;
    height: 100px;
    background: #E74C3C;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
  }
  .failed-icon-wrap svg {
    color: #FFF;
    width: 50px;
    height: 50px;
  }
  .failed-title {
    color: #C0392B;
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 10px;
  }
  .failed-subtitle {
    color: #666;
    font-size: 16px;
    margin-bottom: 30px;
  }
  .btn-failed-primary {
    background: #C0392B;
    color: #FFF;
    border: none;
    padding: 14px 30px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
  }
</style>
@endpush

@section('content')
  <div class="failed-container">
    <div class="failed-card">
      <div class="failed-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      
      <h2 class="failed-title">فشلت عملية الدفع</h2>
      <p class="failed-subtitle">
        عذراً، لم نتمكن من معالجة عملية الدفع الخاصة بك. يرجى التأكد من بيانات البطاقة أو المحاولة باستخدام طريقة دفع أخرى.
      </p>

      @if(isset($chargeData) && isset($chargeData['response']['message']))
        <div class="alert alert-danger">
          السبب: {{ $chargeData['response']['message'] }}
        </div>
      @endif

      <div class="mt-4">
        <a href="{{ front_route('checkout.index') }}" class="btn-failed-primary">العودة للدفع</a>
      </div>
    </div>
  </div>
@endsection
