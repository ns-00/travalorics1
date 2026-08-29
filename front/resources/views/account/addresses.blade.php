@extends('layouts.app')
@section('body-class', 'page-addresses')
@section('content')
  <x-front-breadcrumb type="route" value="account.addresses.index" title="{{ __('front/account.addresses') }}" />
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

      .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, #2C6E58 100%);
        border: none;
        color: #fff;
        border-radius: 30px;
        padding: 10px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(27, 77, 62, 0.4);
        color: #fff;
      }

      .address-card-premium {
        background: #fcfcfc;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #eee;
        height: 100%;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
      }

      .address-card-premium:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border-color: var(--primary);
        transform: translateY(-4px);
      }

      .address-card-premium.is-default {
        border: 2px solid var(--primary);
        background: #fff;
      }

      .address-card-premium.is-default::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 50px 50px 0;
        border-color: transparent var(--primary) transparent transparent;
        opacity: 0.1;
      }

      [dir="rtl"] .address-card-premium.is-default::before {
        right: auto;
        left: 0;
        border-width: 50px 50px 0 0;
        border-color: var(--primary) transparent transparent transparent;
      }

      .address-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px dashed #e9ecef;
      }

      .address-card-title {
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        font-size: 1.1rem;
      }

      .address-card-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        position: relative;
        z-index: 2;
      }

      .address-card-body p {
        margin-bottom: 8px;
        color: #6c757d;
        font-size: 0.95rem;
      }

      .address-card-body p strong {
        color: #495057;
        min-width: 80px;
        display: inline-block;
      }
    </style>
  @endpush
  @hookinsert('account.addresses.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card addresses-box">
          <div class="account-card-title d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('common/address.address') }}</h4>
            <button type="button" class="btn btn-gradient-primary add-address shadow-sm">
              <i class="bi bi-plus-lg me-1"></i>{{ __('common/address.add_new_address') }}
            </button>
          </div>

          <div class="row g-4">
            @foreach($addresses as $index => $address)
              <div class="col-12 col-md-6">
                <div class="address-card address-card-premium {{ $address['default'] ? 'is-default' : '' }}"
                  data-id="{{ $address['id'] }}">
                  <div class="address-card-header">
                    <h5 class="address-card-title"><i
                        class="bi bi-geo-alt-fill text-primary me-2"></i>{{ sub_string($address['name']) }}</h5>
                    <div class="address-card-actions">
                      @if($address['default'])
                        <span class="badge bg-primary rounded-pill px-2 py-1 shadow-sm me-1">
                          <i class="bi bi-star-fill text-warning me-1 small"></i>{{__('front/common.default')}}
                        </span>
                      @endif
                      <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm border edit-address text-primary p-2 mx-1"
                        title="{{ __('front/common.edit') }}">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button type="button"
                        class="btn btn-light btn-sm rounded-circle shadow-sm border delete-address text-danger p-2"
                        title="{{ __('front/common.delete') }}">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                  <div class="address-card-body">
                    <p><strong>{{ __('common/address.name') }}:</strong> {{ $address['name'] }}</p>
                    <p><strong>{{ __('common/address.phone') }}:</strong> {{ $address['phone'] }}</p>
                    <p><strong>{{ __('common/address.zipcode') }}:</strong> <span
                        class="badge bg-light text-dark border">{{ $address['zipcode'] }}</span></p>
                    <p><strong>{{ __('common/address.address_1') }}:</strong> {{ $address['address_1'] }}</p>
                    @if($address['address_2'])
                      <p><strong>{{ __('common/address.address_2') }}:</strong> {{ $address['address_2'] }}</p>
                    @endif
                    <p class="mb-0"><strong>{{ __('common/address.region') }}:</strong> {{ $address['city'] }},
                      {{ $address['state'] }}, {{ $address['country_name'] }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          @if(count($addresses) == 0)
            <div class="text-center py-5">
              <i class="bi bi-geo text-muted display-1 opacity-25 mb-3 d-block"></i>
              <p class="text-muted fw-bold">{{ __('common/address.no_address') ?? 'No addresses found. Add a new one!' }}
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-light border-bottom-0">
          <h5 class="modal-title fw-bold text-dark" id="addressModalLabel">{{ __('common/address.address') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          @include('shared.address-form')
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.addresses.bottom')
@endsection
@push('footer')
  <script>
    const addresses = @json($addresses);
    const isDefault = $('#default');
    $('.add-address').on('click', function () {
      $('.address-form').find('input, select').each(function () {
        $(this).val('')
      })
      isDefault.val(1);
      $('#addressModal').modal('show');
    });
    $('.edit-address').on('click', function () {
      const id = $(this).closest('.address-card-premium').data('id');
      const address = addresses.find(address => address.id === id);
      getZones(address.country_code, function () {
        $('.address-form').find('input, select').each(function () {
          $(this).val(address[$(this).attr('name')])
        })
        isDefault.val(1);
        if (address.default === 1) {
          isDefault.attr('checked', 'checked');
        } else {
          isDefault.removeAttr('checked');
        }
      })
      $('#addressModal').modal('show');
    });
    $('.delete-address').on('click', function () {
      const id = $(this).closest('.address-card-premium').data('id');
      inno.confirm('{{ __('front/common.delete_confirm') }}', {
        btn: ['{{ __('front/common.confirm') }}', '{{ __('front/common.cancel') }}'],
        title: '{{ __('front/account.tip') }}',
        offset: 'auto',
        area: ['400px', 'auto'],
        shade: [0.3, "#fff"]
      }, function () {
        axios.delete(`{{ account_route('addresses.index') }}/${id}`).then(function (res) {
          if (res.success) {
            inno.msg(res.message, { icon: 1, time: 1000 }, function () {
              window.location.reload()
            });
          }
        })
      });
    });
    function updateAddress(params) {
      const id = new URLSearchParams(params).get('id');
      const href = @json(account_route('addresses.index'));
      const method = id ? 'put' : 'post'
      const url = id ? `${href}/${id}` : href
      axios[method](<url, params>).then(function (res) {
        if (res.success) {
          $('#addressModal').modal('hide');
          inno.msg(res.message);
          window.location.reload();
        }
      })
    }
  </script>
@endpush
