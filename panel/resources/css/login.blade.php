<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ panel_locale_direction() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ __('panel/login.title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ mix('build/panel/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ mix('build/panel/css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #111827;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }
        
        .login-brand {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-brand .brand-icon {
            width: 56px;
            height: 56px;
            background: #1B4D3E;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 22px;
            color: #fff;
            margin: 0 auto 12px;
        }
        
        .login-brand h1 {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }
        
        .login-brand h1 span {
            color: #1B4D3E;
        }
        
        .login-brand p {
            color: #6B7280;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: block;
            margin-bottom: 4px;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: #fff;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #1B4D3E;
            box-shadow: 0 0 0 3px rgba(27, 77, 62, 0.08);
        }
        
        .form-group input::placeholder {
            color: #9CA3AF;
        }
        
        .form-error {
            color: #EF4444;
            font-size: 12px;
            margin-top: 3px;
        }
        
        .btn-login {
            width: 100%;
            padding: 11px;
            background: #1B4D3E;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 4px;
        }
        
        .btn-login:hover {
            background: #0F3328;
        }
        
        .btn-login:active {
            transform: scale(0.97);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: #9CA3AF;
            font-size: 12px;
        }
        
        .login-footer a {
            color: #6B7280;
            text-decoration: none;
        }
        
        .login-footer a:hover {
            color: #1B4D3E;
        }
        
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
            padding: 10px 14px;
            color: #991B1B;
            font-size: 13px;
            margin-bottom: 16px;
        }
        
        .locale-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
        }
        
        .locale-btn {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            color: #fff;
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(8px);
        }
        
        .locale-btn:hover {
            background: rgba(255,255,255,0.12);
        }
        
        .dropdown-menu-glass {
            background: #1F2937;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            padding: 4px 0;
            min-width: 130px;
        }
        
        .dropdown-menu-glass .dropdown-item {
            color: #E5E7EB;
            padding: 5px 12px;
            font-size: 12px;
            transition: all 0.2s;
        }
        
        .dropdown-menu-glass .dropdown-item:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }
        
        @media (max-width: 576px) {
            .login-card { padding: 24px 16px; }
            .login-brand h1 { font-size: 17px; }
            .login-brand .brand-icon { width: 40px; height: 40px; font-size: 17px; }
            .locale-switcher { top: 10px; right: 10px; }
            .locale-btn { font-size: 11px; padding: 4px 10px; }
        }
    </style>
</head>
<body>
    <div class="locale-switcher dropdown">
        <button class="locale-btn" data-bs-toggle="dropdown">
            <img src="{{ image_origin('images/flag/'. panel_locale_code().'.png') }}" style="width: 16px; height: 16px; border-radius: 50%;">
            <span>{{ current_panel_locale()['name'] }}</span>
            <i class="bi bi-chevron-down ms-1" style="font-size: 9px;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-glass dropdown-menu-end">
            @foreach (panel_locales() as $locale)
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" 
                   href="{{ panel_route('login.index', ['locale'=> $locale['code']]) }}">
                    <img src="{{ image_origin($locale['image']) }}" style="width: 16px; height: 16px; border-radius: 50%;">
                    {{ $locale['name'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-brand">
                <div class="brand-icon">T</div>
                <h1>Trava<span>lorics</span></h1>
                <p>{{ __('panel/login.title') }}</p>
            </div>

            @if (session('error'))
                <div class="alert-error">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ panel_route('login.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label>{{ __('panel/login.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>{{ __('panel/login.password') }}</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn-login">
                    {{ __('panel/common.btn_submit') }}
                </button>
            </form>

            <div class="login-footer">
                {!! Travalorics_brand_link() !!} {{ Travalorics_version() }}
            </div>
        </div>
    </div>
</body>
</html>
