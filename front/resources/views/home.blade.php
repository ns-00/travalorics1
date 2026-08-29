@extends('layouts.app')
@section('body-class', 'page-home')

@push('header')
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
@endpush

@section('content')
<section class="module-content">
    {{-- Hero Slider --}}
    @if ($slideshow)
    <section class="module-line">
        <div class="module-swiper">
        <div class="swiper" id="module-swiper-1">
            <div class="swiper-wrapper">
                @foreach ($slideshow as $slide)
                @php $slideImage = $slide['image'][front_locale_code()] ?? $slide['image'][array_key_first($slide['image'] ?? [''=>''])] ?? null; @endphp
                @if ($slideImage)
                <div class="swiper-slide">
                    <a href="{{ $slide['link'] ?: 'javascript:void(0)' }}">
                        <img src="{{ image_origin($slideImage) }}" 
                             alt="{{ $slide['title'] ?? 'Slide' }}" 
                             class="img-fluid w-100">
                    </a>
                </div>
                @endif
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
        </div>
    </section>
    <script>
        var swiper = new Swiper('#module-swiper-1', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            effect: 'slide',
            speed: 600,
            slidesPerView: 1,
            preloadImages: true,
            updateOnImagesReady: true,
        });
    </script>
    @endif

    {{-- Featured Products --}}
    <section class="module-line">
        <div class="module-product-tab">
            <div class="container">
                <div class="module-title-wrap">
                    <h2 class="module-title">{{ __('front/home.feature_product') }}</h2>
                    <p class="module-sub-title">{{ __('front/home.feature_product_text') }}</p>
                </div>

                <ul class="nav nav-tabs" role="tablist">
                    @foreach ($tab_products as $item)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                data-bs-toggle="tab"
                                data-bs-target="#module-product-tab-x-{{ $loop->iteration }}"
                                type="button"
                                role="tab">
                            {{ $item['tab_title'] }}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach ($tab_products as $item)
                    <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                         id="module-product-tab-x-{{ $loop->iteration }}"
                         role="tabpanel">
                        <div class="row g-3 g-lg-4">
                            @foreach ($item['products'] as $product)
                            <div class="col-6 col-md-4 col-lg-3">
                                @include('shared.product', ['is_bestseller' => $item['is_bestseller'] ?? false])
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
