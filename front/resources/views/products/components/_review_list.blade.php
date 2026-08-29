@if($reviews->count() == 0)
  <div class="text-center py-5 text-muted">{{ __('front/product.no_reviews_yet') }}</div>
@else
  @foreach($reviews as $review)
    <div class="section-box review-item">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
          <strong class="fs-6 me-1">{{ $review->customer->name ?? 'Customer' }}</strong>
          @if($review->order_item_id)
            <span class="verified-badge ms-1"><i
                class="bi bi-patch-check-fill me-1"></i>{{ __('front/product.verified') }}</span>
          @endif
        </div>
        <span class="text-muted small">
          {{ $review->created_at->diffForHumans() }}
        </span>
      </div>

      <div class="rating-stars mb-1 fs-5">
        @for($i = 1; $i <= 5; $i++)
          {!! $i <= $review->rating ? '★' : '<span class="text-black-50" style="font-size: 0.85em;">★</span>' !!}
        @endfor
        @if($review->title) <span class="ms-2 fw-bold text-dark fs-6"
        style="vertical-align: middle;">{{ $review->title }}</span> @endif
      </div>

      @if($review->rating >= 4)
        <div class="mb-2 text-success fw-bold small">
          👍 {{ __('front/product.recommended_by_customers') }}
        </div>
      @endif

      <p class="mt-2 mb-3 text-dark" style="font-size:0.95rem; line-height: 1.6;">{{ $review->content }}</p>

      @php
        $attrs = count($review->attributes ?? []) > 0 ? $review->attributes : ($review->attribute_ratings ?? []);
      @endphp
      @if(count($attrs) > 0)
        <div class="mt-3 p-3 bg-light rounded border border-light">
          <div class="row g-2">
            @foreach($attrs as $key => $val)
              @php
                $attr_name = is_object($val) ? str_replace('_', ' ', $val->key) : str_replace('_', ' ', $key);
                $attr_value = (float) (is_object($val) ? $val->value : $val);
                
                $display_attr_name = $attr_name;
                if (isset($product) && app()->getLocale() === 'en') {
                    $vars = is_array($product->variables) ? $product->variables : [];
                    if (isset($vars['review_criteria']) && isset($vars['review_criteria_en'])) {
                        $cAr = array_map('trim', explode(',', $vars['review_criteria']));
                        $cEn = array_map('trim', explode(',', $vars['review_criteria_en']));
                        $idx = array_search(trim($attr_name), $cAr);
                        if ($idx !== false && isset($cEn[$idx])) {
                            $display_attr_name = $cEn[$idx];
                        }
                    }
                }
              @endphp
              <div class="col-12 col-md-6 mb-1">
                <div class="d-flex align-items-center gap-2 small">
                  <span class="text-muted text-capitalize" style="width:100px;">
                    {{ $display_attr_name }}
                  </span>
                  <div class="progress w-100" style="height:6px; background-color: #e9ecef;">
                    <div class="progress-bar bg-dark" style="width: {{ ($attr_value / 5) * 100 }}%; border-radius: 4px;"></div>
                  </div>
                  <span class="fw-bold text-dark">{{ $attr_value }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <div class="mt-3 small text-muted d-flex align-items-center">
        <span class="me-3">{{ __('front/product.was_this_helpful') }}</span>
        <button class="btn btn-sm btn-light border fw-bold me-2 helpful-btn rounded-pill px-3 shadow-sm"
          onclick="markHelpful({{ $review->id }}, this)">
          👍 {{ $review->like ?? 0 }}
        </button>
        <button class="btn btn-sm btn-light border fw-bold text-muted rounded-pill px-3 shadow-sm">
          👎 {{ $review->dislike ?? 0 }}
        </button>
      </div>
    </div>
  @endforeach
@endif