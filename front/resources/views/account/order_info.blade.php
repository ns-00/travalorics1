@extends('layouts.app')
@section('body-class', 'page-order-info')
@section('content')
<x-front-breadcrumb type="order" :value="$order" />
@push('header')
  <style>
    .premium-dashboard-card {
      background: #fff;
      border-radius: 20px;
      border: 1px solid rgba(0, 0, 0, 0.05);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
      padding: 30px;
      margin-bottom: 24px;
    }

    .info-box {
      background: #fcfcfc;
      border-radius: 16px;
      padding: 24px;
      border: 1px solid #f1f1f1;
      height: 100%;
      box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.01);
    }

    .info-box-title {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 16px;
      border-bottom: 1px solid #eee;
      padding-bottom: 12px;
      font-size: 1.1rem;
    }

    .info-box p {
      margin-bottom: 10px;
      color: #6c757d;
      font-size: 0.95rem;
    }

    .info-box p strong {
      color: #495057;
      min-width: 90px;
      display: inline-block;
    }

    .btn-gradient-primary {
      background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
      border: none;
      color: #fff;
      border-radius: 30px;
      padding: 8px 24px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(27, 77, 62, 0.4);
      color: #fff;
    }

    .products-table-info th {
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 1px;
      color: #adb5bd;
      border-bottom: 2px solid #f8f9fa;
      padding-bottom: 12px;
    }

    .products-table-info td {
      vertical-align: middle;
      padding: 16px 8px;
      border-bottom: 1px solid #f8f9fa;
    }

    .product-item {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .product-image {
      width: 70px;
      height: 70px;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #eee;
      flex-shrink: 0;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .product-info .name {
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 4px;
    }

    .order-summary-box {
      background: #f8f9fa;
      border-radius: 16px;
      padding: 20px;
    }
  </style>
@endpush
@hookinsert('account.order_info.top')
<div class="container py-4">
  <div class="row gx-lg-5">
    <div class="col-12 col-lg-3 mb-4 mb-lg-0">
      @include('shared.account-sidebar')
    </div>
    <div class="col-12 col-lg-9">
      <div class="premium-dashboard-card order-info-box">
        <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
          <h4 class="fw-bold mb-0 text-dark" >
            {{ __('front/order.order_details') }}</h4>
          <div class="d-flex align-items-center gap-2">
            <div>
              @if ($order->status == 'unpaid')
                <a href="{{ front_route('orders.pay', ['number' => $order->number]) }}"
                  class="btn btn-gradient-primary btn-sm shadow-sm"><i
                    class="bi bi-credit-card me-1"></i>{{ __('front/order.continue_pay') }}</a>
                <button data-number="{{ $order->number }}"
                  class="btn btn-outline-danger rounded-pill btn-sm btn-canceled fw-bold px-3"><i
                    class="bi bi-x-circle me-1"></i>{{ __('front/account.cancel_order') }}</button>
              @elseif($order->status == 'completed')
                <a href="{{ account_route('order_returns.create', ['order_number' => $order->number]) }}"
                  class="btn btn-outline-secondary rounded-pill btn-sm fw-bold px-3"><i
                    class="bi bi-arrow-return-left me-1"></i>{{ __('front/order.create_rma') }}</a>
              @elseif($order->status == 'shipped')
                <button data-number="{{ $order->number }}"
                  class="btn btn-success btn-sm rounded-pill btn-shipped fw-bold px-3"><i
                    class="bi bi-check-circle me-1"></i>{{ __('front/account.signed') }}</button>
              @endif
            </div>
          </div>
        </div>

        <div class="row mb-5 g-4">
          <div class="col-md-3 col-6">
            <div class="text-muted small text-uppercase mb-1">{{ __('front/order.order_number') }}</div>
            <div class="fw-bold text-dark fs-5">#{{ $order->number }}</div>
          </div>
          <div class="col-md-3 col-6">
            <div class="text-muted small text-uppercase mb-1">{{ __('front/order.order_date') }}</div>
            <div class="fw-bold text-dark">{{ $order->created_at->format('Y-m-d') }}</div>
          </div>
          <div class="col-md-3 col-6">
            <div class="text-muted small text-uppercase mb-1">{{ __('front/order.order_total') }}</div>
            <div class="fw-bold fs-5" style="color: var(--primary);">{{ $order->total_format }}</div>
          </div>
          <div class="col-md-3 col-6">
            <div class="text-muted small text-uppercase mb-1">{{ __('front/order.order_status') }}</div>
            <div><span
                class="badge rounded-pill px-3 py-2 fw-bold bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }}">{{ $order->status_format }}</span>
            </div>
          </div>
        </div>
        <h5 class="fw-bold mb-3 text-dark">{{ __('front/order.product') }}</h5>
        <div class="table-responsive mb-5">
          <table class="table products-table-info table-borderless align-middle">
            <thead>
              <tr>
                <th>{{ __('front/order.product') }}</th>
                <th>{{ __('front/order.price') }}</th>
                <th>{{ __('front/order.quantity') }}</th>
                <th class="text-end">{{ __('front/order.subtotal') }}</th>
                <th class="text-center">{{ __('front/order.operation') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order_items as $product)
              <tr>
                <td>
                  <div class="product-item">
                    <div class="product-image">
                      <img src="{{ $product['image'] }}" class="img-fluid">
                    </div>
                    <div class="product-info">
                      <div class="name" data-bs-toggle="tooltip" title="{{ $product['name'] }}">
                        {{ sub_string($product['name'], 64) }}
                      </div>
                      <div class="sku mt-1 text-muted small">{{ $product['product_sku'] }}
                        @if ($product['variant_label'])
                          - <span class="badge bg-light text-dark border">{{ $product['variant_label'] }}</span>
                        @endif
                        @if ($product['item_type_label'])
                          <span class="badge bg-danger ms-1">{{ $product['item_type_label'] }}</span>
                        @endif
                      </div>
                      @if (!empty($product['options']))
                        <div class="product-options mt-1">
                          @foreach ($product['options'] as $option)
                            <div class="option-item text-muted" style="font-size: 0.8rem;">
                              <strong>{{ $option['option_name'] }}:</strong> {{ $option['option_value_name'] }}
                              @if ($option['price_adjustment'] != 0)
                                <span
                                  class="text-primary">({{ $option['price_adjustment'] > 0 ? '+' : '' }}{{ currency_format($option['price_adjustment']) }})</span>
                              @endif
                            </div>
                          @endforeach
                        </div>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="fw-bold text-dark">{{ $product['price_format'] }}</td>
                <td><span class="badge bg-light text-dark border px-2 py-1">{{ $product['quantity'] }}</span></td>
                <td class="fw-bold text-end" style="color: var(--primary);">{{ $product['subtotal_format'] }}</td>
                <td class="text-center">
                  @php
                    $reviewed = \Travalorics\Common\Repositories\ReviewRepo::orderReviewed(current_customer_id(), $product['id']);
                  @endphp
                  @if ($order->status == 'completed' && !$reviewed && $product['item_type'] === 'normal')
                    @php
                      $prodModel = \Travalorics\Common\Models\Product::find($product['product_id']);
                      $criteriaStr = $prodModel && isset($prodModel->variables['review_criteria']) ? $prodModel->variables['review_criteria'] : '';
                      $criteriaEnStr = $prodModel && isset($prodModel->variables['review_criteria_en']) ? $prodModel->variables['review_criteria_en'] : '';
                    @endphp
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-bold add_review"
                      data-bs-toggle="modal" data-bs-target="#addReview-Modal" data-name="{{ $product['name'] }}"
                      data-image="{{ $product['image'] }}" data-ordernumber="{{ $product['order_number'] }}"
                      data-label="{{ $product['variant_label'] }}" data-orderitemid="{{ $product['id'] }}"
                      data-productsku="{{ $product['product_sku'] }}" 
                      data-criteria="{{ $criteriaStr }}" data-criteria-en="{{ $criteriaEnStr }}"><i
                        class="bi bi-star me-1"></i>{{ __('front/order.add_review') }}
                    </button>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          <div class="row justify-content-end mt-3">
            <div class="col-md-5">
              <div class="order-summary-box">
                @foreach ($order->fees as $total)
                  <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ $total['title'] }}</span>
                    <span class="fw-bold text-dark">{{ $total->value_format }}</span>
                  </div>
                @endforeach
                <div class="d-flex justify-content-between mt-3 pt-3 border-top border-dark">
                  <span class="fw-bold text-dark fs-6">{{ __('front/order.order_total') }}</span>
                  <span class="fw-bold fs-5" style="color: var(--primary);">{{ $order->total_format }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-5 g-4">
          <div class="col-12 col-md-6">
            <div class="info-box">
              <h5 class="info-box-title"><i
                  class="bi bi-geo-alt me-2 text-primary"></i>{{ __('common/address.shipping_address') }}</h5>
              <p><strong>{{ __('common/address.name') }}:</strong> {{ $order->shipping_customer_name }}</p>
              <p><strong>{{ __('common/address.phone') }}:</strong> {{ $order->shipping_telephone }}</p>
              <p><strong>{{ __('common/address.zipcode') }}:</strong> <span
                  class="badge bg-light text-dark border">{{ $order->shipping_zipcode }}</span></p>
              <p><strong>{{ __('common/address.address_1') }}:</strong> {{ $order->shipping_address_1 }}</p>
              @if ($order->shipping_address_2)
                <p><strong>{{ __('common/address.address_2') }}:</strong> {{ $order->shipping_address_2 }}</p>
              @endif
              <p class="mb-0"><strong>{{ __('common/address.region') }}:</strong> {{ $order->shipping_city }},
                {{ $order->shipping_state }}, {{ $order->shipping_country }}</p>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="info-box">
              <h5 class="info-box-title"><i
                  class="bi bi-receipt me-2 text-primary"></i>{{ __('common/address.billing_address') }}</h5>
              <p><strong>{{ __('common/address.name') }}:</strong> {{ $order->billing_customer_name }}</p>
              <p><strong>{{ __('common/address.phone') }}:</strong> {{ $order->billing_telephone }}</p>
              <p><strong>{{ __('common/address.zipcode') }}:</strong> <span
                  class="badge bg-light text-dark border">{{ $order->billing_zipcode }}</span></p>
              <p><strong>{{ __('common/address.address_1') }}:</strong> {{ $order->billing_address_1 }}</p>
              @if ($order->billing_address_2)
                <p><strong>{{ __('common/address.address_2') }}:</strong> {{ $order->billing_address_2 }} </p>
              @endif
              <p class="mb-0"><strong>{{ __('common/address.region') }}:</strong> {{ $order->billing_city }},
                {{ $order->billing_state }}, {{ $order->billing_country }}</p>
            </div>
          </div>
        </div>
        @if ($order->comment)
          <div class="mb-5">
            <h5 class="fw-bold mb-3 text-dark"><i
                class="bi bi-chat-left-text me-2 text-primary"></i>{{ __('front/checkout.order_comment') }}</h5>
            <div class="p-3 bg-light rounded-3 border">
              <span class="text-dark">{{ $order->comment }}</span>
            </div>
          </div>
        @endif

        <div class="mb-5">
          <h5 class="fw-bold mb-3 text-dark"><i
              class="bi bi-truck me-2 text-primary"></i>{{ __('front/order.logistics_info') }}</h5>
          <div class="table-responsive">
            <table class="table products-table-info table-borderless">
              <thead>
                <tr>
                  <th>{{ __('front/order.express_code') }}</th>
                  <th>{{ __('front/order.express_company') }}</th>
                  <th>{{ __('front/order.express_number') }}</th>
                  <th>{{ __('front/order.time') }}</th>
                  <th>{{ __('front/order.shipment_info') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->shipments as $shipment)
                  <tr class="align-middle">
                    <td class="fw-bold">{{ $shipment->express_code }}</td>
                    <td>{{ $shipment->express_company }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $shipment->express_number }}</span></td>
                    <td class="text-muted">{{ $shipment->created_at }}</td>
                    <td>
                      <button data-id="{{ $shipment->id }}" type="button"
                        class="btn btn-outline-primary btn-sm rounded-pill fw-bold"
                        id="view-shipment-{{ $shipment->id }}">
                        <i class="bi bi-eye me-1"></i>{{ __('front/order.view') }}
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @foreach ($order->shipments as $shipment)
          @if(strtolower($shipment->express_code) == 'dhl' || strtolower($shipment->express_company) == 'dhl')
            @include('dhl::tracking', ['trackingNumber' => $shipment->express_number])
          @endif
        @endforeach

        <div>
          <h5 class="fw-bold mb-3 text-dark"><i
              class="bi bi-clock-history me-2 text-primary"></i>{{ __('front/order.order_history') }}</h5>
          <div class="table-responsive">
            <table class="table products-table-info table-borderless">
              <thead>
                <tr>
                  <th>{{ __('front/order.order_status') }}</th>
                  <th>{{ __('front/order.remark') }}</th>
                  <th class="text-end">{{ __('front/order.order_date') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->histories as $history)
                  <tr>
                    <td class="fw-bold text-dark">{{ $history->status }}</td>
                    <td class="text-muted">{{ $history->comment }}</td>
                    <td class="text-end text-muted small">{{ $history->created_at }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-lg" id="addReview-Modal" tabindex="-1" aria-labelledby="addReview-Modal-Label"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header border-bottom-0 bg-light">
          <h1 class="modal-title fs-5 fw-bold text-dark" id="addReview-Modal-Label">{{ __('front/order.add_review') }}
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @include('shared.review')
      </div>
    </div>
  </div>

  <div class="modal fade" id="newShipmentModal" tabindex="-1" aria-labelledby="newShipmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header border-bottom-0 bg-light">
          <h5 class="modal-title fw-bold text-dark" id="newShipmentModalLabel">{{ __('front/order.shipment_info') }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <table class="table table-borderless align-middle">
            <thead class="text-muted small text-uppercase border-bottom">
              <tr>
                <th class="col-3 pb-3">{{ __('front/order.time') }}</th>
                <th class="col-9 pb-3">{{ __('front/order.shipment_info') }}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold"
            data-bs-dismiss="modal">{{ 'front/order.confirm' }}</button>
        </div>
      </div>
    </div>
  </div>

  @hookinsert('account.order_info.bottom')
  @endsection
  @push('footer')
    <script>
      $(document).ready(function () {
        const reviewModal = document.getElementById('addReview-Modal')
        // Handle dynamic review criteria for order modal
        if (reviewModal) {
          reviewModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            const orderNumber = button.getAttribute('data-ordernumber')
            const productImage = button.getAttribute('data-image')
            const productName = button.getAttribute('data-name')
            const productLabel = button.getAttribute('data-label')
            const productItemId = button.getAttribute('data-orderitemid')
            const productSku = button.getAttribute('data-productsku')
            const criteriaStr = button.getAttribute('data-criteria')
            const criteriaEnStr = button.getAttribute('data-criteria-en')

            $('#order_number').text(orderNumber)
            $('#product-image').attr('src', productImage)
            $('#name').text(productName)
            $('#label').text(productLabel)
            $('input[name="order_number"]').val(orderNumber)
            $('input[name="order_item_id"]').val(productItemId)
            $('input[name="product_sku"]').val(productSku)

            // Render dynamic criteria
            const container = document.getElementById('dynamic-attributes-container');
            if (container) {
              container.innerHTML = ''; // Clear existing
              let criteriaList = [];
              let criteriaEnList = [];
              if (criteriaStr) {
                criteriaList = criteriaStr.split(',').map(s => s.trim());
                if (criteriaEnStr) {
                  criteriaEnList = criteriaEnStr.split(',').map(s => s.trim());
                }
              } else {
                criteriaList = [
                  '{{ __('front/product.roast_level') }}',
                  '{{ __('front/product.aroma') }}',
                  '{{ __('front/product.acidity') }}',
                  '{{ __('front/product.body') }}',
                  '{{ __('front/product.flavor') }}'
                ];
              }

              let html = '';
              const locale = '{{ app()->getLocale() }}';
              criteriaList.forEach((label, index) => {
                // simple hash for id
                let key = 'attr_' + Math.random().toString(36).substring(7);
                let displayLabel = label;
                if (locale === 'en' && criteriaEnList[index]) {
                  displayLabel = criteriaEnList[index];
                }
                html += `
                  <div class="col-md-6 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                      <span class="text-dark" style="font-size: 0.85rem; font-weight: 500;">${displayLabel}</span>
                      <input type="hidden" name="attribute_labels[]" value="${label}">
                      <div class="rating-mini d-flex flex-row-reverse" style="font-size: 1.1rem; gap: 2px;">
                        <input type="radio" id="${key}_5" name="attribute_ratings[${label}]" value="5" class="d-none" />
                        <label for="${key}_5" class="star" style="cursor:pointer; color:#ccc;">★</label>
                        <input type="radio" id="${key}_4" name="attribute_ratings[${label}]" value="4" class="d-none" />
                        <label for="${key}_4" class="star" style="cursor:pointer; color:#ccc;">★</label>
                        <input type="radio" id="${key}_3" name="attribute_ratings[${label}]" value="3" class="d-none" checked />
                        <label for="${key}_3" class="star" style="cursor:pointer; color:#ccc;">★</label>
                        <input type="radio" id="${key}_2" name="attribute_ratings[${label}]" value="2" class="d-none" />
                        <label for="${key}_2" class="star" style="cursor:pointer; color:#ccc;">★</label>
                        <input type="radio" id="${key}_1" name="attribute_ratings[${label}]" value="1" class="d-none" />
                        <label for="${key}_1" class="star" style="cursor:pointer; color:#ccc;">★</label>
                      </div>
                    </div>
                  </div>
                `;
              });
              container.innerHTML = html;
            }
          })
        }
        
        // View shipment details
        $(document).on('click', '[id^="view-shipment-"]', function () {
          const shipmentId = $(this).data('id')
          axios.get(`${urls.api_base}/panel/shipments/${shipmentId}/traces`)
            .then(response => {
              const traces = response.data.traces
              const tbody = $('#newShipmentModal .modal-body table tbody').last()
              tbody.empty()
              traces.forEach(trace => {
                tbody.append(`
                  <tr>
                    <td class="text-muted small">${trace.time}</td>
                    <td class="fw-bold text-dark">${trace.station}</td>
                  </tr>
                `)
              })
              $('#newShipmentModal').modal('show')
            })
        })

        // Mark order as shipped
        $(document).on('click', '.btn-shipped', function () {
          const orderNumber = $(this).data('number')
          axios.post(`${urls.api_base}/orders/${orderNumber}/complete`, {
            number: orderNumber
          })
            .then(() => {
              inno.msg(__('front/account.signed_success'))
              window.location.reload()
            })
            .catch(() => inno.msg(__('front/account.signed_failed')))
        })

        // Cancel order
        $(document).on('click', '.btn-canceled', function () {
          const orderNumber = $(this).data('number')
          inno.confirm('{{ __('front/account.cancel_order_confirm') }}', {
            icon: 5,
            title: '{{ __('front/account.tip') }}',
            btn: [
              '{{ __('front/account.cancel_order_confirm_title') }}',
              '{{ __('front/account.cancel_order_confirm_btn_close') }}'
            ],
            offset: 'auto',
            area: ['400px', 'auto'],
            shade: [0.3, "#fff"]
          }, function (index) {
            layer.close(index)
            layer.load(2, {
              shade: [0.3, "#fff"]
            })
            axios.post(`${urls.api_base}/orders/${orderNumber}/cancel`, {
              number: orderNumber
            })
              .then(() => {
                inno.msg("{{ __('front/account.cancel_order_success') }}")
                window.location.reload()
              })
              .catch(() => inno.msg("{{ __('front/account.cancel_order_failed') }}"))
              .finally(() => layer.closeAll('loading'))
          })
        })
      })
    </script>
  @endpush
