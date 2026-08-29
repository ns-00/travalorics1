<form id="reviewForm" action="{{ account_route('reviews.store') }}" method="POST">
  <div class="mb-2">
    @csrf
    @if (isset($order))
      <input type="hidden" name="order_number" value="">
      <input type="hidden" name="order_item_id" value="">
      <input type="hidden" name="product_sku" value="">
    @else
      <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
    @endif
    <div>
      <div class="review-content">
        @if (isset($order))
          <div class="mb-3">
            <table class="table table-bordered table-striped table-response">
              <thead>
                <tr>
                  <th>{{ __('front/order.order_number') }}</th>
                  <th>{{ __('front/order.product_image') }}</th>
                  <th>{{ __('front/order.product_name') }}</th>
                  <th>{{ __('front/order.product_spec') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td data-title="{{ __('front/order.order_number') }}" class="align-items-center" id='order_number'></td>
                  <td data-title="{{ __('front/order.product_image') }}">
                    <img class="product-image wh-30 justify-content-center align-items-center" id="product-image" src=""
                      class="img-fluid wh-20">
                  </td>
                  <td data-title="{{ __('front/order.product_name') }}" class="name align-items-center" id="name"></td>
                  <td data-title="{{ __('front/order.product_spec') }}" class="label mt-2 text-secondary" id="label"></td>
                </tr>
              </tbody>
            </table>
          </div>
        @endif
        <div class="row">
          <label class="col-8 text-left font-size-25 mb-0" for="review">
            <h5>{{ __('front/product.input_your_review') }}</h5>
          </label>
          <div class="rating col-4 text-end">
            <input type="radio" name="rating" value="5" id="5" checked>
            <label for="5">☆</label>
            <input type="radio" name="rating" value="4" id="4">
            <label for="4">☆</label>
            <input type="radio" name="rating" value="3" id="3">
            <label for="3">☆</label>
            <input type="radio" name="rating" value="2" id="2">
            <label for="2">☆</label>
            <input type="radio" name="rating" value="1" id="1">
            <label for="1">☆</label>
          </div>
        </div>
        <div class="mb-3 mt-3">
          <input type="text" class="form-control fw-bold shadow-sm" style="border-radius: 8px;" name="title"
            placeholder="{{ __('front/product.summary_of_experience') }}"
            required maxlength="150">
        </div>
        <textarea class="form-control shadow-sm" style="border-radius: 8px;" name="content" id="review" rows="5"
          placeholder="{{ __('front/product.input_some_text_here') }}..." required></textarea>

        <!-- Dynamic Attributes -->
        <div class="coffee-attributes-section mt-4 p-3 bg-light rounded border">
          <h6 class="mb-3 text-secondary text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;"><i
              class="bi bi-star"></i> {{ __('front/product.rate_coffee_attributes') }} <small
              class="text-muted text-lowercase">({{ __('front/product.optional') }})</small></h6>
          <div class="row" id="dynamic-attributes-container">
            @if (!isset($order))
              @php
                // Product Page Context
                $criteriaStr = isset($product) && isset($product->variables['review_criteria']) ? $product->variables['review_criteria'] : '';
                $criteriaEnStr = isset($product) && isset($product->variables['review_criteria_en']) ? $product->variables['review_criteria_en'] : '';
                
                $arCriteriaList = [];
                $enCriteriaList = [];
                if (!empty($criteriaStr)) {
                    $arCriteriaList = array_map('trim', explode(',', $criteriaStr));
                    if (!empty($criteriaEnStr)) {
                        $enCriteriaList = array_map('trim', explode(',', $criteriaEnStr));
                    }
                } else {
                    $arCriteriaList = [
                        __('front/product.roast_level'),
                        __('front/product.aroma'),
                        __('front/product.acidity'),
                        __('front/product.body'),
                        __('front/product.flavor')
                    ];
                }
              @endphp
              @foreach($arCriteriaList as $index => $arLabel)
                @php 
                    $key = md5($arLabel); 
                    $displayLabel = $arLabel;
                    if (app()->getLocale() == 'en' && isset($enCriteriaList[$index]) && !empty($enCriteriaList[$index])) {
                        $displayLabel = $enCriteriaList[$index];
                    }
                @endphp
                <div class="col-md-6 mb-2">
                  <div class="d-flex align-items-center justify-content-between">
                    <span class="text-dark" style="font-size: 0.85rem; font-weight: 500;">{{ $displayLabel }}</span>
                    <input type="hidden" name="attribute_labels[]" value="{{ $arLabel }}">
                    <div class="rating-mini d-flex flex-row-reverse" style="font-size: 1.1rem; gap: 2px;">
                      <input type="radio" id="{{$key}}_5" name="attribute_ratings[{{ $arLabel }}]" value="5" class="d-none" />
                      <label for="{{$key}}_5" class="star" style="cursor:pointer; color:#ccc;">★</label>
                      <input type="radio" id="{{$key}}_4" name="attribute_ratings[{{ $arLabel }}]" value="4" class="d-none" />
                      <label for="{{$key}}_4" class="star" style="cursor:pointer; color:#ccc;">★</label>
                      <input type="radio" id="{{$key}}_3" name="attribute_ratings[{{ $arLabel }}]" value="3" class="d-none" checked />
                      <label for="{{$key}}_3" class="star" style="cursor:pointer; color:#ccc;">★</label>
                      <input type="radio" id="{{$key}}_2" name="attribute_ratings[{{ $arLabel }}]" value="2" class="d-none" />
                      <label for="{{$key}}_2" class="star" style="cursor:pointer; color:#ccc;">★</label>
                      <input type="radio" id="{{$key}}_1" name="attribute_ratings[{{ $arLabel }}]" value="1" class="d-none" />
                      <label for="{{$key}}_1" class="star" style="cursor:pointer; color:#ccc;">★</label>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
          <style>
            .rating-mini input:checked~label {
              color: #ffa41c !important;
            }

            .rating-mini label:hover,
            .rating-mini label:hover~label {
              color: #ffb74d !important;
            }
          </style>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-4 text-end">
    <button type="submit" class="btn btn-dark submit_review px-4 py-2 rounded-pill fw-bold"
      style="box-shadow: 0 4px 6px rgba(0,0,0,.1);">
      <span class="normal-state">{{ __('front/product.submit_review') }}</span>
      <span class="loading-state d-none"><i class="bi bi-arrow-repeat spin"></i>
        {{ __('front/common.loading') }}</span>
    </button>
  </div>
</form>

@push('footer')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const reviewForm = document.getElementById('reviewForm');
      if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
          e.preventDefault();

          const submitBtn = reviewForm.querySelector('.submit_review');
          const normalState = submitBtn.querySelector('.normal-state');
          const loadingState = submitBtn.querySelector('.loading-state');

          // UI Loading state
          submitBtn.disabled = true;
          normalState.classList.add('d-none');
          loadingState.classList.remove('d-none');

          const formData = new FormData(this);

          axios.post(this.action, formData, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
            .then(function (response) {
              if (response.data.success) {
                if (typeof inno !== 'undefined' && inno.msg) {
                  inno.msg(response.data.message || 'Review submitted successfully!');
                }
                setTimeout(function () {
                  location.reload();
                }, 1000);
              }
            })
            .catch(function (error) {
              // Restore UI
              submitBtn.disabled = false;
              normalState.classList.remove('d-none');
              loadingState.classList.add('d-none');

              let message = 'An error occurred';
              if (error.response && error.response.data && error.response.data.message) {
                message = error.response.data.message;
              }
              if (typeof inno !== 'undefined' && inno.msg) {
                inno.msg(message);
              } else {
                alert(message);
              }
            });
        });
      }
    });
  </script>
@endpush