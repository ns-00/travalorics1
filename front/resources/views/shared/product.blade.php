@if($product->fallbackName())
<div class="product-card-premium mb-4" data-aos="fade-up">
    <div class="image-wrapper">
        @if((isset($is_bestseller) && $is_bestseller) || $product->sales >= 10 || $product->order_items_count >= 10)
        <span class="badge bg-dark position-absolute top-0 start-0 m-3 px-3 py-2 shadow-sm fs-6"
              style="border-radius: 4px; z-index: 10;">
            🔥 {{ __('front/product.bestseller') }}
        </span>
        @endif
        <div class="wishlist-container add-wishlist position-absolute top-0 end-0 m-3 bg-white rounded-circle shadow-sm"
             data-in-wishlist="{{ $product->hasFavorite() }}" data-id="{{ $product->id }}"
             data-price="{{ $product->masterSku->price }}"
             style="cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; z-index: 10; transition: all 0.3s;">
            <i class="bi bi-heart{{ $product->hasFavorite() ? '-fill text-danger' : ' text-secondary' }} fs-5"></i>
        </div>
        <a href="{{ $product->url }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->fallbackName() }}">
        </a>
    </div>
    <div class="product-info-premium p-4">
        <a href="{{ $product->url }}" class="product-title-premium text-truncate d-block" style="min-height: auto;"
           data-bs-toggle="tooltip" title="{{ $product->fallbackName() }}">
            {{ $product->fallbackName() }}
        </a>
        <div class="text-warning small mb-2 d-flex align-items-center" title="{{ $product->seo_rating ?? 0 }} / 5">
            @php
                $rating = $product->seo_rating ?? 0;
            @endphp
            @for($i = 1; $i <= 5; $i++)
                @if($rating >= $i)
                    <i class="bi bi-star-fill"></i>
                @elseif($rating >= $i - 0.5)
                    <i class="bi bi-star-half"></i>
                @else
                    <i class="bi bi-star text-secondary"></i>
                @endif
            @endfor
            <span class="ms-1 text-muted small">({{ (int) $product->seo_reviews }})</span>
        </div>
        <div class="price-container mt-1 mb-3">
            <span class="price-current">{{ $product->masterSku->getFinalPriceFormat() }}</span>
            @if ($product->masterSku->origin_price)
                <span class="price-old">{{ $product->masterSku->origin_price_format }}</span>
            @endif
        </div>
        @if(!system_setting('disable_online_order'))
        <button class="btn-premium-cart btn-add-cart w-100" data-id="{{ $product->id }}"
                data-price="{{ $product->masterSku->getFinalPrice() }}" data-sku-id="{{ $product->masterSku->id }}">
            <i class="bi bi-cart-plus fs-5"></i>
            <span>{{ __('front/cart.add_to_cart') }}</span>
        </button>
        @endif
    </div>
</div>
@once
<style>
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
        background: #fdfdfd;
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
    .product-info-premium {
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
        margin-bottom: 8px;
        transition: color 0.3s;
    }
    .product-title-premium:hover {
        color: var(--primary);
    }
    .price-container {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-top: auto;
    }
    .price-current {
        font-weight: 800;
        color: var(--primary);
        font-size: 1.3rem;
    }
    .price-old {
        color: #adb5bd;
        text-decoration: line-through;
        font-size: 0.95rem;
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
    }
    .btn-premium-cart:hover {
        box-shadow: 0 6px 15px rgba(27, 77, 62, 0.4);
        transform: translateY(-2px);
        color: #fff;
    }
    .wishlist-container:hover {
        background: #f8f9fa !important;
        transform: scale(1.1);
    }
</style>
@endonce
@endif
