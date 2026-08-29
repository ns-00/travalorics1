<div class="row review-amazon-style">

  <!-- العمود الأيسر: إحصائيات التقييم -->
  <div class="col-md-4 mb-4">
    <h4 class="mb-3">{{ __('front/product.customer_reviews') }}</h4>

    <h2 class="fw-bold mb-0 text-dark" style="font-size: 3.5rem; letter-spacing: -1px;">
      {{ number_format($reviewStats['average'], 1) }}</h2>
    <div class="text-warning mb-2" style="font-size: 1.8rem; letter-spacing: 2px;">
      @for($i = 1; $i <= 5; $i++)
        {{ $i <= round($reviewStats['average']) ? '★' : '☆' }}
      @endfor
    </div>

    <div class="text-muted mb-4 fw-bold">{{ __('front/product.based_on') }} {{ $reviewStats['total'] }}
      {{ __('front/product.global_ratings') }}</div>

    <!-- 🧠 Product Attribute Intelligence Summary -->
    @foreach ([5, 4, 3, 2, 1] as $star)
      @php
        $count = $reviewStats['histogram'][$star] ?? 0;
        $percent = $reviewStats['total'] > 0 ? round(($count / $reviewStats['total']) * 100) : 0;
      @endphp
      <div class="d-flex align-items-center gap-2 mb-2 rating-histogram-bar" data-rating="{{ $star }}"
        style="cursor:pointer; transition: opacity 0.2s; padding: 2px 5px;">
        <span class="fw-bold text-dark" style="width: 25px;">{{ $star }}★</span>
        <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px; background-color: #e9ecef;">
          <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percent }}%; border-radius: 10px;">
          </div>
        </div>
        <span class="text-muted small fw-bold" style="width: 35px; text-align: right;">{{ $percent }}%</span>
      </div>
    @endforeach

    <!-- 🧠 Product Attribute Intelligence Summary -->
    @if(isset($reviewStats['attributes']) && count($reviewStats['attributes']) > 0)
      <div class="mt-4 mb-4 bg-light p-3 rounded border shadow-sm">

        <div class="text-center mb-2">
          <small class="text-muted fw-bold">
            ☕ Flavor Profile (Based on customer taste)
          </small>
        </div>

        <!-- Chart.js View -->
        <div class="mb-3 d-flex justify-content-center">
          <canvas id="coffeeRadarChart" style="max-height: 200px; width: 100%;"></canvas>
        </div>

        <div class="mt-2 border-top pt-3">
          @foreach($reviewStats['attributes'] as $key => $data)
            @php $avg = $data['avg'];
            $keyName = str_replace('_', ' ', $key); @endphp
            <div class="d-flex align-items-center gap-2 small mb-2">
              <span class="text-muted text-capitalize" style="width:90px; font-size: 0.85rem;">{{ $keyName }}</span>
              <div class="progress w-100" style="height:6px; background-color: #dee2e6;">
                <div class="progress-bar"
                  style="width: {{ ($avg / 5) * 100 }}%; background: linear-gradient(90deg, #6F4E37, #C89B3C); border-radius: 4px;">
                </div>
              </div>
              <span class="fw-bold">{{ $avg }}</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>

  <!-- العمود الأيمن: التعليقات + نموذج الكتابة -->
  <div class="col-md-8 ps-md-5">

    <!-- نموذج كتابة تقييم -->
    <div class="review-action-section bg-white p-4 rounded shadow-sm border mb-4">
      <h5><i class="bi bi-pencil-square me-2"></i>{{ __('front/product.write_review') }}</h5>
      <p class="text-muted" style="font-size:0.9rem;">{{ __('front/product.share_thoughts') }}</p>

      @if(current_customer())
        @if(!$reviewed)
          @include('shared.review')
        @else
          <div class="alert alert-success mt-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ __('front/product.have_reviewed') }}
          </div>
        @endif
      @else
        <a class="btn btn-outline-dark w-100 mt-2 rounded-pill" href="javascript:inno.openLogin()">
          {{ __('front/product.please_login_first') }}
        </a>
      @endif
    </div>

    <!-- فرز وتعليقات -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="active-rating-filter text-muted" style="font-size:0.9rem;"></div>
      <select class="form-select form-select-sm w-auto review-sort-select">
        <option value="top">{{ __('front/product.top_reviews') }}</option>
        <option value="recent">{{ __('front/product.most_recent') }}</option>
      </select>
    </div>

    <!-- CRO Trick: AI Review Summary -->
    @if(isset($reviewStats['smart_summary']))
      <div class="mb-3 p-3 bg-light rounded"
        style="border-left: 4px solid #ffc107 !important; border: 1px solid #e9ecef;">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span style="
                  background:#ffc107;
                  color:#000;
                  padding:4px 8px;
                  border-radius:6px;
                  font-size:12px;
                  font-weight:700;
              ">
            🔥 Insight
          </span>
          <strong class="text-dark">{{ __('front/product.customers_say') }}</strong>
        </div>
        <div class="mt-1 text-muted" style="font-size: 0.95rem; line-height: 1.5;">
          {{ $reviewStats['smart_summary'] }}
        </div>
      </div>

      <!-- Smart Checkout Trigger -->
      <div class="mt-3 mb-4 text-center">
        <button class="btn btn-dark px-4 py-2 fw-bold text-white rounded-pill shadow-sm" onclick="scrollToBuyBox()">
          🔥 Buy Now — Customers Love It
        </button>
      </div>
    @endif

    <!-- قائمة التعليقات -->
    <div class="review-list-container">
      @include('products.components._review_list', ['reviews' => $reviews])
    </div>

    <!-- زر Load More -->
    @if($reviews->hasMorePages())
      <div class="text-center mt-4 load-more-reviews-container">
        <button class="btn btn-outline-dark rounded-pill px-4 load-more-reviews" data-page="2"
          data-product-id="{{ $product->id }}">
          {{ __('front/common.load_more') }}
        </button>
      </div>
    @endif

  </div>
