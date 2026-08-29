@extends('layouts.app')
@section('body-class', 'page-checkout')
@section('content')
  @push('header')
    <script src="{{ asset('vendor/vue/3.5/vue.global' . (!config('app.debug') ? '.prod' : '') . '.js') }}"></script>
    <style>
      .premium-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.03);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
      }

      .checkout-item .title-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .checkout-item .title {
        font-weight: bold;
        font-size: 1.15rem;
        color: var(--dark);
      }

      .select-item {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        overflow: hidden;
      }

      .payment-icon-img {
        max-height: 30px;
        width: auto;
        max-width: 23%;
        object-fit: contain;
      }

      .select-item:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      }

      .select-item.active {
        border-color: var(--primary);
        background: rgba(var(--primary-rgb), 0.02);
        box-shadow: 0 0 0 1px var(--primary);
      }

      .select-item .left {
        display: flex;
        align-items: flex-start;
        gap: 12px;
      }

      .select-item .bi-circle {
        font-size: 1.2rem;
        color: #ddd;
        margin-top: 2px;
        transition: all 0.2s ease;
      }

      .select-item.active .bi-circle::before {
        content: "\f26a";
        /* bi-check-circle-fill */
        color: var(--primary);
      }

      .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        border: none;
        color: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(27, 77, 62, 0.4);
        color: #fff;
      }

      .address-info {
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.5;
        margin-top: 4px;
      }

      .edit-address {
        font-size: 0.85rem;
        font-weight: bold;
        color: var(--primary);
        transition: color 0.2s;
      }

      .edit-address:hover {
        color: #2C6E58;
      }

      /* Force Global Fonts Override (Fix for Arabic) */
      [dir="rtl"] body.page-checkout, [dir="rtl"] .page-checkout * { font-family: 'Cairo', sans-serif !important; letter-spacing: normal !important; }
      body.page-checkout, .page-checkout * { font-family: 'Inter', sans-serif !important; }
      
      /* Premium Background & Glass Effect */
      body.page-checkout {
        background: linear-gradient(135deg, #fdfdfd 0%, #f4f6f8 100%);
      }
      .premium-box {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        border-radius: 20px;
        transition: transform 0.3s ease;
      }
      .premium-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 45px rgba(0, 0, 0, 0.06);
      }
    </style>
  @endpush
  <x-front-breadcrumb type="route" value="checkout.index" title="{{ __('front/checkout.checkout') }}" />
  @hookinsert('checkout.top')
  <div class="container checkout-container h-min-600 py-4">
    <div class="row gx-lg-5" id="app-checkout" v-cloak>
      <div class="col-12 col-md-7">
        <div class="checkout-info">
          <div class="address-box premium-box">
            <div class="checkout-item" v-if="!source.addressEdit">
              <div class="addresses-wrap">
                <div class="shipping-address">
                  <div class="title-wrap">
                    <div class="title">
                      <i class="bi bi-geo-alt-fill text-primary me-2"></i>{{ __('front/checkout.shipping_address') }}
                    </div>
                    <div>
                      <span class="cursor-pointer text-primary fw-bold" v-if="!source.addressEdit"
                        @click="addressEdit(true)"><i
                          class="bi bi-plus-lg me-1"></i>{{ __('front/checkout.create_address') }}</span>
                    </div>
                  </div>
                  <div class="checkout-select-wrap address-select" v-if="source.addresses.length && !source.addressEdit">
                    <div :class="['select-item', current.shipping_address_id == address.id ? 'active' : '']"
                      v-for="address, index in source.addresses" :key="address.id"
                      @click="updateShippingAddress(address.id)">
                      <div class="left">
                        <i class="bi bi-circle"></i>
                        <div class="select-title">
                          <div class="address-name mb-1 fw-bold text-dark">@{{ address.name }} <span
                              class="text-muted fw-normal ms-2">@{{ address.phone }}</span></div>
                          <div class="address-info">@{{ address.address_1 }} @{{ address.address_2 }}<br>@{{ address.city
                            }}
                            @{{ address.state }} @{{ address.country_name }} <span
                              class="badge bg-light text-dark border">@{{ address.zipcode }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="edit-address text-decoration-underline cursor-pointer" @click.stop="editAddress(index)">
                        {{ __('front/common.edit') }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="checkout-item mt-4 pt-4 border-top" v-if="!source.addressEdit">
              <div class="addresses-wrap">
                <div class="shipping-address">
                  <div class="title-wrap border-0 mb-3">
                    <div class="title"><i
                        class="bi bi-receipt-cutoff text-primary me-2"></i>{{ __('front/checkout.billing_address') }}
                    </div>
                    <div>
                      <label class="form-check-label d-flex align-items-center gap-2 cursor-pointer fw-bold text-muted">
                        <input class="form-check-input mt-0 shadow-sm" style="width:1.2rem; height:1.2rem;"
                          type="checkbox" v-model="source.same_as_shipping_address">
                        {{ __('front/checkout.same_shipping_address') }}
                      </label>
                    </div>
                  </div>
                  <div v-if="!source.same_as_shipping_address">
                    <div class="checkout-select-wrap address-select"
                      v-if="source.addresses.length && !source.addressEdit">
                      <div :class="['select-item', current.billing_address_id == address.id ? 'active' : '']"
                        v-for="address, index in source.addresses" :key="address.id"
                        @click="updateCheckout('billing_address_id', address.id)">
                        <div class="left">
                          <i class="bi bi-circle"></i>
                          <div class="select-title">
                            <div class="address-name mb-1 fw-bold text-dark">@{{ address.name }} <span
                                class="text-muted fw-normal ms-2">@{{ address.phone }}</span></div>
                            <div class="address-info">@{{ address.address_1 }} @{{ address.address_2 }}<br>@{{
                              address.city }}
                              @{{ address.state }} @{{ address.country_name }}
                            </div>
                          </div>
                        </div>
                        <div class="edit-address text-decoration-underline cursor-pointer"
                          @click.stop="editAddress(index)">
                          {{ __('front/common.edit') }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-show="source.addressEdit">
              <div class="checkout-item">
                <div class="title-wrap">
                  <div class="title">{{ __('front/checkout.create_address') }}</div>
                  @if (!current_customer())
                    <span class="cursor-pointer btn btn-sm btn-outline-primary rounded-pill fw-bold" @click="login"><i
                        class="bi bi-box-arrow-in-right"></i> {{ __('front/common.login') }}</span>
                  @endif
                  <span class="cursor-pointer text-danger fw-bold" v-if="source.addresses.length"
                    @click="addressEdit(false)"><i class="bi bi-x-lg me-1"></i>
                    {{ __('front/checkout.cancel_create') }}</span>
                </div>
                @include('shared.address-form')
              </div>
            </div>
          </div>
          <div class="checkout-item premium-box">
            <div class="title-wrap">
              <div class="title"><i class="bi bi-truck text-primary me-2"></i>{{ __('front/checkout.shipping_methods') }}
              </div>
            </div>
            <div class="checkout-select-wrap">
              <div v-if="!current.shipping_address_id" class="alert alert-warning border-0 shadow-sm rounded-3">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ __('front/checkout.please_create_address') }}
              </div>
              <div v-else>
                <div v-for="item in source.shippingMethods" :key="item.code">
                  <div v-for="quote in item.quotes" :key="quote.code"
                    @click="updateCheckout('shipping_method_code', quote.code)"
                    :class="['select-item', current.shipping_method_code == quote.code ? 'active' : '']">
                    <div class="left w-100">
                      <i class="bi bi-circle"></i>
                      <div class="select-title w-100 d-flex justify-content-between align-items-center">
                        <span class="name fw-bold text-dark"> @{{ quote.name }}</span>
                        <span class="cost fw-bold" style="color: var(--primary);"> @{{ quote.cost_format }}</span>
                      </div>
                    </div>
                    <div class="icon ms-3" v-if="quote.icon"><img :src="quote.icon" class="img-fluid"
                        style="max-height: 30px;"></div>
                  </div>
                </div>
                <div v-if="!source.shippingMethods.length" class="alert alert-warning border-0 shadow-sm rounded-3">
                  <i class="bi bi-exclamation-circle-fill me-2"></i> {{ __('front/checkout.no_shipping_methods') }}
                </div>
              </div>
            </div>
          </div>
          <div class="checkout-item premium-box">
            <div class="title-wrap">
              <div class="title"><i
                  class="bi bi-credit-card-2-front-fill text-primary me-2"></i>{{ __('front/checkout.billing_methods') }}
              </div>
            </div>
            <div class="checkout-select-wrap">
              <div :class="['select-item', current.billing_method_code == item.code ? 'active' : '']"
                v-for="item in source.billingMethods" :key="item.code"
                @click="updateCheckout('billing_method_code', item.code)">
                <div class="left">
                  <i class="bi bi-circle"></i>
                  <div class="select-title fw-bold text-dark">@{{ item.name }}</div>
                </div>
                <div class="icon ms-2 d-flex flex-nowrap gap-1 justify-content-end align-items-center" v-if="item.icon" style="flex: 1; min-width: 0;">
                  <template v-if="Array.isArray(item.icon)">
                    <img v-for="(ic, idx) in item.icon" :key="idx" :src="ic" class="payment-icon-img">
                  </template>
                  <template v-else>
                    <img :src="item.icon" class="payment-icon-img">
                  </template>
                </div>
              </div>
              <div v-if="!source.billingMethods.length" class="alert alert-warning border-0 shadow-sm rounded-3"><i
                  class="bi bi-exclamation-circle-fill me-2"></i> {{ __('front/checkout.no_billing_methods') }}</div>
            </div>
          </div>
          <div class="checkout-item premium-box">
            <div class="title-wrap">
              <div class="title"><i
                  class="bi bi-chat-left-text-fill text-primary me-2"></i>{{ __('front/checkout.order_comment') }}</div>
            </div>
            <div class="checkout-select">
              <textarea class="form-control border-light shadow-sm rounded-3" rows="3" v-model="current.comment"
                placeholder="{{ __('front/checkout.order_comment') }}"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5">
        <div class="checkout-data sticky-top" style="top: 100px; padding-bottom: 25px;">
          <div class="checkout-data-content premium-box mb-0">

            <h4 class="fw-bold mb-4 border-bottom pb-3 text-dark">
              {{ __('front/checkout.order_summary') }}
            </h4>

            <div id="order-summary-live">
              <div class="products-table">
                @hookinsert('checkout.products.before')

                @if (!empty($cart_list))
                  <div class="products-table-wrap">
                    @foreach ($cart_list as $product)
                      <div class="products-table-list d-flex justify-content-between align-items-center border-bottom py-3">
                        <div class="d-flex gap-3 align-items-center">
                          <div class="product-image position-relative" style="width: 65px; height: 65px; flex-shrink: 0;">
                            <img src="{{ $product['image'] }}" class="img-fluid rounded-3 border shadow-sm w-100 h-100"
                              style="object-fit: cover;">
                            <span
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary shadow-sm">
                              {{ $product['quantity'] }}
                            </span>
                          </div>
                          <div class="product-info">
                            <div class="name fw-bold text-dark fs-6">{{ $product['product_name'] }}</div>
                            <div class="sku mt-1 text-muted" style="font-size: 0.8rem;">
                              @if ($product['variant_label'])
                                <span class="badge bg-light text-dark border">{{ $product['variant_label'] }}</span>
                              @endif
                              @if ($product['item_type_label'])
                                <span class="badge bg-danger ms-1">{{ $product['item_type_label'] }}</span>
                              @endif
                            </div>
                          </div>
                        </div>
                        <div class="text-end fw-bold" style="color: var(--primary);">{{ $product['price_format'] }}</div>
                      </div>
                    @endforeach
                  </div>
                @endif

                @hookinsert('checkout.products.after')
              </div>
            </div>

            <!-- Coupon Section -->
            <div class="border-bottom py-3">
              <div class="row">
                <div class="col-12 d-flex align-items-center gap-2">
                  <div class="input-group flex-nowrap shadow-sm rounded-3 overflow-hidden border">
                    <span class="input-group-text bg-light text-muted fw-bold border-0"><i class="bi bi-tag-fill"></i></span>
                    <input type="text" v-model="current.coupon_code" class="form-control py-2 border-0 shadow-none text-uppercase" placeholder="{{ __('front/checkout.coupon_code') }}">
                    <button class="btn btn-dark fw-bold px-4 border-0" type="button" @click="applyCoupon" :disabled="!current.coupon_code && !source.hasCoupon">
                      <span v-if="source.hasCoupon">{{ __('front/checkout.remove') }}</span>
                      <span v-else>{{ __('front/checkout.apply') }}</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            @if (current_customer())
              <div class="border-bottom py-3">
                <div class="row">
                  <div class="col-12 d-flex align-items-center gap-2">
                    <div class="input-group flex-nowrap shadow-sm rounded-3 overflow-hidden border">
                      <span
                        class="input-group-text bg-light text-muted fw-bold border-0">{{ default_currency()->symbol_left }}</span>
                      <input type="text" v-model="current.balance" class="form-control py-2 border-0 shadow-none"
                        placeholder="{{ __('front/transaction.balance_placeholder') }}"
                        aria-label="{{ __('front/transaction.balance') }}" @input="validateInput">
                      <button :class="{
                                        'disabled': parseFloat(current.balance) > source.balanceAmount || parseFloat(current
                                            .balance) >= source.totalAmount || isNaN(parseFloat(current.balance))
                                    }" class="btn btn-dark fw-bold px-4 border-0" type="button" @click="submitBalance"
                        :disabled="parseFloat(current.balance) > source.balanceAmount || parseFloat(current.balance) >= source.totalAmount ||
                                        isNaN(parseFloat(current.balance))" style="cursor: pointer;">
                        {{ __('front/transaction.confirm') }}
                      </button>
                    </div>
                  </div>
                </div>
                <div class="pt-2 d-flex flex-column gap-1">
                  <span class="text-muted" style="font-size: 0.85rem;"><i
                      class="bi bi-wallet2 me-1"></i>{{ __('front/transaction.available_balance') }}: <strong
                      class="text-dark">@{{ source.balanceAmountFormat }}</strong></span>
                  <span class="text-danger fw-bold" style="font-size: 0.85rem;"
                    v-if="parseFloat(current.balance) > source.balanceAmount">{{ __('front/transaction.input_should_balance') }}</span>
                  <span class="text-danger fw-bold" style="font-size: 0.85rem;"
                    v-else-if="parseFloat(current.balance) >= source.totalAmount">{{ __('front/transaction.input_balance_total') }}</span>
                </div>
              </div>
            @endif
            <div class="pt-3">
              <ul class="cart-data-list m-0 p-0" style="list-style: none;">
                <li class="d-flex justify-content-between mb-2 text-muted" v-for="fee in source.feeList" :key="fee.title">
                  <span>@{{ fee.title }}</span><span class="fw-bold text-dark"> @{{ fee.total_format }} </span>
                </li>
                <li class="d-flex justify-content-between mt-4 mb-2 pt-3 border-top border-2">
                  <span class="fw-bold text-dark fs-5 text-uppercase"
                    style="letter-spacing: 0.5px;">{{ __('front/cart.total') }}</span>
                  <span class="fw-bold fs-3" style="color: var(--primary);">@{{ source.totalAmountFormat }}</span>
                </li>
              </ul>
            </div>
            @hookinsert('checkout.confirm.before')
            <button class="btn btn-gradient-primary rounded-pill btn-lg fw-bold w-100 to-checkout mt-4 shadow-sm"
              style="height: 55px;" :disabled="isCheckout" type="button" @click="submitCheckout">
              <i class="bi bi-lock-fill me-2 text-warning"></i>{{ __('front/checkout.place_order') }}
            </button>

            <div class="mt-4 text-center small text-muted">
              <div class="d-flex justify-content-center gap-4 mb-2 fs-6">
                <span class="text-success"><i class="bi bi-shield-check-fill"></i> Secure</span>
                <span class="text-primary"><i class="bi bi-lightning-charge-fill"></i> Fast</span>
              </div>
              <div>🔒 {{ __('front/product.verified') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @hookinsert('checkout.bottom')
@endsection
@push('footer')
  <script>
    const {
      createApp,
      ref,
      reactive,
      onMounted,
      computed
    } = Vue
    const api = {
      address: @json(front_route('addresses.store')),
      checkout: @json(front_route('checkout.index')),
      checkoutConfirm: @json(front_route('checkout.confirm')),
    }
    const checkoutApp = createApp({
      setup() {
        const source = reactive({
          addresses: @json($address_list),
          shippingMethods: @json($shipping_methods),
          billingMethods: @json($billing_methods),
          addressEdit: @json($address_list).length ? false : true,
          same_as_shipping_address: true,
          feeList: @json($fee_list),
          totalAmount: @json($amount),
          totalAmountFormat: @json(currency_format($amount)),
          balanceAmount: @json($balance_amount ?? 0),
          balanceAmountFormat: @json($balance_amount_format ?? '0'),
          hasCoupon: @json(!empty($checkout['coupon_code'])),
        })
        const current = reactive({
          shipping_address_id: @json($checkout['shipping_address_id'] ?? 0),
          billing_address_id: @json($checkout['billing_address_id'] ?? 0),
          shipping_method_code: @json($checkout['shipping_method_code'] ?? ''),
          billing_method_code: @json($checkout['billing_method_code'] ?? ''),
          comment: '',
          balance: 0,
          coupon_code: @json($checkout['coupon_code'] ?? ''),
          reference: {
            balance: Number(@json($checkout['reference']['balance'] ?? 0))
          },
        })
        current.balance = current.reference.balance;
        const isCheckout = computed(() => {
          return !current.shipping_address_id || !current.billing_address_id || !current.shipping_method_code || !
            current.billing_method_code
        })
        editAddress = (index) => {
          source.addressEdit = true
          const address = source.addresses[index]
          getZones(address.country_code, function () {
            $('.address-form').find('input, select').each(function () {
              $(this).val(address[$(this).attr('name')])
            })
          })
        }
        const updateCheckout = (key, value) => {
          current[key] = value;
          if (source.same_as_shipping_address && key === 'shipping_address_id') {
            current.billing_address_id = value;
          }
          axios.put(api.checkout, current).then(function (res) {
            if (res.success) {
              source.feeList = res.data.fee_list;
              source.totalAmount = res.data.amount;
              source.totalAmountFormat = res.data.amount_format;
              source.shippingMethods = res.data.shipping_methods;
            }
          });
        }
        const selectFirstShippingMethod = () => {
          if (source.shippingMethods.length && source.shippingMethods[0].quotes.length) {
            const firstQuote = source.shippingMethods[0].quotes[0];
            current.shipping_method_code = firstQuote.code;
            updateCheckout('shipping_method_code', firstQuote.code);
          }
        }
        const updateShippingAddress = (addressId) => {
          current.shipping_method_code = '';
          updateCheckout('shipping_address_id', addressId);

          axios.put(api.checkout, current).then(function (res) {
            if (res.success) {
              source.shippingMethods = res.data.shipping_methods;
              selectFirstShippingMethod();
            }
          });
        }
        const updateAddress = (params) => {
          const id = parseInt(new URLSearchParams(params).get('id'));
          const url = id ? api.address + '/' + id : api.address;
          const method = id ? 'put' : 'post';
          axios[method](url, params).then(function (res) {
            if (res.success) {
              inno.msg(res.message);

              if (id) {
                const index = source.addresses.findIndex(address => address.id === id);
                source.addresses[index] = res.data;
                updateShippingAddress(id);
              } else {
                source.addresses.push(res.data);
                if (source.addresses.length === 1) {
                  updateShippingAddress(res.data.id);
                }
              }
              source.addressEdit = false;
              clearForm();
            }
          });
        }
        const addressEdit = (status) => {
          source.addressEdit = status
          clearForm()
        }
        const submitCheckout = () => {
          layer.load(2, {
            shade: [0.3, '#fff']
          })
          axios.post(api.checkoutConfirm, current).then(function (res) {
            if (res.success) {
              inno.msg(res.message, {
                time: 1000
              }, function () {
                location.href = inno.getBase() + '/orders/' + res.data.number + '/pay'
              })
            } else {
              layer.closeAll('loading')
              inno.msg(res.message || 'Error occurred', { icon: 2 });
            }
          }).catch(function (err) {
            layer.closeAll('loading')
            if (err.response) {
              alert('Submit order failed with HTTP ' + err.response.status + ': ' + JSON.stringify(err.response.data));
            } else {
              alert('JS Error submitting order: ' + err.message);
            }
          });
        }
        const login = () => {
          inno.openLogin()
        }
        const submitBalance = () => {
          if (parseFloat(current.balance) <= source.balanceAmount && parseFloat(current.balance) < source.totalAmount) {
            axios.put(api.checkout, {
              reference: {
                balance: parseFloat(current.balance)
              }
            }).then(function (res) {
              if (res.success) {
                source.feeList = res.data.fee_list;
                source.totalAmount = res.data.amount;
                source.totalAmountFormat = res.data.amount_format;
              }
            }).catch(function (error) {
              console.error('Error:', error);
            });
          }
        }
        const validateInput = (event) => {
          let value = event.target.value;
          value = value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
          if (value.startsWith('.')) {
            value = value.substring(1);
          }
          if (value !== event.target.value) {
            event.target.value = value;
          }
        }
        const applyCoupon = () => {
          layer.load(2, { shade: [0.3, '#fff'] });
          let codeToApply = source.hasCoupon ? '' : current.coupon_code;
          
          axios.post(inno.getBase() + '/checkout/coupon', { coupon_code: codeToApply })
            .then(res => {
              layer.closeAll('loading');
              if (res.success) {
                source.feeList = res.data.fee_list;
                source.totalAmount = res.data.amount;
                source.totalAmountFormat = res.data.amount_format;
                current.coupon_code = res.data.checkout.coupon_code || '';
                source.hasCoupon = !!current.coupon_code;
                inno.msg(res.message, { icon: 1 });
              } else {
                current.coupon_code = '';
                inno.msg(res.message || 'Invalid coupon', { icon: 2 });
              }
            })
            .catch(err => {
              layer.closeAll('loading');
              inno.msg('Error applying coupon', { icon: 2 });
            });
        }
        return {
          source,
          login,
          current,
          editAddress,
          updateCheckout,
          addressEdit,
          isCheckout,
          updateAddress,
          updateShippingAddress,
          submitCheckout,
          submitBalance,
          validateInput,
          applyCoupon,
        }
      }
    }).mount('#app-checkout')
    function updateAddress(params) {
      checkoutApp.updateAddress(params)
    }
  </script>
@endpush