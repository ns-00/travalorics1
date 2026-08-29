<header class="header-box">
    <div class="d-flex align-items-center">
        <div class="mb-menu" id="sidebarToggle" role="button" tabindex="0" aria-label="Toggle navigation" aria-expanded="false">
            <i class="bi bi-list"></i>
        </div>
        <div class="header-logo">
            <a href="{{ panel_route('home.index') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Travalorics" class="sidebar-logo">
            </a>
        </div>
    </div>
    <div class="right-tool">
        <div class="header-item dropdown panel-locale-menu">
            <button class="header-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                aria-label="{{ current_panel_locale()['name'] ?? 'Language' }}">
                <span class="panel-locale-current">
                    <span class="panel-locale-flag wh-20">
                        <img src="{{ image_origin(current_panel_locale()['image'] ?? 'images/flag/en.png') }}"
                            alt="{{ current_panel_locale()['name'] ?? 'Language' }}">
                    </span>
                    <span class="d-none d-md-inline">{{ current_panel_locale()['name'] ?? 'Language' }}</span>
                </span>
                <i class="bi bi-chevron-down panel-dropdown-chevron" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end panel-locale-dropdown">
                <div class="panel-dropdown-heading">
                    <span class="text-muted small">{{ __('panel/menu.locales') }}</span>
                </div>
                @foreach (panel_locales() as $locale)
                    <a class="dropdown-item {{ panel_locale_code() === $locale['code'] ? 'active' : '' }}"
                        href="{{ panel_route('locale.switch', ['code' => $locale['code']]) }}">
                        <span class="panel-locale-flag wh-20">
                            <img src="{{ image_origin($locale['image']) }}" alt="{{ $locale['name'] }}">
                        </span>
                        <span>{{ $locale['name'] }}</span>
                        @if (panel_locale_code() === $locale['code'])
                            <i class="bi bi-check2 ms-auto" aria-hidden="true"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        <div class="header-item dropdown">
            <button class="header-dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                <i class="bi bi-chevron-down panel-dropdown-chevron" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ panel_route('account.index') }}"><i class="bi bi-person"></i> {{ __('panel/menu.account') }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item panel-logout-link" href="{{ panel_route('logout.index') }}">
                    <i class="bi bi-box-arrow-right"></i> {{ __('panel/common.logout') }}
                </a>
            </div>
        </div>
    </div>
</header>
