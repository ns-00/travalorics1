@extends('layouts.app')
@section('body-class', 'page-checkout-pending')

@push('header')
<style>
  .pending-container {
    padding: 60px 15px;
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #FEF9E7 0%, #F9E79F 100%);
  }
  .pending-card {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 24px;
    padding: 50px 40px;
    box-shadow: 0 20px 40px rgba(241, 196, 15, 0.1);
    text-align: center;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
  }
  .pending-icon-wrap {
    width: 100px;
    height: 100px;
    background: #F39C12;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
  }
  .pending-icon-wrap svg {
    color: #FFF;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
  }
  @keyframes spin { 100% { transform: rotate(360deg); } }
  .pending-title {
    color: #D68910;
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 10px;
  }
  .pending-subtitle {
    color: #666;
    font-size: 16px;
    margin-bottom: 30px;
  }
  .btn-pending-primary {
    background: #D68910;
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
  <div class="pending-container">
    <div class="pending-card">
      <div class="pending-icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      
      <h2 class="pending-title">الدفع قيد المعالجة</h2>
      <p class="pending-subtitle">
        جاري معالجة دفعتك حالياً بواسطة البنك. يرجى الانتظار قليلاً وعدم إغلاق الصفحة.
      </p>

      <div class="mt-4">
        <a href="{{ front_route('home.index') }}" class="btn-pending-primary">العودة للرئيسية</a>
      </div>
    </div>
  </div>
@endsection
