@push('header')
  <style>
    .premium-sidebar-box {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(0, 0, 0, 0.05);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
      border-radius: 20px;
      padding: 24px;
    }

    .account-links a {
      color: #495057;
      transition: all 0.3s ease;
    }

    .account-links li.active a {
      background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
      color: #fff !important;
      box-shadow: 0 4px 12px rgba(27, 77, 62, 0.2);
    }

    .account-links li:not(.active) a:hover {
      background: #f8f9fa;
      color: var(--primary) !important;
      transform: translateX(4px);
    }

    [dir="rtl"] .account-links li:not(.active) a:hover {
      transform: translateX(-4px);
    }
  </style>
@endpush
<div class="account-sidebar premium-sidebar-box">
  <div class="account-user text-center mb-4 pb-4 border-bottom">
    <div class="profile mb-3 d-flex justify-content-center">
      <div class="rounded-circle shadow-sm border border-3 border-light bg-white d-flex align-items-center justify-content-center overflow-hidden" style="width: 90px; height: 90px;">
        <img src="{{ image_resize($customer->avatar) }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
      </div>
    </div>
    <div class="account-name">
      <div class="fw-bold name fs-5 text-dark mb-1">{{ __('front/account.hello') }}, {{ $customer->name }}</div>
      <div class="text-muted email" style="font-size: 0.85rem;">{{ $customer->email }}</div>
    </div>
    @hookinsert('front.account.sidebar.avatar.after', $customer)
  </div>
  <ul class="account-links list-unstyled m-0 p-0">
    <li class="mb-2 {{ equal_account_route_name('index') ? 'active' : '' }}">
      <a href="{{ account_route('index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-person fs-5"></i><span>{{ front_trans('account.account') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.home.after')
    <li class="mb-2 {{ equal_account_route_name(['orders.index', 'orders.number_show']) ? 'active' : '' }}">
      <a href="{{ account_route('orders.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-bag-check fs-5"></i><span>{{ front_trans('account.orders') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.orders.after')
    <li class="mb-2 {{ equal_account_route_name('favorites.index') ? 'active' : '' }}">
      <a href="{{ account_route('favorites.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-heart fs-5"></i><span>{{ front_trans('account.favorites') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.favorites.after')
    <li
      class="mb-2 {{ equal_account_route_name(['wallet.index', 'wallet.transactions.index', 'wallet.withdrawals.index', 'wallet.withdrawals.create', 'wallet.withdrawals.show']) ? 'active' : '' }}">
      <a href="{{ account_route('wallet.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-wallet2 fs-5"></i><span>{{ front_trans('account.wallet') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.wallet.after')
    <li class="mb-2 {{ equal_account_route_name('reviews.index') ? 'active' : '' }}">
      <a href="{{ account_route('reviews.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-chat-quote fs-5"></i><span>{{ front_trans('account.reviews') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.reviews.after')
    <li class="mb-2 {{ equal_account_route_name('addresses.index') ? 'active' : '' }}">
      <a href="{{ account_route('addresses.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-geo-alt fs-5"></i><span>{{ front_trans('account.addresses') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.addresses.after')
    <li
      class="mb-2 {{ equal_account_route_name(['order_returns.index', 'order_returns.create', 'order_returns.show']) ? 'active' : '' }}">
      <a href="{{ account_route('order_returns.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-arrow-return-left fs-5"></i><span>{{ front_trans('account.order_returns') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.order_returns.after')
    <li class="mb-2 {{ equal_account_route_name('edit.index') ? 'active' : '' }}">
      <a href="{{ account_route('edit.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-person-lines-fill fs-5"></i><span>{{ front_trans('account.edit') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.edit.after')
    <li class="mb-2 {{ equal_account_route_name('password.index') ? 'active' : '' }}">
      <a href="{{ account_route('password.index') }}"
        class="d-flex align-items-center gap-3 py-3 px-3 rounded-3 text-decoration-none transition-all fw-bold">
        <i class="bi bi-shield-lock fs-5"></i><span>{{ front_trans('account.password') }}</span>
      </a>
    </li>
    @hookinsert('front.account.sidebar.password.after')
    <li class="mt-4 pt-3 border-top">
      <a href="{{ account_route('logout') }}"
        class="d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-danger text-decoration-none transition-all fw-bold hover-bg-light">
        <i class="bi bi-box-arrow-left fs-5"></i><span>{{ front_trans('account.logout') }}</span>
      </a>
    </li>
  </ul>
</div>