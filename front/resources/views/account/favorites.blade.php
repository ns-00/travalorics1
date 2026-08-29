@extends('layouts.app')
@section('body-class', 'page-wishlist')
@section('content')
<x-front-breadcrumb type="route" value="account.favorites.index" title="{{ __('front/account.favorites') }}" />
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

    .product-card-premium {
      background: #fff;
      border-radius: 20px;
      border: 1px solid #f0f0f0;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      height: 100%;
      position: relative;
      display: flex;
      flex-direction: column;
    }

    .product-card-premium:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
      border-color: var(--primary);
    }

    .product-card-premium .image-wrapper {
      position: relative;
      padding-top: 100%;
      background: #f8f9fa;
      overflow: hidden;
    }

    .product-card-premium .image-wrapper img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 20px;
      transition: transform 0.5s ease;
    }

    .product-card-premium:hover .image-wrapper img {
      transform: scale(1.05);
    }

    .cancel-favorite-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      z-index: 10;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(5px);
      color: #dc3545;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s;
      border: 1px solid rgba(255, 255, 255, 0.5);
    }

    [dir="rtl"] .cancel-favorite-btn {
      right: auto;
      left: 15px;
    }

    .cancel-favorite-btn:hover {
      background: #dc3545;
      color: #fff;
      transform: rotate(90deg);
    }

    .product-info-premium {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .product-title-premium {
      color: var(--dark);
      text-decoration: none;
      font-weight: 700;
      font-size: 1.05rem;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 12px;
      transition: color 0.3s;
    }

    .product-title-premium:hover {
      color: var(--primary);
    }

    .price-container {
      display: flex;
      align-items: baseline;
      gap: 10px;
      margin-bottom: 20px;
      margin-top: auto;
    }

    .price-current {
      font-weight: 800;
      color: var(--primary);
      font-size: 1.25rem;
    }

    .price-old {
      color: #adb5bd;
      text-decoration: line-through;
      font-size: 0.9rem;
    }

    .btn-premium-cart {
      background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 12px;
      font-weight: 600;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      width: 100%;
    }

    .btn-premium-cart:hover {
      box-shadow: 0 6px 15px rgba(27, 77, 62, 0.4);
      transform: translateY(-2px);
      color: #fff;
    }
  </style>
@endpush
@hookinsert('account.favorites.top')
<div class="container py-4">
  <div class="row gx-lg-5">
    <div class="col-12 col-lg-3 mb-4 mb-lg-0">
      @include('shared.account-sidebar')
    </div>
    <div class="col-12 col-lg-9">
      <div class="premium-dashboard-card wishlist-box">
        <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
          <h4 class="fw-bold mb-0 text-dark" >
            {{ __('front/favorites.favorites') }}</h4>
        </div>
        @if ($favorites->count())
        <div class="row g-4">
          @foreach ($favorites as $product)
          @php($product = $product->product)
              <div class="col-6 col-md-4">
                <div class="product-card-premium">
                  <div class="image-wrapper">
                    <div class="cancel-favorite-btn cancel-favorite" data-id="{{ $product->id }}" data-in-wishlist="1"
                      title="Remove"><i class="bi bi-trash"></i></div>
                    <a href="{{ $product->url }}">
                      <img src="{{ $product->image_url }}" alt="{{ $product->fallbackName() }}">
                    </a>
                  </div>
                  <div class="product-info-premium">
                    <a href="{{ $product->url }}" class="product-title-premium"
                      style="min-height: 46px;">{{ $product->fallbackName() }}</a>

                    <div class="price-container mt-2">
                      <span class="price-current">{{ $product->masterSku->price_format }}</span>
                      @if ($product->masterSku->origin_price)
                        <span class="price-old">{{ $product->masterSku->origin_price_format }}</span>
                      @endif
                    </div>

                    <div class="btn-premium-cart cursor-pointer btn-add-cart" data-id="{{ $product->id }}"
                      data-sku-id="{{ $product->masterSku->id }}">
                      <i class="bi bi-cart-plus fs-5"></i>
                      <span>{{ __('front/product.add_to_cart') }}</span>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          @else
        <div class="text-center py-5">
          <i class="bi bi-heart text-muted display-1 opacity-25 mb-3 d-block"></i>
          <p class="text-muted fw-bold">{{ __('front/account.no_order') ?? 'No favorite items yet.' }}</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@hookinsert('account.favorites.bottom')
@endsection
@push('footer')
  <script>
    $('.cancel-favorite').on('click', function () {
      const id = $(this).attr('data-id');
      layer.load(2, { shade: [0.3, "#fff"] });
      inno.addWishlist(id, 1, null, function () {
        setTimeout(() => {
          location.reload();
        }, 500);
      })
    });
  </script>
@endpush
