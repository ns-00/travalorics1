@extends('panel::layouts.app')
@section('body-class', '')
@section('title', __('panel/menu.order_returns'))

@section('page-title-right')
<div class="title-right-btns">
  @foreach ($next_statuses as $status)
    <button type="button" class="btn btn-sm btn-primary ms-2"
      onclick="changeReturnStatus('{{ $status['status'] }}')">
      {{ $status['name'] }}
    </button>
  @endforeach
  <a href="{{ panel_route('order_returns.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
    {{ __('panel/common.btn_back') }}
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">{{ __('panel/menu.order_returns') }} #{{ $order_return->number }}</h5>
  </div>
  <div class="card-body">

    {{-- معلومات الإرجاع --}}
    <div class="row mb-4">
      <div class="col-md-6">
        <table class="table table-borderless">
          <tr>
            <th>{{ __('front/return.number') }}</th>
            <td>{{ $order_return->number }}</td>
          </tr>
          <tr>
            <th>{{ __('panel/order_return.order_number') }}</th>
            <td>
              <a href="{{ panel_route('orders.edit', $order_return->order_id) }}" target="_blank">
                {{ $order_return->order_number }}
              </a>
            </td>
          </tr>
          <tr>
            <th>{{ __('panel/order_return.customer') }}</th>
            <td>
              <a href="{{ panel_route('customers.edit', $order_return->customer_id) }}" target="_blank">
                {{ $order_return->customer->name ?? '-' }}
              </a>
            </td>
          </tr>
          <tr>
            <th>{{ __('front/return.status') }}</th>
            <td><span class="badge bg-{{ $order_return->status_color }}">{{ $order_return->status_format }}</span></td>
          </tr>
          <tr>
            <th>{{ __('front/return.opened') }}</th>
            <td>{{ $order_return->opened ? __('front/common.yes') : __('front/common.no') }}</td>
          </tr>
          <tr>
            <th>{{ __('front/return.quantity') }}</th>
            <td>{{ $order_return->quantity }}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-borderless">
          <tr>
            <th>{{ __('front/return.product_name') }}</th>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ $order_return->product->image_url ?? '' }}" class="wh-40 rounded border" alt="">
                <span>{{ $order_return->product_name }}</span>
              </div>
            </td>
          </tr>
          <tr>
            <th>SKU</th>
            <td>{{ $order_return->product_sku }}</td>
          </tr>
          <tr>
            <th>{{ __('front/return.comment') }}</th>
            <td>{{ $order_return->comment }}</td>
          </tr>
          <tr>
            <th>{{ __('front/return.created_at') }}</th>
            <td>{{ $order_return->created_at }}</td>
          </tr>
        </table>
      </div>
    </div>

    {{-- سجل الحالة --}}
    @if($order_return->histories && $order_return->histories->count())
    <div class="card mt-3">
      <div class="card-header">
        <h6 class="mb-0">{{ __('panel/order.history') }}</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>{{ __('front/return.status') }}</th>
                <th>{{ __('panel/order.comment') }}</th>
                <th>{{ __('panel/order.date_time') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order_return->histories as $history)
              <tr>
                <td><span class="badge bg-secondary">{{ $history->status }}</span></td>
                <td>{{ $history->comment }}</td>
                <td>{{ $history->created_at }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection

@push('footer')
<script>
function changeReturnStatus(status) {
    layer.load(2, {shade: [0.3,'#fff']});
    axios.put('{{ panel_route('order_returns.change_status', [$order_return->id]) }}', {status: status})
        .then(res => {
            inno.msg(res.message || 'تم التحديث بنجاح');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(err => {
            inno.msg(err.response?.data?.message || 'حدث خطأ');
        })
        .finally(() => layer.closeAll('loading'));
}
</script>
@endpush