</div>

@push('footer')
  <style>
    .review-amazon-style .review-item {
      border-radius: 6px;
      border: 1px solid #e2e2e2;
      padding: 15px;
    }

    .review-amazon-style .rating-histogram-bar {
      transition: all 0.2s;
    }

    .review-amazon-style .rating-histogram-bar:hover {
      opacity: 0.8;
    }

    .review-amazon-style .rating-histogram-bar.active {
      background: #fff3cd;
      border-radius: 6px;
    }

    .review-amazon-style .customer-avatar i {
      line-height: 35px;
    }
  </style>
  <script>
    let currentSort = 'top';
    let currentRatingFilter = null;

    function scrollToBuyBox() {
      const buyBox = document.querySelector('.sticky-top');
      if (buyBox) {
        buyBox.scrollIntoView({ behavior: 'smooth' });
      } else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    }

    function fetchReviews(page = 1, append = false) {
      if (!append) $('.review-list-container').html('<div class="text-center py-5"><i class="bi bi-arrow-repeat spin fs-3"></i></div>');
      axios.get('{{ front_route("products.reviews", ["product" => $product->id]) }}', {
        params: { page, sort: currentSort, filter_rating: currentRatingFilter }
      }).then(response => {
        if (append) $('.review-list-container').append(response.data.html);
        else $('.review-list-container').html(response.data.html);

        // تحديث زر Load More
        $('.load-more-reviews-container').remove();
        if (response.data.has_more) {
          $('.review-list-container').after(`<div class="text-center mt-4 load-more-reviews-container">
          <button class="btn btn-outline-dark rounded-pill px-4 load-more-reviews" data-page="${page + 1}" data-product-id="{{ $product->id }}">{{ __('front/common.load_more') }}</button>
        </div>`);
        }
      }).catch(error => console.error(error));
    }

    $(document).on('click', '.load-more-reviews', function () {
      fetchReviews($(this).data('page'), true);
    });

    $('.review-sort-select').on('change', function () {
      currentSort = $(this).val();
      fetchReviews(1);
    });

    $('.rating-histogram-bar').on('click', function () {
      const rating = $(this).data('rating');

      $('.rating-histogram-bar').removeClass('active');

      if (currentRatingFilter === rating) {
        currentRatingFilter = null;
      } else {
        currentRatingFilter = rating;
        $(this).addClass('active');
      }

      fetchReviews(1);
    });

    function markHelpful(reviewId, btn) {
      let url = '{{ front_route("reviews.helpful", ["id" => ":id"]) }}'.replace(':id', reviewId);
      axios.post(url).then(res => {
        if (res.data.success) {
          $(btn).html('👍 ' + res.data.count).prop('disabled', true).removeClass('btn-light').addClass('btn-secondary text-white');
        } else {
          inno.msg(res.data.message);
        }
      });
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @if(isset($reviewStats['radar_labels']))
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const radarLabels = @json($reviewStats['radar_labels']);
        const radarValues = @json($reviewStats['radar_values']);
        const ctx = document.getElementById('coffeeRadarChart');

        if (ctx && radarLabels.length > 0) {
          new Chart(ctx, {
            type: 'radar',
            data: {
              labels: radarLabels,
              datasets: [{
                label: 'Rating',
                data: radarValues,
                fill: true,
                backgroundColor: 'rgba(111, 78, 55, 0.25)', // Premium Coffee Color
                borderColor: '#6F4E37',
                pointBackgroundColor: '#6F4E37',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#6F4E37',
                borderWidth: 2
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                r: {
                  min: 0,
                  max: 5,
                  ticks: { stepSize: 1, display: false },
                  grid: { color: 'rgba(0,0,0,0.05)' },
                  angleLines: { color: 'rgba(0,0,0,0.1)' },
                  pointLabels: {
                    font: { size: 11, weight: 'bold', family: "'Inter', sans-serif" },
                    color: '#495057'
                  }
                }
              },
              plugins: {
                legend: { display: false },
                tooltip: {
                  backgroundColor: '#212529',
                  padding: 10,
                  cornerRadius: 8,
                  displayColors: false,
                  callbacks: {
                    label: function (context) {
                      return context.label + ': ' + context.formattedValue + '/5';
                    },
                    afterLabel: function (context) {
                      const tooltipsMap = {
                        'Acidity': 'Bright & crisp taste notes',
                        'Body': 'Tactile feeling in the mouth',
                        'Aroma': 'Fragrance of the brewed coffee',
                        'Flavor': 'Overall taste characteristics',
                        'Aftertaste': 'Lingering finish on the palate'
                      };
                      return tooltipsMap[context.label] ? 'ℹ️ ' + tooltipsMap[context.label] : '';
                    }
                  }
                }
              }
            }
          });
        }
      });
    </script>
  @endif
@endpush