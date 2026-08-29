@extends('panel::layouts.app')
@section('title', __('panel/order.order_detail') . ' #' . $order->number)

@section('page-title-right')
  <div class="premium-actions-wrapper d-flex align-items-center gap-2">
    <a href="{{ panel_route('orders.index') }}" class="btn btn-light border btn-sm fw-bold px-3 text-dark">
      <i class="bi bi-arrow-left"></i> {{ __('panel/common.back') }}
    </a>
    
    @if(placeholder_hook_matched('panel.orders.show.actions.before'))
      @hookinsert('panel.orders.show.actions.before')
    @endif

    <button onclick="window.print();" class="btn btn-light border btn-sm fw-bold px-3 text-dark">
      <i class="bi bi-printer"></i> {{ __('panel/common.print') }} / PDF
    </button>

    @if($order->canCancel())
      <form action="{{ panel_route('orders.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
        @csrf @method('PUT')
        <button type="submit" class="btn btn-danger btn-sm fw-bold px-3">
          <i class="bi bi-x-circle"></i> Cancel Order
        </button>
      </form>
    @endif
    
    @hookinsert('panel.orders.show.title.right')
  </div>
@endsection

@section('content')
<div class="row g-4">
  <div class="col-xl-9 col-lg-8">
    <div class="premium-dashboard-card mb-4 p-4 d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: rgba(111, 78, 55, 0.05); border: 1px solid rgba(111, 78, 55, 0.1);">
      <div>
        <h3 class="m-0 fs-5 fw-bold text-dark">Order Status: {{ $order->status_format }}</h3>
        <p class="m-0 text-muted small">Placed on {{ $order->created_at->format('M d, Y @ h:i A') }}</p>
      </div>
      <div>
        <form action="{{ panel_route('orders.update-status', $order->id) }}" method="POST" class="d-flex gap-2">
          @csrf @method('PUT')
          <select name="status" class="form-select form-select-sm border-secondary-subtle">
            @foreach($order->availableStatuses() ?? [] as $key => $status)
              <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary btn-sm px-3">Update</button>
        </form>
      </div>
    </div>

    <div class="premium-dashboard-card mb-4 p-0 overflow-hidden">
      <div class="p-4 border-bottom fw-bold text-dark"><i class="bi bi-box-seam text-primary me-2"></i>Line Items ({{ $order->items->count() ?? 0 }})</div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Item Description</th>
              <th>Price</th>
              <th class="text-center">Qty</th>
              <th class="text-end pe-4">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
              <tr>
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ image_resize($item->product_image ?? '', 50, 50) }}" class="rounded border" style="width:40px; height:40px; object-fit:cover;">
                    <div>
                      <span class="fw-bold d-block text-dark">{{ $item->product_name }}</span>
                      <span class="text-muted small">SKU: {{ $item->sku ?? '-' }}</span>
                    </div>
                  </div>
                </td>
                <td>{{ currency_format($item->price ?? 0) }}</td>
                <td class="text-center">×{{ $item->quantity }}</td>
                <td class="text-end pe-4 fw-bold text-dark">{{ currency_format($item->total ?? 0) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="p-4 border-top bg-light-subtle d-flex flex-column gap-2 ms-auto" style="max-width: 360px;">
        <div class="d-flex justify-content-between text-secondary small"><span>Subtotal</span><span>{{ currency_format($order->subtotal ?? 0) }}</span></div>
        @if($order->discount > 0)<div class="d-flex justify-content-between text-danger small fw-medium"><span>Discount</span><span>-{{ currency_format($order->discount) }}</span></div>@endif
        <div class="d-flex justify-content-between text-secondary small"><span>Shipping</span><span>{{ currency_format($order->shipping_fee ?? 0) }}</span></div>
        <div class="d-flex justify-content-between border-top pt-2 mt-1 fs-5 fw-bold text-primary"><span>Grand Total</span><span>{{ $order->total_format }}</span></div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-4">
    <div class="premium-dashboard-card mb-4">
      <div class="fw-bold fs-6 text-dark mb-3 pb-2 border-bottom"><i class="bi bi-person me-2 text-primary"></i>Customer Profile</div>
      <h5 class="fw-bold mb-2 fs-6 text-dark">{{ $order->customer_name }}</h5>
      <p class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i> {{ $order->customer_email ?? 'No Email' }}</p>
      <p class="text-muted small mb-0"><i class="bi bi-telephone me-1"></i> {{ $order->customer_phone ?? 'No Phone' }}</p>
    </div>
    <div class="premium-dashboard-card mb-4">
      <div class="fw-bold fs-6 text-dark mb-3 pb-2 border-bottom"><i class="bi bi-geo-alt me-2 text-primary"></i>Shipping Address</div>
      <div class="text-secondary small lh-base">{!! nl2br(e($order->shipping_address ?? 'No address')) !!}</div>
    </div>
  </div>
</div>

<style>
  @media print {
    body { background: #fff !important; color: #000 !important; }
    .sidebar-box, .header-box, .page-title-box .btn, .status-update-form-wrapper, .page-bottom-btns, p.text-center { display: none !important; }
    .premium-dashboard-card { border: none !important; box-shadow: none !important; background: transparent !important; }
  }
</style>
@endsection