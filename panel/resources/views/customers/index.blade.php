@extends('panel::layouts.app')
@section('body-class', 'page-customer')

@section('title', __('panel/menu.customers'))
@section('page-title-right')
<a href="{{ panel_route('customers.create') }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> {{
  __('panel/common.create') }}</a>
@endsection

@section('content')
<div class="premium-dashboard-card h-min-600" id="app">
  <div class="card-body p-0">

    <x-panel-data-criteria :criteria="$criteria ?? []" :action="panel_route('customers.index')" />

    @if ($customers->count())
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr class="text-uppercase text-muted small" style="letter-spacing: 0.5px;">
            <th>{{ __('panel/common.id')}}</th>
            <th>{{ __('panel/customer.customer_info') }}</th>
            @hookinsert('panel.customer.index.thead.bottom')
            <th>{{ __('panel/customer.from') }}</th>
            <th>{{ __('panel/customer.group') }}</th>
            <th>{{ __('panel/customer.locale') }}</th>
            @hookinsert('panel.product.index.thead.bottom')
            <th>{{ __('panel/common.created_at') }}</th>
            <th>{{ __('panel/common.active') }}</th>
            <th class="text-end">{{ __('panel/common.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($customers as $item)
          <tr class="border-bottom" style="border-color: #f1f1f1 !important;">
            <td class="fw-bold">{{ $item->id }}</td>
            <td class="customer-info-cell">
              <div class="d-flex align-items-center">
                <div class="wh-40 rounded-circle overflow-hidden shadow-sm border border-light me-3 flex-shrink-0">
                  <img src="{{ image_resize($item->avatar, 40, 40) }}" 
                       alt="{{ $item->name }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                </div>
                <div class="customer-details">
                  <div class="customer-name fw-bold text-dark">{{ $item->name }}</div>
                  <div class="customer-email text-muted small"><i class="bi bi-envelope me-1"></i>{{ $item->email }}</div>
                </div>
              </div>
            </td>
            @hookinsert('panel.customer.index.tbody.bottom', $item)
            <td><span class="badge bg-light text-dark border">{{ $item->from_display }}</span></td>
            <td><span class="badge bg-light text-dark border">{{ $item->customerGroup->translation->name ?? '-' }}</span></td> 
            <td class="text-uppercase fw-bold text-secondary">{{ $item->locale }}</td>
            @hookinsert('panel.product.index.tbody.bottom', $item)
            <td class="text-muted small">{{ $item->created_at }}</td>
            <td>
              @include('panel::shared.list_switch', ['value' => $item->active, 'url' => panel_route('customers.active',
              $item)])
            </td>
            <td class="text-end">
              <div class="d-flex gap-2 justify-content-end">
                <a href="{{ panel_route('customers.login', [$item->id]) }}" target="_blank">
                  <el-button size="small" plain type="primary">{{ __('panel/customer.login_frontend')}}</el-button>
                </a>
                <a href="{{ panel_route('customers.edit', [$item->id]) }}">
                  <el-button size="small" plain type="primary">{{ __('panel/common.edit')}}</el-button>
                </a>
                <form ref="deleteForm" action="{{ panel_route('customers.destroy', [$item->id]) }}" method="POST"
                  class="d-inline">
                  @csrf
                  @method('DELETE')
                  <el-button size="small" type="danger" plain @click="open({{$item->id}})">{{
                    __('panel/common.delete')}}</el-button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{ $customers->withQueryString()->links('panel::vendor/pagination/bootstrap-4') }}
    @else
    <x-common-no-data />
    @endif
  </div>
</div>
@endsection

@push('footer')
<script>
  const { createApp, ref } = Vue;
    const { ElMessageBox, ElMessage } = ElementPlus;

      const app = createApp({
      setup() {
     const deleteForm = ref(null);

     const open = (index) => {
       ElMessageBox.confirm(
       '{{ __("common/base.hint_delete") }}',
       '{{ __("common/base.cancel") }}',
       {
         confirmButtonText: '{{ __("common/base.confirm")}}',
         cancelButtonText: '{{ __("common/base.cancel")}}',
         type: 'warning',
       }
       )
     .then(() => {
      const deleteUrl = urls.base_url+'/customers/'+index;
      deleteForm.value.action=deleteUrl;
      deleteForm.value.submit();
      })
     .catch(() => {
     });
     };

     return { open, deleteForm };
       }
        });

     app.use(ElementPlus);
     app.mount('#app');
</script>
@endpush
