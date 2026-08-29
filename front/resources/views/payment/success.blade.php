@extends('layouts.app')
@section('body-class', 'page-checkout-success')

@push('header')
<style>
  .success-container {
    padding: 60px 15px;
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F4F1EA 0%, #e8e2d2 100%);
  }
  .success-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 24px;
    padding: 50px 40px;
    box-shadow: 0 20px 40px rgba(27, 77, 62, 0.08);
    text-align: center;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
  }
  .success-icon-wrap {
    width: 100px;
    height: 100px;
    background: #1B4D3E;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    box-shadow: 0 10px 20px rgba(27, 77, 62, 0.2);
    animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
  }
  .success-icon-wrap svg {
    color: #F4F1EA;
    width: 50px;
    height: 50px;
    animation: checkmark 0.5s ease-in-out 0.3s forwards;
    opacity: 0;
    transform: scale(0.5);
  }
  @keyframes scaleIn {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }
  @keyframes checkmark {
    0% { transform: scale(0.5); opacity: 0; }
    50% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
  }
  .success-title {
    color: #1B4D3E;
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
  }
  .success-subtitle {
    color: #666;
    font-size: 16px;
    margin-bottom: 40px;
  }
  .order-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    text-align: left;
    background: rgba(244, 241, 234, 0.6);
    padding: 25px;
    border-radius: 16px;
    margin-bottom: 40px;
  }
  .order-detail-item p {
    margin: 0;
    color: #777;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 5px;
  }
  .order-detail-item h5 {
    margin: 0;
    color: #1B4D3E;
    font-size: 18px;
    font-weight: 700;
  }
  .success-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
  }
  .btn-success-primary {
    background: #1B4D3E;
    color: #F4F1EA;
    border: none;
    padding: 14px 30px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    text-decoration: none;
    flex: 1;
  }
  .btn-success-primary:hover {
    background: #123329;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(27, 77, 62, 0.2);
  }
  .btn-success-secondary {
    background: transparent;
    color: #1B4D3E;
    border: 2px solid #1B4D3E;
    padding: 12px 30px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    text-decoration: none;
    flex: 1;
  }
  .btn-success-secondary:hover {
    background: rgba(27, 77, 62, 0.05);
    color: #1B4D3E;
  }
  
  html[dir="rtl"] .order-details-grid {
    text-align: right;
  }
  
  @media (max-width: 576px) {
    .order-details-grid {
      grid-template-columns: 1fr;
      gap: 15px;
    }
    .success-actions {
      flex-direction: column;
    }
    .success-card {
      padding: 30px 20px;
      border-radius: 16px;
    }
  }
</style>
@endpush

@section('content')

  @hookinsert('checkout.success.top')

  <div class="success-container">
    <div class="success-card">
      @if($order)
        <div class="success-icon-wrap">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        
        <h2 class="success-title">
          @if($order->payment_method_code == 'bank_transfer')
            {{ trans('front/payment.bank_transfer_success_title') }}
          @else
            {{ trans('front/payment.success_title') }}
          @endif
        </h2>
        <p class="success-subtitle">{{ __('front/payment.success_subtitle') ?? 'Thank you! Your order has been received successfully.' }}</p>

        <div class="order-details-grid">
          <div class="order-detail-item">
            <p>{{ trans('front/order.order_number') }}</p>
            <h5>#{{ $order->number }}</h5>
          </div>
          <div class="order-detail-item">
            <p>{{ trans('front/order.order_date') }}</p>
            <h5>{{ $order->created_at->format('M d, Y') }}</h5>
          </div>
          <div class="order-detail-item">
            <p>{{ trans('front/order.order_total') }}</p>
            <h5>{{ currency_format($order->total) }}</h5>
          </div>
          <div class="order-detail-item">
            <p>{{ trans('front/order.order_status') }}</p>
            <h5><span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">{{ $order->status_format }}</span></h5>
          </div>
        </div>

        <div class="success-actions">
          @if(current_customer())
            <a href="{{ account_route('orders.number_show', ['number'=>$order->number]) }}" class="btn-success-primary">
              {{ trans('front/payment.view_order') }}
            </a>
          @else
            <a href="{{ front_route('orders.number_show', ['number'=>$order->number]) }}" class="btn-success-primary">
              {{ trans('front/payment.view_order') }}
            </a>
          @endif
          <a href="{{ front_route('home.index') }}" class="btn-success-secondary">
            {{ trans('front/payment.continue_shopping') }}
          </a>
        </div>
      @else
        <div class="text-center py-5">
          <h4 class="text-muted">No order found.</h4>
          <a href="{{ front_route('home.index') }}" class="btn-success-secondary mt-3">Return to Home</a>
        </div>
      @endif
    </div>
  </div>

  @hookinsert('checkout.success.bottom')
@endsection
