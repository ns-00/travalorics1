@extends('layouts.app')
@section('body-class', 'page-review')
@section('content')
  <x-front-breadcrumb type="route" value="account.reviews.index" title="{{ __('front/account.reviews') }}" />
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

      .account-table-box th {
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 12px;
      }

      .account-table-box td {
        vertical-align: middle;
        padding: 16px 8px;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
      }

      .review-product-img {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #eee;
      }
    </style>
  @endpush
  @hookinsert('account.review_index.top')
  <div class="container py-4">
    <div class="row gx-lg-5">
      <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        @include('shared.account-sidebar')
      </div>
      <div class="col-12 col-lg-9">
        <div class="premium-dashboard-card review-box">
          <div class="account-card-title mb-4 pb-3 border-bottom">
            <h4 class="fw-bold mb-0 text-dark" >
              {{ __('front/review.review') }}</h4>
          </div>
          @if ($reviews->count())
            <div class="table-responsive">
              <table class="table account-table-box table-borderless">
                <thead>
                  <tr>
                    <th>{{ __('panel/review.product') }}</th>
                    <th>{{ __('front/review.rating') }}</th>
                    <th>{{ __('front/review.review_content') }}</th>
                    <th>{{ __('front/common.date') }}</th>
                    <th class="text-end">{{ __('panel/common.actions') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($reviews as $review)
                    <tr class="review-card-actions" data-id="{{ $review->id }}">
                      <td data-title="{{ __('front/order.product') }}" data-bs-toggle="tooltip" title="{{ $review->product->fallbackName() }}">
                        <div class="d-flex align-items-center gap-3">
                          <img src="{{ $review->product->image_url }}" alt="{{ $review->product->name }}"
                            class="review-product-img shadow-sm">
                          <span class="fw-bold text-dark">{{ sub_string($review->product->fallbackName(), 24) }}</span>
                        </div>
                      </td>
                      <td data-title="{{ __('front/product.rating') }}">
                        <div class="text-warning fs-6">
                          <x-front-review :rating="$review['rating']" />
                        </div>
                      </td>
                      <td data-title="{{ __('front/product.review_content') }}" data-bs-toggle="tooltip" title="{{ $review->content }}" class="text-muted">
                        {{ sub_string($review->content, 25)}}</td>
                      <td data-title="{{ __('front/order.order_date') }}" class="text-muted small">{{ $review->created_at->format('Y-m-d') }}</td>
                      <td data-title="{{ __('front/common.action') }}" class="text-end">
                        <button type="button"
                          class="btn delete-review btn-sm btn-outline-danger rounded-pill px-3 fw-bold shadow-sm"
                          data-url="{{ account_route('reviews.destroy', $review->id) }}"><i
                            class="bi bi-trash me-1"></i>{{ __('front/common.delete') }}</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-4">
              {{ $reviews->links('panel::vendor/pagination/bootstrap-4') }}
            </div>
          @else
            <div class="text-center py-5">
              <i class="bi bi-star text-muted display-1 opacity-25 mb-3 d-block"></i>
              <p class="text-muted fw-bold">No reviews yet.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @hookinsert('account.review_index.bottom')
@endsection
@push('footer')
  <script>
    $('.delete-review').on('click', function () {
      const url = $(this).data('url');
      inno.confirm('{{ __('front/common.delete_confirm') }}', {
        btn: ['{{ __('front/common.confirm') }}', '{{ __('front/common.cancel') }}'],
        title: '{{ __('front/account.tip') }}',
        offset: 'auto',
        area: ['400px', 'auto'],
        shade: [0.3, "#fff"]
      }, function () {
        layer.load(2, { shade: [0.3, "#fff"] });
        axios.delete(url).then(function (res) {
          if (res.success) {
            inno.msg(res.message, { icon: 1, time: 1000 }, function () {
              window.location.reload()
            });
          }
        }).catch(function () {
          layer.closeAll('loading');
        })
      });
    });
  </script>
@endpush
