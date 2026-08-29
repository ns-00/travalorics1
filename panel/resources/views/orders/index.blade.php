@extends('panel::layouts.app')
@section('body-class', '')

@section('title', __('panel/menu.orders'))

@section('page-title-right')
  @hookinsert('panel.orders.index.title.right')
@endsection

@section('content')
  <div class="premium-dashboard-card h-min-600">
    <div class="card-body p-0">
      <x-panel-data-criteria :criteria="$criteria ?? []" :action="panel_route('orders.index')" :export="true" />
      @hookinsert('panel.orders.index.criteria.after')

      @if ($orders->count())
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr class="text-uppercase text-muted small" style="letter-spacing: 0.5px;">
               @hookinsert('panel.orders.index.header.top')
                <th>{{ __('panel/common.id') }}</th>
                <th>{{ __('panel/order.number') }}</th>
                <th>{{ __('panel/order.order_items') }}</th>
                <th>{{ __('panel/order.customer_name') }}</th>
                <th>{{ __('panel/order.shipping_method_name') }}</th>
                <th>{{ __('panel/order.billing_method_name') }}</th>
                <th>{{ __('panel/order.total') }}</th>
                <th>{{ __('panel/order.status') }}</th>
                @hookinsert('panel.orders.index.header.extra')
                <th>{{ __('panel/order.created_at') }}</th>
                <th class="text-end">{{ __('panel/common.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as $item)
                <tr>
                 @hookinsert('panel.orders.index.row.top', $item)
                  <td class="fw-bold">{{ $item->id }}</td>
                  <td>
                    <span class="fw-bold text-dark">#{{ $item->number }}</span>
                    @if($item->id == $item->parent_id)
                      <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1">M</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      @foreach ($item->items->take(5) as $product)
                        <div class="wh-40 overflow-hidden border border-light rounded-3 shadow-sm bg-white" style="margin-right: -10px; position: relative; z-index: {{ 5 - $loop->index }};">
                          <img src="{{ image_resize($product->image, 50, 50) }}" alt="{{ $product->name }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>
                      @endforeach
                      @if($item->items->count() > 5)
                        <div class="ms-3 text-muted small fw-bold">+{{ $item->items->count() - 5 }}</div>
                      @endif
                    </div>
                  </td>
                  <td>
                    @if ($item->customer_id > 0)
                      <a href="{{ panel_route('customers.edit', $item->customer_id) }}" class="text-decoration-none fw-bold hover-primary transition-all" target="_blank">
                        <i class="bi bi-person-circle text-muted me-1"></i>{{ $item->customer_name }}
                      </a>
                    @else
                      <span class="text-muted"><i class="bi bi-person-circle me-1"></i>{{ $item->customer_name }}</span>
                    @endif
                  </td>
                  <td><span class="badge bg-light text-dark border">{{ $item->shipping_method_name }}</span></td>
                  <td><span class="badge bg-light text-dark border">{{ $item->billing_method_name }}</span></td>
                  <td class="fw-bold text-primary">{{ $item->total_format }}</td>
                  <td><span class="badge rounded-pill px-3 py-2 bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} border border-{{ $item->status_color }} border-opacity-25">{{ $item->status_format }}</span></td>
                  @hookinsert('panel.orders.index.row.extra', $item)
                  <td class="text-muted small">{{ $item->created_at }}</td>
                  <td class="text-end">
                    <a href="{{ panel_route('orders.show', [$item->id]) }}"
                      class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm hover-primary fw-bold">{{ __('panel/common.view') }}</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ $orders->withQueryString()->links('panel::vendor/pagination/bootstrap-4') }}
      @else
        <x-common-no-data />
      @endif
    </div>
  </div>
@endsection

@push('footer')
    @hookinsert('panel.orders.footer.script.bottom')
@endpush
