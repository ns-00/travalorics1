@extends('layouts.app')
@section('body-class', 'page-cart')
@section('content')
  @push('header')
    <script src="{{ asset('vendor/vue/3.5/vue.global' . (!config('app.debug') ? '.prod' : '') . '.js') }}"></script>
    <style>
      .premium-cart-box {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        border-radius: 20px;
      }

      .products-table th {
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #768088;
        border-bottom: 2px solid #f1f1f1;
      }

      .products-table td {
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
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

      .quantity-wrap {
        background: #f8f9fa;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        padding: 4px;
        border: 1px solid #eee;
      }

      .quantity-wrap .form-control {
        background: transparent;
        border: none;
        width: 45px;
        text-align: center;
        font-weight: bold;
        padding: 0;
      }

      .quantity-wrap .plus,
      .quantity-wrap .minus {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        color: var(--dark);
        transition: all 0.2s;
      }

      .quantity-wrap .plus:hover,
      .quantity-wrap .minus:hover {
        background: var(--primary);
        color: #fff;
      }

      [dir="rtl"] .products-table th {
        letter-spacing: normal;
      }

      /* Force Global Fonts Override (Fix for Arabic) */
      [dir="rtl"] body.page-cart, [dir="rtl"] .page-cart * { font-family: 'Cairo', sans-serif !important; letter-spacing: normal !important; }
      body.page-cart, .page-cart * { font-family: 'Inter', sans-serif !important; }
    </style>
  @endpush
  <x-front-breadcrumb type="route" value="carts.index" title="{{ __('front/cart.cart') }}" />
  @hookinsert('cart.top')
  <div class="container py-4">
    @if (session()->has('errors'))
      <x-common-alert type="danger" msg="{{ session('errors')->first() }}" class="mt-4" />
    @endif
    @if (session('error'))
      <x-common-alert type="danger" msg="{{ session('error') }}" class="mt-4" />
    @endif
    @if (session('success'))
      <x-common-alert type="success" msg="{{ session('success') }}" class="mt-4" />
    @endif
    <div id="app-cart" v-cloak>
      <div class="row gx-lg-5" v-if="list.length">

        <!-- Cart Items Section -->
        <div class="col-12 col-lg-8">
          <div class="premium-cart-box p-4 mb-4">
            <h2 class="fw-bold mb-4 text-dark">{{ __('front/cart.cart') }}</h2>
            @hookinsert('cart.table.before')
            <div class="table-responsive">
              <table class="table products-table align-middle">
                <thead>
                  <tr>
                    <th scope="col" style="width: 40px;">
                      <input class="form-check-input product-all-check shadow-sm" type="checkbox" :checked="allSelected"
                        @change="toggleAllSelection">
                    </th>
                    <th scope="col">{{ __('front/cart.product') }}</th>
                    <th scope="col" class="d-none d-md-table-cell">{{ __('front/cart.price') }}</th>
                    <th scope="col" class="text-center">{{ __('front/cart.quantity') }}</th>
                    <th scope="col" class="text-end">{{ __('front/cart.subtotal') }}</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in list" :key="item.id" :data-id="item.id">
                    <td class="td-product-check">
                      <input class="form-check-input product-item-check shadow-sm" :value="item.id" type="checkbox"
                        :checked="item.selected" @change="updateSelection($event.target.checked, [item.id])"
                        :disabled="item.item_type !== 'normal'">
                    </td>
                    <td class="td-product-info py-3">
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-image rounded-3 overflow-hidden shadow-sm"
                          style="width: 80px; height: 80px; flex-shrink:0;">
                          <img :src="item.image" class="img-fluid w-100 h-100"
                            style="object-fit: cover; background: #fdfdfd;">
                        </div>
                        <div class="product-info">
                          <a :href="item.url" class="fw-bold text-dark text-decoration-none fs-6">@{{ item.product_name
                            }}</a>
                          <div class="text-muted small mt-1">
                            @{{ item.sku_code }}
                            <template v-if="item.variant_label">
                              - <span class="badge bg-light border text-dark">@{{ item.variant_label }}</span>
                            </template>
                            <template v-if="item.options && item.options.length">
                              <div class="product-options mt-2">
                                <div v-for="option in item.options" :key="option.option_id" class="option-item">
                                  <span class="option-name text-muted">@{{ option.option_name }}:</span>
                                  <span class="option-value fw-bold">@{{ option.option_value_name }}</span>
                                  <span v-if="option.price_adjustment != 0" class="price-adjustment text-success">
                                    (@{{ option.price_adjustment_format }})
                                  </span>
                                </div>
                              </div>
                            </template>
                            <span v-if="!item.is_stock_enough" class="badge bg-danger ms-2">
                              {{ __('front/common.stock_not_enough') }}
                            </span>
                            <span v-if="item.item_type_label" class="badge bg-danger ms-2">
                              @{{ item.item_type_label }}
                            </span>
                          </div>
                          <div class="mb-price mt-2 fw-bold d-md-none" style="color: var(--primary);">@{{
                            item.price_format }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="td-price fw-bold text-dark d-none d-md-table-cell fs-6">@{{ item.price_format }}</td>
                    <td class="td-quantity text-center">
                      <div class="quantity-wrap" v-if="item.item_type === 'normal'">
                        <div class="minus" @click="updateQuantity(item.id, item.quantity - 1)">
                          <i class="bi bi-dash-lg"></i>
                        </div>
                        <input type="number" class="form-control" v-model.number="item.quantity"
                          @change="updateQuantity(item.id, item.quantity)">
                        <div class="plus" @click="updateQuantity(item.id, item.quantity + 1)">
                          <i class="bi bi-plus-lg"></i>
                        </div>
                      </div>
                      <div v-else class="fw-bold">@{{ item.quantity }}</div>
                    </td>
                    <td class="td-subtotal fw-bold text-end fs-5" style="color: var(--primary);">@{{ item.subtotal_format
                      }}</td>
                    <td class="td-delete text-end">
                      <div class="delete-cart text-danger fs-5 cursor-pointer opacity-75 hover-opacity-100"
                        v-if="item.item_type === 'normal'" @click="deleteItem(item.id)" style="transition: opacity 0.2s;">
                        <i class="bi bi-trash3-fill"></i>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            @hookinsert('cart.table.after')
          </div>
        </div>
        <!-- Order Summary Section -->
        <div class="col-12 col-lg-4">
          <div class="cart-data premium-cart-box p-4 sticky-top d-none d-lg-block" style="top: 100px;">
            <h4 class="fw-bold mb-4 pb-3 border-bottom text-dark">{{ __('front/cart.cart_total') }}</h4>
            <ul class="list-unstyled mb-4">
              <li class="d-flex justify-content-between mb-3 text-muted">
                <span>{{ __('front/cart.selected') }}</span>
                <span class="total-total fw-bold text-dark">@{{ total }}</span>
              </li>
              <li class="d-flex justify-content-between pt-4 border-top mt-2">
                <span class="fw-bold text-dark">{{ __('front/cart.total') }}</span>
                <span class="total-amount fs-3 fw-bold" style="color: var(--primary);">@{{ amount_format }}</span>
              </li>
            </ul>

            @if(!system_setting('disable_online_order'))
              <button
                class="btn btn-gradient-primary rounded-pill btn-lg w-100 to-checkout shadow-sm d-flex justify-content-center align-items-center gap-2"
                :disabled="!selectedItems.length || hasStockNotEnough" @click="goToCheckout" style="height: 55px;">
                <i class="bi bi-lightning-charge-fill text-warning"></i> {{ __('front/cart.go_checkout') }}
              </button>
            @endif
          </div>

          <!-- Mobile Sticky Checkout Bar -->
          <div
            class="mobile-cart-bar d-lg-none position-fixed bottom-0 start-0 w-100 bg-white p-3 border-top shadow-lg d-flex align-items-center justify-content-between"
            style="z-index: 1040; padding-bottom: env(safe-area-inset-bottom);">
            <div>
              <small class="text-muted fw-bold d-block mb-1">{{ __('front/cart.total') }} (@{{ total }}
                {{ __('front/cart.selected') }})</small>
              <div class="total-amount fs-4 fw-bold" style="color: var(--primary); line-height: 1;">@{{ amount_format }}
              </div>
            </div>
            @if(!system_setting('disable_online_order'))
              <button class="btn btn-gradient-primary rounded-pill flex-shrink-0 px-4 py-2 to-checkout shadow-sm"
                :disabled="!selectedItems.length || hasStockNotEnough" @click="goToCheckout">
                {{ __('front/cart.go_checkout') }}
              </button>
            @endif
          </div>
        </div>
      </div>

      <!-- Empty Cart State -->
      <div v-else class="text-center py-5 my-5 premium-cart-box p-5" data-aos="fade-up">
        <img src="{{ asset('images/icons/empty-cart.svg') }}" class="img-fluid mb-4"
          style="max-width: 180px; opacity: 0.8;">
        <h2 class="fw-bold text-dark mb-3">{{ __('front/cart.empty_cart') }}</h2>
        <p class="text-muted mb-4 fs-5">{{ __('front/cart.no_items_yet') }}</p>
        <a class="btn btn-gradient-primary rounded-pill px-5 py-3 shadow-sm fw-bold"
          href="{{ front_route('home.index') }}">{{ __('front/cart.continue') }}</a>
      </div>
    </div>
  </div>
  @hookinsert('cart.bottom')
@endsection
@push('footer')
  <script>
    const { createApp, ref, computed } = Vue
    createApp({
      setup() {
        const list = ref(@json($list))
        const total = ref(@json($total))
        const amount_format = ref(@json($amount_format))
        const allSelected = computed(() => {
          const normalItems = list.value.filter(item => item.item_type === 'normal')
          return normalItems.length > 0 && normalItems.every(item => item.selected)
        })
        const selectedItems = computed(() => {
          return list.value.filter(item => item.selected && item.item_type === 'normal')
        })
        const hasStockNotEnough = computed(() => selectedItems.value.some(item => !item.is_stock_enough));
        const updateCartState = (data) => {
          list.value = data.list
          total.value = data.total_format
          amount_format.value = data.amount_format
          $('.header-cart-icon .icon-quantity').text(data.total_format)
        }
        const updateQuantity = async (id, quantity) => {
          const item = list.value.find(item => item.id === id)
          if (!item || item.item_type !== 'normal' || quantity < 1) return
          try {
            const res = await axios.put(`${urls.cart_add}/${id}`, { quantity })
            if (res.success) {
              updateCartState(res.data)
            }
          } catch (error) {
            console.error('Failed to update quantity:', error)
          }
        }
        const updateSelection = async (selected, ids) => {
          const normalIds = ids.filter(id => {
            const item = list.value.find(item => item.id === id)
            return item && item.item_type === 'normal'
          })
          if (!normalIds.length) return
          try {
            const res = await axios.post(`${urls.cart_add}/${selected ? 'select' : 'unselect'}`, {
              cart_ids: normalIds
            })
            if (res.success) {
              updateCartState(res.data)
            }
          } catch (error) {
            console.error('Failed to update selection:', error)
          }
        }
        const toggleAllSelection = () => {
          const normalIds = list.value
            .filter(item => item.item_type === 'normal')
            .map(item => item.id)
          updateSelection(!allSelected.value, normalIds)
        }
        const deleteItem = async (id) => {
          const item = list.value.find(item => item.id === id)
          if (!item || item.item_type !== 'normal') return
          try {
            const res = await axios.delete(`${urls.cart_add}/${id}`)
            if (res.success) {
              if (list.value.length === 1) {
                window.location.reload()
                return
              }
              updateCartState(res.data)
            }
          } catch (error) {
            console.error('Failed to delete item:', error)
          }
        }
        const goToCheckout = () => {
          if (!selectedItems.value.length) {
            inno.msg('{{ __('front/cart.go_checkout') }}');
            return
          }
          window.location.href = urls.checkout
        }
        return {
          list,
          total,
          amount_format,
          allSelected,
          selectedItems,
          updateQuantity,
          updateSelection,
          toggleAllSelection,
          deleteItem,
          goToCheckout
        }
      }
    }).mount('#app-cart')
  </script>
@endpush