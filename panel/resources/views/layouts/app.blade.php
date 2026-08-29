<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ panel_locale_direction() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ panel_route('home.index') }}">
  <title>@yield('title'){{ View::hasSection('title') ? ' - ' : '' }}Travalorics</title>
  <meta name="keywords" content="@yield('keywords', 'Travalorics, 创新, 开源, CMS, Laravel 11, 多语言, 多货币, Hook, 插件架构, 灵活, 强大')">
  <meta name="generator" content="Travalorics {{ Travalorics_version() }}">
  <meta name="asset" content="{{ asset('/') }}">
  <meta name="description" content="@yield('description', 'Travalorics')">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="api-token" content="{{ session('panel_api_token') }}">
  <link rel="shortcut icon" href="{{ image_origin(system_setting('favicon', 'images/favicon.png')) }}">
  <link rel="stylesheet" href="{{ asset('vendor/element-plus/index.css') }}">
  <link rel="stylesheet" href="{{ mix('build/panel/css/bootstrap.css') }}?v=1782941916">
  <link rel="stylesheet" href="{{ mix('build/panel/css/app.css') }}?v=1782941916">
  <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('vendor/vue/3.5/vue.global' . (config('app.debug') ? '' : '.prod') . '.js') }}"></script>
  <script src="{{ asset('vendor/element-plus/index.full.js') }}"></script>
  <script src="{{ asset('vendor/element-plus/icons.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ mix('build/panel/js/app.js') }}"></script>
  
  <!-- Modern UI Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <script>
    let urls = {
      api_base: '{{ route('api.panel.base.index') }}',
      base_url: '{{ panel_route('home.index') }}',
      upload_images: '{{ panel_route('upload.images') }}',
      ai_generate: '{{ panel_route('content_ai.generate') }}',
    }

    const lang = {
      hint: '{{ __('panel/common.hint') }}',
      delete_confirm: '{{ __('panel/common.delete_confirm') }}',
      confirm: '{{ __('panel/common.confirm') }}',
      cancel: '{{ __('panel/common.cancel') }}',
      product_selector: '{{ __('panel/common.product_selector') }}',
    }
  </script>
  @stack('header')
  <!-- CSS in global.scss -->
</head>

<body class="@yield('body-class')">
  @include('panel::layouts.header')
  <div class="main-content">
    <aside class="sidebar-box navbar-expand-xs border-radius-xl">
      <div class="sidebar-body">
        <x-panel-layout-sidebar></x-panel-layout-sidebar>
      </div>
      <div class="mb-menu-close"><i class="bi bi-chevron-left"></i></div>
    </aside>

    <div id="content">
      <div class="page-title-box py-1 d-flex align-items-center justify-content-between">
        <div class="d-flex">
          <h4 class="page-title mb-0">@yield('title')</h4>
          <div class="ms-4 text-danger">@yield('page-title-after')</div>
        </div>
        <div class="text-nowrap">
          @yield('page-title-right')
          @hookinsert('panel.layout.right.button.after')
        </div>
      </div>

      <div class="container-fluid p-0 mt-2">
        <div class="content-info">
          @if (session()->has('errors'))
            <x-common-alert type="danger" msg="{{ session('errors')->first() }}" class="mt-4"/>
          @endif
          @if (session('success'))
            <x-common-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
          @endif
          @if (session('error'))
            <x-common-alert type="danger" msg="{{ session('error') }}" class="mt-4"/>
          @endif
          @yield('content')
        </div>

        <div class="page-bottom-btns">
          @yield('page-bottom-btns')
        </div>

        <p class="text-center text-secondary mt-5">
          {!! Travalorics_brand_link() !!}
          {{ Travalorics_version() }} &copy; {{ date('Y') }} All Rights Reserved
        </p>
      </div>
    </div>
  </div>

  @include('panel::layouts.footer')

  <!-- Replace Omani Rial Text with Icon -->
  <script>
  (function () {
    const replaceOMR = (rootNode) => {
      const omrHtml = '<img src="{{ asset('images/om-icon.png') }}" alt="OMR" class="omr-icon-img" style="height:1em;width:auto;vertical-align:-0.15em;margin-inline:4px;display:inline-block;">';
      const walk = document.createTreeWalker(rootNode, NodeFilter.SHOW_TEXT, null, false);
      let n;
      const nodesToReplace = [];
      const forbiddenTags = ['SCRIPT','STYLE','OPTION','TEXTAREA','TITLE','NOSCRIPT'];
      while (n = walk.nextNode()) {
        if (n.nodeValue && (n.nodeValue.includes('ر.ع') || n.nodeValue.includes(' OMR'))) {
          let p = n.parentNode; let skip = false;
          while (p) { if (forbiddenTags.includes(p.tagName)) { skip=true; break; } p=p.parentNode; }
          if (!skip && !n.parentNode.classList?.contains('omr-icon-img')) nodesToReplace.push(n);
        }
      }
      nodesToReplace.forEach(textNode => {
        const regex = /ر\.ع| OMR/g;
        const parts = textNode.nodeValue.split(regex);
        if (parts.length < 2) return;
        const frag = document.createDocumentFragment();
        parts.forEach((part, i) => {
          if (part) frag.appendChild(document.createTextNode(part));
          if (i < parts.length - 1) {
            const span = document.createElement('span');
            span.className = 'omr-icon-img';
            span.innerHTML = omrHtml;
            frag.appendChild(span);
          }
        });
        textNode.parentNode.replaceChild(frag, textNode);
      });
    };
    document.addEventListener('DOMContentLoaded', () => {
      replaceOMR(document.body);
      const obs = new MutationObserver(muts => muts.forEach(m => m.addedNodes.forEach(n => { if(n.nodeType===1) replaceOMR(n); })));
      obs.observe(document.body, {childList:true, subtree:true});
    });
  })();
  </script>

  <!-- Replace Saudi Riyal Text with Icon instantly -->
  <script>
    (function () {
      const replaceCurrencySymbol = (rootNode) => {
        // Use empty alt or "Riyal" to prevent infinite loops if we search for "SAR"
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
          // Regex to capture the number and the symbol in either order
          const priceRegex = /(?:ر\.س\s*([-+]?\s*[\d,]+(?:\.\d+)?)|([-+]?\s*[\d,]+(?:\.\d+)?)\s*ر\.س)/g;

          let newHtml = node.nodeValue.replace(priceRegex, function (match, num1, num2) {
            let numberStr = num1 || num2 || '';
            // Force LTR direction so the icon is ALWAYS on the left of the number in both EN and AR
            return '<bdi dir="ltr" style="unicode-bidi: isolate; white-space: nowrap;">' + iconHtml + ' ' + numberStr + '</bdi>';
          });

          // If the regex didn't match the number (e.g. isolated symbol), just replace the symbol
          if (newHtml === node.nodeValue) {
            newHtml = node.nodeValue.replace(/ر\.س/g, iconHtml);
          }

          span.innerHTML = newHtml;
          node.parentNode.replaceChild(span, node);
        });
      };

      replaceCurrencySymbol(document.body);

      // Observe DOM for AJAX updates
      const observer = new MutationObserver((mutations) => {
        let shouldRun = false;
        mutations.forEach(m => {
          if (m.addedNodes.length > 0) {
            for (let i = 0; i < m.addedNodes.length; i++) {
              let node = m.addedNodes[i];
              if (node.nodeType === 1) { // Element node
                if (!node.classList.contains('sar-price-bdi') && !node.classList.contains('sar-icon-img')) {
                  shouldRun = true;
                }
              } else if (node.nodeType === 3) { // Text node
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
