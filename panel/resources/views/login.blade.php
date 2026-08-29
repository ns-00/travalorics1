<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ panel_locale_direction() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{ panel_route('home.index') }}">
  <title>@yield('title', __('panel/login.title'))</title>
  <meta name="keywords" content="@yield('keywords', __('panel/login.keywords'))">
  <meta name="description" content="@yield('description', __('panel/login.description'))">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
  <link rel="stylesheet" href="{{ mix('build/panel/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ mix('build/panel/css/app.css') }}">
  <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ mix('build/panel/js/app.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
    
    body.page-login {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #113329 0%, #1B4D3E 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      position: relative;
      overflow: hidden;
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 450px;
      padding: 2rem;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 24px;
      padding: 3rem 2.5rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-logo-container {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .login-logo-container h2 {
      font-weight: 700;
      color: #F4F1EA;
      font-size: 2.2rem;
      letter-spacing: -0.5px;
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .login-logo-container p {
      color: #D3D3D3;
      font-size: 0.95rem;
    }

    .form-floating > .form-control {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(244, 241, 234, 0.2);
      color: #fff;
      border-radius: 12px;
      height: 60px;
      padding-top: 1.625rem;
      padding-bottom: 0.625rem;
      transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus {
      background: rgba(255, 255, 255, 0.1);
      border-color: #F4F1EA;
      box-shadow: 0 0 0 4px rgba(244, 241, 234, 0.15);
    }

    .form-floating > label {
      color: #D3D3D3;
      padding: 1rem 1.25rem;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
      color: #F4F1EA;
      transform: scale(0.85) translateY(-0.75rem) translateX(0.15rem);
    }

    .btn-login {
      background: #F4F1EA;
      border: none;
      border-radius: 12px;
      color: #1B4D3E;
      font-weight: 700;
      font-size: 1.05rem;
      padding: 1rem;
      width: 100%;
      margin-top: 1.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-login::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(to right, rgba(27,77,62,0), rgba(27,77,62,0.1), rgba(27,77,62,0));
      transform: translateX(-100%);
      transition: transform 0.5s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -10px rgba(244, 241, 234, 0.8);
      background: #ffffff;
      color: #113329;
    }

    .btn-login:hover::after {
      transform: translateX(100%);
    }

    .locale-switcher {
      position: absolute;
      top: 2rem;
      right: 2rem;
      z-index: 10;
    }

    .locale-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #F4F1EA;
      border-radius: 30px;
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .locale-btn:hover, .locale-btn[aria-expanded="true"] {
      background: rgba(255, 255, 255, 0.15);
      border-color: #F4F1EA;
    }

    .dropdown-menu-dark-glass {
      background: rgba(17, 51, 41, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
      margin-top: 0.5rem !important;
    }

    .dropdown-menu-dark-glass .dropdown-item {
      color: #e2e8f0;
      transition: all 0.2s;
    }

    .dropdown-menu-dark-glass .dropdown-item:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
    }

    .footer-text {
      text-align: center;
      color: #A9DFD8;
      font-size: 0.85rem;
      margin-top: 2rem;
      text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }
    
    .footer-text a {
      color: #F4F1EA;
      text-decoration: none;
      transition: color 0.2s;
      font-weight: 600;
    }
    
    .footer-text a:hover {
      color: #ffffff;
      text-decoration: underline;
    }

  </style>
  @stack('header')
</head>
<body class="page-login">
  
  <div class="locale-switcher dropdown">
    <button class="locale-btn d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <div class="wh-20 me-2"><img src="{{ image_origin('images/flag/'. panel_locale_code().'.png') }}" class="img-fluid rounded-circle"></div>
      <span class="me-1">{{ current_panel_locale()['name'] }}</span>
      <i class="bi bi-chevron-down ms-1" style="font-size: 0.8rem;"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark-glass py-2">
      @foreach (panel_locales() as $locale)
      <li>
        <a class="dropdown-item d-flex align-items-center py-2" href="{{ panel_route('login.index', ['locale'=> $locale['code']]) }}">
          <div class="wh-20 me-2"><img src="{{ image_origin($locale['image']) }}" class="img-fluid rounded-circle"></div>
          {{ $locale['name'] }}
        </a>
      </li>
      @endforeach
    </ul>
  </div>

  <div class="login-container">
    <div class="glass-card">
        <div class="login-logo-container">
          <h2>{{ __('panel/login.login_index') }}</h2>
          <p>{{ __('panel/login.title') }}</p>
        </div>

        <form action="{{ panel_route('login.store') }}" method="post" autocomplete="off">
          @csrf

          <div class="form-floating mb-4">
            <input type="email" name="email" class="form-control" id="email-input" value="{{ old('email') }}" placeholder="{{ __('common.email') }}" required autocomplete="off">
            <label for="email-input"><i class="bi bi-envelope me-2"></i>{{ __('panel/login.email') }}</label>
            @error('email')
              <div class="invalid-feedback d-block mt-2 px-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
          </div>

          <div class="form-floating mb-4">
            <input type="password" name="password" class="form-control" id="password-input" value="" placeholder="{{ __('shop/login.password') }}" required autocomplete="new-password">
            <label for="password-input"><i class="bi bi-lock me-2"></i>{{ __('panel/login.password') }}</label>
            @error('password')
              <div class="invalid-feedback d-block mt-2 px-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror
          </div>

          @if (session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-white rounded-3">
              <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
          @endif

          <button type="submit" class="btn-login shadow-sm">
            {{ __('panel/common.btn_submit') }} <i class="bi bi-arrow-right-short ms-1 fs-5 align-middle"></i>
          </button>
        </form>
    </div>
    
    <div class="footer-text">
      {!! Travalorics_brand_link() !!}
      <br>{{ Travalorics_version() }} &copy; {{ date('Y') }} All Rights Reserved
    </div>
  </div>

</body>
</html>
