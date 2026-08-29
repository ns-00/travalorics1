<!-- Header Top Bar -->
<div class="header-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-12 top-info">
                <span><i class="bi bi-telephone"></i> {{ setting('phone') ?? '+966 12 345 6789' }}</span>
                <span class="ms-3"><i class="bi bi-envelope"></i> {{ setting('email') ?? 'info@travalorics.com' }}</span>
            </div>
            <div class="col-md-6 col-12 text-md-end text-start">
                <a href="{{ route('login') }}" class="text-white me-3"><i class="bi bi-box-arrow-in-right"></i> تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="text-white"><i class="bi bi-person-plus"></i> حساب جديد</a>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Header -->
<header class="header-desktop d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-4">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Travalorics Coffee">
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-4">
                <nav class="menu d-flex justify-content-center">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('front.home') }}</a>
                    <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">{{ __('front.shop') }}</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">{{ __('front.about') }}</a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('front.contact') }}</a>
                </nav>
            </div>
            <div class="col-lg-3 col-4 d-flex justify-content-end align-items-center gap-3">
                <a href="#" class="header-icon" title="{{ __('front.search') }}">
                    <i class="bi bi-search"></i>
                </a>
                <a href="{{ route('wishlist') }}" class="header-icon" title="{{ __('front.wishlist') }}">
                    <i class="bi bi-heart"></i>
                    <span class="badge">{{ Cart::wishlist()->count() ?? 0 }}</span>
                </a>
                <a href="{{ route('cart') }}" class="header-icon" title="{{ __('front.cart') }}">
                    <i class="bi bi-cart3"></i>
                    <span class="badge">{{ Cart::content()->count() ?? 0 }}</span>
                </a>
                <a href="{{ route('profile') }}" class="header-icon">
                    <i class="bi bi-person-circle"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Header -->
<header class="header-mobile d-block d-lg-none">
    <button class="menu-toggle" id="mobileMenuToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Travalorics Coffee">
        </a>
    </div>
    <div class="cart-icon">
        <a href="{{ route('cart') }}" class="d-flex align-items-center">
            <i class="bi bi-cart3 fs-4"></i>
            <span class="badge">{{ Cart::content()->count() ?? 0 }}</span>
        </a>
    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-header">
        <img src="{{ asset('images/logo.png') }}" alt="Travalorics">
        <button class="close-btn" id="mobileNavClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <ul class="mobile-nav-links">
        <li><a href="{{ route('home') }}"><i class="bi bi-house"></i> {{ __('front.home') }}</a></li>
        <li><a href="{{ route('shop') }}"><i class="bi bi-grid"></i> {{ __('front.shop') }}</a></li>
        <li><a href="{{ route('about') }}"><i class="bi bi-info-circle"></i> {{ __('front.about') }}</a></li>
        <li><a href="{{ route('contact') }}"><i class="bi bi-envelope"></i> {{ __('front.contact') }}</a></li>
        <hr>
        <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> {{ __('front.login') }}</a></li>
        <li><a href="{{ route('register') }}"><i class="bi bi-person-plus"></i> {{ __('front.register') }}</a></li>
    </ul>
</div>

<!-- Overlay -->
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
