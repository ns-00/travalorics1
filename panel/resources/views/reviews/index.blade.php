@extends('panel::layouts.app')
@section('body-class', '')

@section('title', __('panel/menu.reviews'))

@section('page-title-right')
  <a href="{{ panel_route('reviews.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-square"></i> {{__('panel/common.create') }}
  </a>
  @hookinsert('panel.reviews.list.buttons')
@endsection

@section('content')
  <div class="card h-min-600">
    <div class="card-body">

      <x-panel-data-criteria :criteria="$criteria ?? []" :action="panel_route('reviews.index')"/>

      @if ($reviews->count())
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
            <tr>
              <td>{{ __('panel/review.id') }}</td>
              <td>{{ __('panel/review.customer') }}</td>
              <td>{{ __('panel/review.product') }}</td>
              <td>{{ __('panel/review.rating') }}</td>
              <td>{{ __('panel/review.review_content') }}</td>
              <td>{{ __('panel/common.date') }}</td>
              <td>{{ __('panel/common.status') ?? 'Status' }}</td>
              <td>{{ __('panel/common.active') }}</td>
              <td>{{ __('panel/common.actions') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($reviews as $review)
              <tr>
                <td>
                  @if($review->featured_rank > 0)
                    <i class="bi bi-star-fill text-warning me-1" title="Featured Review"></i>
                  @endif
                  {{ $review->id }}
                </td>
                <td>{{ $review->customer->name ?? '-' }}</td>
                @if($review->product)
                  <td data-title="product" data-bs-toggle="tooltip" data-bs-placement="bottom"
                      title="{{ $review->product->fallbackName() }}">
                    <a href="{{ $review->product->url ?? '' }}" target="_blank" class="text-decoration-none">
                      <img src="{{ image_resize($review->product->image ?? '') }}"
                           alt="{{ $review->product->name ?? '' }}"
                           class="img-fluid wh-30">
                      {{ sub_string($review->product->fallbackName(), 24) }}
                    </a>
                  </td>
                @else
                  <td>-</td>
                @endif
                <td>
                  <x-front-review :rating="$review['rating']"/>
                </td>
                <td>
                  <div>
                    @if($review->title)
                      <strong>{{ $review->title }}</strong><br>
                    @endif
                    <span class="btn-link-review_content" data-bs-toggle="tooltip" data-bs-placement="bottom"
                          title="{{ $review->content }}">{{ sub_string($review->content)}}</span>
                  </div>
                </td>
                <td>{{ $review->created_at->format('Y-m-d') }}</td>
                <td>
                  <select class="form-select form-select-sm status-select" data-url="{{ panel_route('reviews.status', $review->id) }}" style="width: 130px; font-weight: bold; background-color: {{ $review->status === 'published' ? '#d1e7dd' : ($review->status === 'pending' ? '#fff3cd' : '#e2e3e5') }}; color: {{ $review->status === 'published' ? '#0f5132' : ($review->status === 'pending' ? '#664d03' : '#41464b') }}; border-color: transparent;">
                    <option value="pending" {{ $review->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="published" {{ $review->status === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="shadow_hidden" {{ $review->status === 'shadow_hidden' ? 'selected' : '' }}>Hidden (Spam)</option>
                  </select>
                </td>
                <td>
                  @if ($review['id'])
                    @include('panel::shared.list_switch', ['value' => $review['active'], 'url' => panel_route('reviews.active', $review['id'])])
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn feature-review btn-sm {{ $review->featured_rank > 0 ? 'btn-warning text-dark' : 'btn-outline-warning' }}"
                            data-url="{{ panel_route('reviews.feature', $review->id) }}" title="Highest Rank means it shows first">
                      <i class="bi bi-star{{ $review->featured_rank > 0 ? '-fill' : '' }}"></i> Feature
                    </button>
                    <button type="button" class="btn delete-review btn-sm btn-outline-danger"
                            data-url="{{ panel_route('reviews.destroy', $review->id) }}"><i class="bi bi-trash"></i></button>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        {{ $reviews->withQueryString()->links('panel::vendor/pagination/bootstrap-4') }}
      @else
        <x-common-no-data/>
      @endif
    </div>
  </div>
@endsection

@push('footer')
  <script>
    $('.delete-review').on('click', function () {
      const url = $(this).data('url');
      layer.confirm('{{ __('front/common.delete_confirm') }}', {
        btn: ['{{ __('front/common.confirm') }}', '{{ __('front/common.cancel') }}']
      }, function () {
        axios.delete(url).then(function (res) {
          if (res.success) {
            layer.msg(res.message, {icon: 1, time: 1000}, function () {
              window.location.reload()
            });
          }
        })
      });
    });
    $('.feature-review').on('click', function () {
      const url = $(this).data('url');
      const btn = $(this);
      axios.put(url).then(function (res) {
        if (res.data.success) {
          layer.msg(res.data.message, {icon: 1, time: 800}, function () {
            window.location.reload();
          });
        }
      });
    });

    $('.status-select').on('change', function () {
      const url = $(this).data('url');
      const status = $(this).val();
      axios.put(url, { status: status }).then(function (res) {
        if (res.data.success) {
          layer.msg(res.data.message, {icon: 1, time: 800}, function () {
            window.location.reload();
          });
        }
      });
    });
  </script>
@endpush
