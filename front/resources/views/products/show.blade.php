@extends('layouts.app')
@section('og_title', $product->name)
@section('og_description', strip_tags($product->description ?? system_setting_locale('meta_description', '')))
@section('og_image', $product->image ? image_origin($product->image) : asset(system_setting('front_logo', 'images/logo.svg')))

@section('body-class', 'page-product')

@section('title', \Travalorics\Common\Libraries\MetaInfo::getInstance($product)->getTitle())
@section('description', \Travalorics\Common\Libraries\MetaInfo::getInstance($product)->getDescription())
@section('keywords', \Travalorics\Common\Libraries\MetaInfo::getInstance($product)->getKeywords())

@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">

  <script src="{{ asset('vendor/photoswipe/umd/photoswipe.umd.min.js') }}"></script>
  <script src="{{ asset('vendor/photoswipe/umd/photoswipe-lightbox.umd.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/photoswipe/photoswipe.css') }}">
  
  <script src="{{ asset('vendor/video-js/video.min.js') }}"></script>
  <link href="{{ asset('vendor/video-js/video-js.css') }}" rel="stylesheet">
  
  <style>
      /* Premium UI Tokens */
      .premium-buy-box {
          background: rgba(255, 255, 255, 0.85);
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          border: 1px solid rgba(255, 255, 255, 0.5);
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
          border-radius: 20px;
          padding: 30px;
      }
      .btn-gradient-primary {
          background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
          border: none;
          color: #fff;
          border-radius: 30px;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(27, 77, 62, 0.3);
      }
      .btn-gradient-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(27, 77, 62, 0.5);
          color: #fff;
      }
      .btn-gradient-dark {
          background: linear-gradient(135deg, #2b2b2b 0%, #000000 100%);
          border: none;
          color: #fff;
          border-radius: 30px;
          transition: all 0.3s ease;
      }
      .btn-gradient-dark:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
          color: #fff;
      }
      .price-badge {
          background: rgba(40, 167, 69, 0.1);
          color: #28a745;
          padding: 6px 12px;
          border-radius: 8px;
          font-size: 0.9rem;
      }
      .trust-icons i {
          font-size: 1.8rem;
          color: #adb5bd;
          margin: 0 10px;
          transition: all 0.3s ease;
      }
      .trust-icons i:hover { color: var(--primary); transform: scale(1.1); }
      .custom-add-wishlist {
          transition: transform 0.2s;
          display: inline-block;
          padding: 8px 16px;
          border-radius: 20px;
          background: #f8f9fa;
          border: 1px solid #e9ecef;
      }
      .custom-add-wishlist:hover {
          transform: scale(1.05);
          background: #fff;
          box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      }
      .custom-add-wishlist .bi-heart-fill { color: #dc3545 !important; }
      
      /* 📱 Mobile Specific Product CSS */
      .mobile-buy-bar {
          position: fixed;
          bottom: -120px;
          left: 0;
          width: 100%;
          z-index: 1050;
          transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          padding-bottom: env(safe-area-inset-bottom);
      }
      .mobile-buy-bar.show {
          bottom: 0;
      }
      
      @media (max-width: 991px) {
          .premium-buy-box {
              padding: 20px 15px;
              border-radius: 16px;
          }
          .product-header h1 {
              font-size: 1.6rem !important;
          }
          .price-block h2 {
              font-size: 1.8rem !important;
          }
          .custom-add-wishlist {
              width: 100%;
              text-align: center;
              justify-content: center;
              padding: 12px;
          }
          /* Ensure the page doesn't get covered by the sticky bar at the very bottom */
          .product-description-sections {
              padding-bottom: 90px;
          }
      }
      
      /* Force Global Fonts Override (Fix for Arabic) */
      [dir="rtl"] body.page-product, [dir="rtl"] .page-product * { font-family: 'Cairo', sans-serif !important; }
      body.page-product, .page-product * { font-family: 'Inter', sans-serif !important; }
  </style>

  @include('products.components._seo')
@endpush

@section('content')

  <x-front-breadcrumb type="product" :value="$product"/>

  @hookinsert('product.show.top')

  <div class="container">
    <div class="page-product-top">
      <div class="row">
        <div class="col-12 col-lg-6 product-left-col">
          <div class="product-images">
            @include('products.components._images')
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="product-info premium-buy-box mt-4 mt-lg-0">
            <div class="product-header mb-4">
              <h1 class="fw-bold mb-2" style="font-size: 2.2rem;">{{ $product->fallbackName() }}</h1>
              <div class="sub-product-title text-muted mb-4 fs-5">{{ $product->fallbackName('summary') }}</div>

              <!-- 1. السعر -->
              @hookupdate('front.product.show.price')
              <div class="price-block mb-3 d-flex align-items-center gap-3 bg-light p-3 rounded-4">
                <h2 class="fw-bold text-dark m-0" style="font-size: 2.5rem; color: var(--primary) !important;">{{ $sku['price_format'] }}</h2>
                @if($sku['origin_price'])
                  <span class="old-price text-muted text-decoration-line-through fs-5">{{ $sku['origin_price_format'] }}</span>
                @endif
                @if($sku['quantity'] > 0)
                  <span class="price-badge fw-bold ms-auto d-flex align-items-center gap-1">
                      <i class="bi bi-check-circle-fill"></i> {{ __('front/product.in_stock') }}
                  </span>
                @endif
              </div>
              @endhookupdate

              <!-- 2. Social Proof -->
              @php 
                $activeReviews = $product->reviews()->where('active', 1);
                $c = $activeReviews->count();
                $r = $c > 0 ? round($activeReviews->avg('rating'), 1) : 0;
              @endphp
              <div class="mb-3 d-flex align-items-center gap-2" style="cursor: pointer;" onclick="document.getElementById('product-description-reviews').scrollIntoView({behavior: 'smooth', block: 'start'})">
                <div class="bg-dark text-white px-2 py-1 rounded d-flex align-items-center gap-1 shadow-sm">
                  <span class="text-warning">★</span>
                  <span class="fw-bold">{{ $r }}</span>
                </div>
                <span class="text-muted fw-medium text-decoration-underline">({{ $c }} {{ trans_choice('front/product.verified_buyers', $c) }})</span>
                <span class="badge bg-danger ms-2 bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">
                    🔥 {{ $reviewStats['recommend_percent'] ?? 100 }}% {{ __('front/product.recommend') }}
                </span>
              </div>

              <!-- 3. Scarcity & Fake Activity -->
              @if(!system_setting('disable_online_order'))
              <div class="mb-4 small fw-bold d-flex justify-content-between align-items-center border-bottom pb-3">
                <div class="text-success bg-success bg-opacity-10 px-3 py-2 rounded-pill"><i class="bi bi-cart-check-fill me-1"></i> {{ $product->actual_sales > 0 ? $product->actual_sales : ($product->sales ?? 0) }} {{ __('front/product.items_sold') }}</div>
                <div class="text-secondary"><i class="bi bi-eye-fill me-1"></i> {{ $product->viewed ?? 0 }} {{ __('front/product.people_viewing') }}</div>
              </div>
              @endif
            </div>

            @include('products.components._bundle_items')
            @include('products.components._variants')
            @include('products.components._options')

            @if(!system_setting('disable_online_order'))
                <!-- 4. الكمية + زر Add to cart -->
                <div class="d-flex gap-3 align-items-center w-100 mt-4">
                  <div class="quantity-wrap border rounded-pill d-flex align-items-center flex-shrink-0 bg-white shadow-sm" style="width: 130px; height: 55px; overflow: hidden;">
                    <div class="minus px-3 h-100 d-flex align-items-center bg-light" style="cursor: pointer;"><i class="bi bi-dash-lg"></i></div>
                    <input type="number" class="form-control border-0 text-center fw-bold product-quantity h-100 px-0 fs-5" value="1" data-sku-id="{{ $sku['id'] }}" style="box-shadow: none;">
                    <div class="plus px-3 h-100 d-flex align-items-center bg-light" style="cursor: pointer;"><i class="bi bi-plus-lg"></i></div>
                  </div>
                  
                  <button class="btn btn-gradient-dark rounded-pill w-100 h-100 fw-bold add-cart m-0 fs-5 d-flex justify-content-center align-items-center gap-2" data-id="{{ $product->id }}"
                          data-price="{{ $product->masterSku->price }}" style="height: 55px !important;">
                    <i class="bi bi-bag-plus"></i> {{ __('front/product.add_to_cart') }}
                  </button>
                </div>
                
                <!-- 5. زر الشراء الفوري -->
                <button class="btn btn-gradient-primary rounded-pill w-100 mt-3 shadow-lg fs-5 fw-bold d-flex justify-content-center align-items-center gap-2" style="height: 60px;" onclick="instantBuy()">
                    <i class="bi bi-lightning-charge-fill"></i> {{ __('front/product.instant_buy') }}
                </button>

                <!-- 6. Trust Layer -->
                <div class="mt-4 text-center">
                    <div class="mb-2 fw-bold text-dark"><i class="bi bi-shield-lock-fill text-success me-1"></i> تسوق آمن وموثوق 100%</div>
                    <div class="trust-icons d-flex justify-content-center mt-2">
                        <i class="bi bi-credit-card-2-front-fill" title="Credit Card"></i>
                        <i class="bi bi-wallet-fill" title="Wallet"></i>
                        <i class="bi bi-cash-stack" title="Cash"></i>
                        <i class="bi bi-shield-check" title="Secure"></i>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-center mt-4">
                <div class="custom-add-wishlist shadow-sm fw-bold text-dark d-flex align-items-center gap-2" data-in-wishlist="{{ $product->hasFavorite() ? 1 : 0 }}"
                     data-id="{{ $product->id }}"
                     data-price="{{ $product->masterSku->price }}" style="cursor: pointer;" onclick="toggleWishlist(this)">
                  <i class="bi bi-heart{{ $product->hasFavorite() ? '-fill text-danger' : '' }}" id="wishlist-icon"></i> 
                  <span id="wishlist-text">{{ __('front/product.add_wishlist') }}</span>
                </div>
            </div>
            @hookinsert('product.detail.after')
          </div>
        </div>
      </div>
    </div>

    <div class="product-description-sections mt-5">
        
        <!-- 1. Description Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden" id="product-description-description">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-card-text me-2 text-primary"></i> {{ __('front/product.description') }}</h4>
            </div>
            <div class="card-body p-4 p-md-5" style="line-height: 1.8;">
              @if($product->fallbackName('selling_point'))
                {!! parsedown($product->fallbackName('selling_point')) !!}
              @endif
              {!! $product->fallbackName('content') !!}
              @hookinsert('product.detail.description.after')
            </div>
        </div>

        <!-- 2. Attributes Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden" id="product-description-attribute">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-list-stars me-2 text-primary"></i> {{ __('front/product.attribute') }}</h4>
            </div>
            <div class="card-body p-4 p-md-5">
              @if($attributes && count($attributes) > 0)
                <div class="table-responsive">
                  <table class="table table-hover border">
                    @foreach ($attributes as $group)
                      <thead class="bg-light">
                      <tr>
                        <th colspan="2" class="text-uppercase text-muted">{{ $group['attribute_group_name'] }}</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach ($group['attributes'] as $item)
                        <tr>
                          <td class="fw-bold" style="width: 35%;">{{ $item['attribute'] }}</td>
                          <td>{{ $item['attribute_value'] }}</td>
                        </tr>
                      @endforeach
                      </tbody>
                    @endforeach
                  </table>
                </div>
              @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-info-circle display-4 d-block mb-3 opacity-50"></i>
                    {{ __('front/common.no_data') }}
                </div>
              @endif
            </div>
        </div>

        <!-- 3. Related Products Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden" id="product-description-correlation">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-collection-play me-2 text-primary"></i> {{__('front/product.related_product')}}</h4>
            </div>
            <div class="card-body p-4 p-md-5">
                @if(isset($related) && count($related) > 0)
                  <div class="row gx-3 gx-lg-4">
                    @foreach ($related as $relatedItem)
                      <div class="col-6 col-md-4 col-lg-3">
                        @include('shared.product', ['product'=>$relatedItem])
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="text-center py-5 text-muted">
                      <i class="bi bi-box-seam display-4 d-block mb-3 opacity-50"></i>
                      {{ __('front/common.no_data') }}
                  </div>
                @endif
            </div>
        </div>

        <!-- 4. Reviews Section -->
        <div class="card border-0 shadow-sm mb-5 rounded-4 overflow-hidden" id="product-description-reviews">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-chat-heart me-2 text-primary"></i> {{ __('front/product.review') }}</h4>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="product-reviews-container" id="product-review-container">
                    @include('products.components._review_section')
                </div>
            </div>
        </div>

        @hookinsert('product.detail.tab.pane.after')
    </div>

    @hookinsert('product.show.bottom')

  </div>

  <!-- 📱 Mobile Sticky Buy Bar (Conversion Killer Feature) -->
  <div class="mobile-buy-bar d-lg-none" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-top: 1px solid rgba(0,0,0,0.05); box-shadow: 0 -4px 20px rgba(0,0,0,0.05);">
      <div class="d-flex align-items-center justify-content-between px-3 py-2">
          <!-- Image & Price -->
          <div class="d-flex align-items-center gap-3">
              <img src="{{ image_origin($product->info['image'] ?? 'images/default_product.png') }}" width="45" height="45" class="rounded-3 shadow-sm border border-light" style="object-fit: cover;">
              <div class="d-flex flex-column">
                  <div class="fw-bold m-0" style="color: var(--primary); font-size: 1.2rem; line-height: 1.1;">
                      {{ $sku['price_format'] }}
                  </div>
                  <div class="d-flex align-items-center gap-1 mt-1">
                      <small class="text-warning fw-bold d-flex align-items-center" style="font-size: 0.8rem;">
                          <i class="bi bi-star-fill me-1"></i> {{ round($r, 1) }}
                      </small>
                      <small class="badge bg-success bg-opacity-10 text-success fw-bold ms-1 border border-success border-opacity-25" style="font-size: 0.65rem;">
                          {{ __('front/product.in_stock') }}
                      </small>
                  </div>
              </div>
          </div>
          <!-- Buy Trigger -->
          <button class="btn btn-gradient-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-1"
                  onclick="instantBuy()" style="height: 45px;">
              <i class="bi bi-lightning-charge-fill"></i> {{ __('front/product.instant_buy') }}
          </button>
      </div>
  </div>

@endsection

@push('footer')
  <script>
    $('.quantity-wrap .plus, .quantity-wrap .minus').on('click', function () {
      if ($(this).parent().hasClass('disabled')) {
        return;
      }

      let quantity = parseInt($(this).siblings('input').val());
      if ($(this).hasClass('plus')) {
        $(this).siblings('input').val(quantity + 1);
      } else {
        if (quantity > 1) {
          $(this).siblings('input').val(quantity - 1);
        }
      }
    });

    $('.add-cart, .buy-now').on('click', function () {
      // 验证必需选项是否已选择
      if (typeof validateRequiredOptions === 'function' && !validateRequiredOptions()) {
        // 滚动到第一个错误的选项组
        const $firstError = $('.option-group.has-error').first();
        if ($firstError.length) {
          $('html, body').animate({
            scrollTop: $firstError.offset().top - 100
          }, 500);
          $('.option-group').removeClass('shake');
          $firstError.addClass('shake');
        }
        
        if (window.inno && window.inno.alert) {
          window.inno.alert({msg: '{{ __("front/product.please_select_required_options") }}', type: 'warning'});
        } else {
          alert('{{ __("front/product.please_select_required_options") }}');
        }
        return;
      }

      const quantity = $('.product-quantity').val();
      const skuId = $('.product-quantity').data('sku-id');
      const isBuyNow = $(this).hasClass('buy-now');

      // 收集选中的选项
      const productOptions = {};
      
      // 收集下拉选择框的选项
      $('.option-select').each(function() {
        const optionId = $(this).data('option-id');
        const selectedValue = $(this).val();
        if (selectedValue) {
          productOptions[optionId] = [selectedValue];
        }
      });
      
      // 收集单选按钮的选项
      $('.option-radio-item input[type="radio"]:checked').each(function() {
        const optionId = $(this).data('option-id');
        const optionValue = $(this).val();
        productOptions[optionId] = [optionValue];
      });
      
      // 收集多选复选框的选项
      $('.option-checkbox-item input[type="checkbox"]:checked').each(function() {
        const optionId = $(this).data('option-id');
        const optionValue = $(this).val();
        if (!productOptions[optionId]) {
          productOptions[optionId] = [];
        }
        productOptions[optionId].push(optionValue);
      });

      // 准备请求数据
      const requestData = {
        skuId, 
        quantity, 
        isBuyNow,
        options: productOptions
      };
      
      let $btn = $(this);
      let originalText = $btn.html();
      $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> جاري الإضافة...');
      
      inno.addCart(requestData, this, function (res) {
        $btn.html('<i class="bi bi-check-circle-fill"></i> تمت الإضافة');
        $btn.addClass('btn-success').removeClass('btn-gradient-dark text-white');
        
        setTimeout(() => {
            $btn.prop('disabled', false).html(originalText);
            $btn.removeClass('btn-success').addClass('btn-gradient-dark text-white');
        }, 2000);
        
        if (isBuyNow) {
          window.location.href = '{{ front_route('carts.index') }}';
        }
      });
    });
    

    
    // Frictionless One-Click Buy
    function instantBuy(){
        if (typeof validateRequiredOptions === 'function' && !validateRequiredOptions()) {
            const $firstError = $('.option-group.has-error').first();
            if ($firstError.length) {
              $('html, body').animate({ scrollTop: $firstError.offset().top - 100 }, 500);
              $('.option-group').removeClass('shake');
              $firstError.addClass('shake');
            }
            if (window.inno && window.inno.alert) {
              window.inno.alert({msg: '{{ __("front/product.please_select_required_options") }}', type: 'warning'});
            }
            return;
        }

        console.log('Checkout Started'); // Event tracking reference
        
        const quantity = $('.product-quantity').val();
        const skuId = $('.product-quantity').data('sku-id');

        const productOptions = {};
        $('.option-select').each(function() {
          const optionId = $(this).data('option-id');
          const selectedValue = $(this).val();
          if (selectedValue) productOptions[optionId] = [selectedValue];
        });
        $('.option-radio-item input[type="radio"]:checked').each(function() {
          const optionId = $(this).data('option-id');
          const optionValue = $(this).val();
          productOptions[optionId] = [optionValue];
        });
        $('.option-checkbox-item input[type="checkbox"]:checked').each(function() {
          const optionId = $(this).data('option-id');
          const optionValue = $(this).val();
          if (!productOptions[optionId]) productOptions[optionId] = [];
          productOptions[optionId].push(optionValue);
        });

        const requestData = {
          skuId: skuId, 
          quantity: quantity, 
          isBuyNow: true,
          options: productOptions
        };

        let $btn = $('.btn-gradient-primary');
        let initialHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> جاري...');

        inno.addCart(requestData, $btn[0], function(res) {
            window.location.href = '{{ front_route('carts.index') }}';
        });

        setTimeout(() => {
            $btn.prop('disabled', false).html(initialHtml);
        }, 8000);
    }

    // Smart Mobile Sticky Bar
    let lastScrollTop = 0;
    window.addEventListener("scroll", function() {
        let bar = document.querySelector('.mobile-buy-bar');
        if (!bar) return;
        
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > 300) {
            bar.classList.add('show');
        } else {
            bar.classList.remove('show');
        }
        lastScrollTop = scrollTop;
    });

    // 🔥 Heat Trigger (Boost Conversions on idle)
    setTimeout(() => {
        document.querySelector('.mobile-buy-bar')?.classList.add('show');
    }, 4000);
    
    // Zoom Engine Bootstrap
    function initImageZoom() {
        const mainImg = document.querySelector('.main-image');
        if (mainImg && window.innerWidth > 991) {
            new Drift(mainImg, {
                paneContainer: document.body,
                inlinePane: false,
                hoverBoundingBox: true,
                containInline: true,
                hoverDelay: 0,
                touchDelay: 0,
                handleTouch: false // Disable on touch UI to protect swipe gesture context
            });
        }
    }
    document.addEventListener('DOMContentLoaded', initImageZoom);
    
    $(document).on('variant-changed', function(e, sku) {
        if (sku.origin_image_url) {
            $('.main-image').attr('data-zoom', sku.origin_image_url);
            $('.mobile-buy-bar img').attr('src', sku.origin_image_url);
        }
        if (sku.price_format) {
            $('.mobile-buy-bar .text-dark').text(sku.price_format);
        }
    });
    
    function toggleWishlist(element) {
        if (typeof config === 'undefined' || !config.isLogin) {
            if (typeof layer !== 'undefined' && typeof urls !== 'undefined' && urls.login) {
                layer.open({
                  type: 2,
                  area: window.innerWidth < 768 ? '94%' : '500px',
                  content: `${urls.login}?iframe=true`
                });
            } else {
                window.location.href = '/login';
            }
            return;
        }

        const id = $(element).attr('data-id');
        const isWishlist = parseInt($(element).attr('data-in-wishlist')) || 0;
        const icon = $(element).find('i.bi');
        const countEl = $('.item a[href*="favorites"] .icon-quantity');
        
        $(element).css('pointer-events', 'none').animate({opacity: 0.5}, 200);

        if (isWishlist === 1) {
            axios.post(urls.favorite_cancel, {product_id: id}).then(res => {
                if (!res || res.success !== true) {
                    inno.msg(res?.message || 'Error occurred. Please login.');
                    return;
                }
                inno.msg(res?.message || 'Removed from wishlist');
                $(element).attr('data-in-wishlist', '0');
                icon.removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                
                if (countEl.length) {
                    let count = parseInt(countEl.text()) || 0;
                    countEl.text(Math.max(0, count - 1));
                }
            }).finally(() => {
                $(element).css('pointer-events', 'auto').animate({opacity: 1}, 200);
            });
        } else {
            axios.post(urls.favorites, {product_id: id}).then(res => {
                if (!res || res.success !== true) {
                    inno.msg(res?.message || 'Error occurred. Please login.');
                    return;
                }
                inno.msg(res?.message || 'Added to wishlist');
                $(element).attr('data-in-wishlist', '1');
                icon.removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                
                if (countEl.length) {
                    let count = parseInt(countEl.text()) || 0;
                    countEl.text(count + 1);
                }
            }).finally(() => {
                $(element).css('pointer-events', 'auto').animate({opacity: 1}, 200);
            });
        }
    }
  </script>
@endpush
