<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ front_locale_direction() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ front_route('home.index') }}">
  <title>
    @yield('title', system_setting_locale('meta_title', 'Travalorics - 创新的开源电商系统 | 开源独立站系统 | Laravel 12，多语言和多货币支持'))
  </title>
  <meta name="description"
    content="@yield('description', system_setting_locale('meta_description', 'Travalorics是一款创新的开源电子商务平台，基于Laravel 12开发，具有多语言和多货币支持的特性。它采用了基于Hook的强大而灵活的插件架构，为用户提供了丰富的定制和扩展功能。欢迎体验Travalorics，打造属于您自己的电子商务平台！'))">
  <meta name="keywords"
    content="@yield('keywords', system_setting_locale('meta_keywords', 'Travalorics, 创新, 开源, 电商, 跨境电商, 开源独立站, Laravel 12, 多语言, 多货币, Hook, 插件架构, 灵活, 强大'))">
  <meta name="generator" content="Travalorics {{ Travalorics_version() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="api-token" content="{{ session('front_api_token') }}">
  <link rel="shortcut icon" href="{{ image_origin(system_setting('favicon', 'images/favicon.png')) }}">

  <!-- Modern UI Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&family=Inter:wght@400;600;800&display=swap"
    rel="stylesheet">

  <!-- AOS Animations -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    /* 🎨 Global Color System */
    :root {
      --primary: #1B4D3E;
       --primary-light: #2C6E58;
       --primary-dark: #123327;
      /* Coffee */
      --accent: #C89B3C;
      --bg: #F8F5F2;
    }

    body, body * {
      font-family: 'Inter', sans-serif;
      letter-spacing: -0.2px;
    }

    [dir="rtl"] body, [dir="rtl"] body * {
      font-family: 'Cairo', sans-serif;
      line-height: 1.8;
      letter-spacing: normal;
    }
    
    body {
      background-color: var(--bg);
    }

    /* 🌐 Global UI Color Overrides for Header & Footer */
    .header-top .language-switch .btn.dropdown-toggle, .header-top .top-info a { color: #ffffff !important; font-weight: bold; }
    .footer-item-content a { color: #ffffff !important; }
    .copyright-text a { color: #ffffff !important; }
    /* 📱 Global Mobile Responsiveness (Prevents Layout Breaking) */
    @media (max-width: 767px) {
      body, html {
        max-width: 100vw !important;
        overflow-x: hidden !important;
      }
      #appContent {
        width: 100% !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
      }
      /* Make sure all images respect bounds */
      img { max-width: 100%; height: auto; }
      
      /* General container padding fixes for mobile */
      .container {
        padding-left: 15px !important;
        padding-right: 15px !important;
      }
    }
    
    /* 📱 Mobile Responsive Tables for Account Center */
    @media (max-width: 767px) {
      .account-table-box thead { display: none; }
      .account-table-box tr { 
        display: block; 
        margin-bottom: 15px; 
        border: 1px solid rgba(0,0,0,0.05); 
        border-radius: 12px; 
        padding: 10px; 
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
      }
      .account-table-box td { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        border-bottom: 1px solid #f8f9fa; 
        padding: 12px 5px; 
        text-align: right; 
        gap: 10px;
      }
      html[dir="rtl"] .account-table-box td { text-align: left; }
      .account-table-box td:last-child { border-bottom: none; }
      .account-table-box td::before { 
        content: attr(data-title); 
        font-weight: 700; 
        color: #6c757d; 
        font-size: 0.85rem; 
      }
    }
  </style>
  <link rel="stylesheet" href="{{ mix('build/front/css/bootstrap.css') }}">
  <script src="{{ mix('build/front/js/app.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ mix('build/front/css/app.css') }}">
  <script>
    let urls = {
      api_base: '{{ route('api.home.base') }}',
      base_url: '{{ front_route('home.index') }}',
      upload_images: '{{ front_root_route('upload.images') }}',
      cart_add: '{{ front_route('carts.store') }}',
      cart_mini: '{{ front_route('carts.mini') }}',
      cart: '{{ front_route('carts.index') }}',
      checkout: '{{ front_route('checkout.index') }}',
      login: '{{ front_route('login.index') }}',
      favorites: '{{ account_route('favorites.index') }}',
      favorite_cancel: '{{ account_route('favorites.cancel') }}',
    }

    let config = {
      isLogin: !!{{ current_customer()->id ?? 'null' }},
      currency: {
        code: '{{ current_currency_code() }}',
        symbol_left: '{{ default_currency()->symbol_left ?? "$" }}',
        symbol_right: '{{ default_currency()->symbol_right ?? "" }}',
        decimal_place: {{ default_currency()->decimal_place ?? 2 }},
        rate: {{ default_currency()->value ?? 1 }}
      }
    }

    let asset_url = '{{ asset('') }}';
  </script>
  @stack('header')
  @hookinsert('front.layout.app.head.bottom')
</head>

<body class="@yield('body-class')">
    @if (!request('iframe'))
        <x-front-header />
    @endif

    <div class="m-0 p-0" id="appContent">
        @yield('content')
    </div>

    @if (!request('iframe'))
        <x-front-footer />
    @endif

    @if (!request('iframe'))
        @include('components.mini-cart')
    @endif

    <!-- Initialize Core Animations -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            AOS.init({
                duration: 600,
                once: true,
                offset: 50
            });
        });

        // Quick Add To Cart (Grid Views)
        $(document).on('click', '.btn-add-cart', function (e) {
            e.preventDefault();
            let $btn = $(this);
            let skuId = $btn.data('sku-id');
            if (!skuId || typeof inno === 'undefined') return;

            let originalText = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>...');

            inno.addCart({
                skuId: skuId,
                quantity: 1,
                isBuyNow: false,
                options: {}
            }, this, function (res) {
                $btn.html('<i class="bi bi-check2"></i>');
                $btn.addClass('btn-success text-white border-success').removeClass('btn-outline-dark');
                setTimeout(() => {
                    $btn.prop('disabled', false).html(originalText);
                    $btn.removeClass('btn-success text-white border-success').addClass('btn-outline-dark');
                }, 2000);
            });
        });

        // Replace Saudi Riyal Text with Icon instantly
        (function () {
            const replaceCurrencySymbol = (rootNode) => {
                const iconHtml = '<img src="{{ asset('images/sar-icon.png') }}" alt="Riyal" class="sar-icon-img" style="height: 1em; width: auto; vertical-align: -0.15em; margin-inline: 4px; display: inline-block;">';

                const walk = document.createTreeWalker(rootNode, NodeFilter.SHOW_TEXT, null, false);
                let n;
                const nodesToReplace = [];
                const forbiddenTags = ['SCRIPT', 'STYLE', 'OPTION', 'TEXTAREA', 'TITLE', 'NOSCRIPT'];

                while (n = walk.nextNode()) {
                    if (n.nodeValue && n.nodeValue.includes('ر.س')) {
                        let p = n.parentNode;
                        let forbidden = false;
                        while (p && p !== document.body && p !== document.documentElement) {
                            if (forbiddenTags.includes(p.nodeName) || (p.classList && p.classList.contains('sar-price-bdi'))) {
                                forbidden = true;
                                break;
                            }
                            p = p.parentNode;
                        }
                        if (!forbidden) {
                            nodesToReplace.push(n);
                        }
                    }
                }

                nodesToReplace.forEach(node => {
                    const span = document.createElement('span');
                    span.className = 'sar-price-bdi';
                    const priceRegex = /(?:ر\.س\s*([-+]?\s*[\d,]+(?:\.\d+)?)|([-+]?\s*[\d,]+(?:\.\d+)?)\s*ر\.س)/g;

                    let newHtml = node.nodeValue.replace(priceRegex, function (match, num1, num2) {
                        let numberStr = num1 || num2 || '';
                        return '<bdi dir="ltr" style="unicode-bidi: isolate; white-space: nowrap;">' + iconHtml + ' ' + numberStr + '</bdi>';
                    });

                    if (newHtml === node.nodeValue) {
                        newHtml = node.nodeValue.replace(/ر\.س/g, iconHtml);
                    }

                    span.innerHTML = newHtml;
                    node.parentNode.replaceChild(span, node);
                });
            };

            replaceCurrencySymbol(document.body);

            const observer = new MutationObserver((mutations) => {
                let shouldRun = false;
                mutations.forEach(m => {
                    if (m.addedNodes.length > 0) {
                        for (let i = 0; i < m.addedNodes.length; i++) {
                            let node = m.addedNodes[i];
                            if (node.nodeType === 1) {
                                if (!node.classList.contains('sar-price-bdi') && !node.classList.contains('sar-icon-img')) {
                                    shouldRun = true;
                                }
                            } else if (node.nodeType === 3) {
                                if (node.nodeValue.includes('ر.س')) {
                                    shouldRun = true;
                                }
                            }
                        }
                    }
                });
                if (shouldRun) {
                    observer.disconnect();
                    replaceCurrencySymbol(document.body);
                    observer.observe(document.body, { childList: true, subtree: true });
                }
            });
            observer.observe(document.body, { childList: true, subtree: true });
        })();
    </script>

    @stack('footer')
</body>

</html>
