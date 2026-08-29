@extends('panel::layouts.app')
@section('body-class', 'page-home')

@section('title', __('panel/menu.dashboard'))

@push('header')
<script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')

<div class="row dashboard-top-card g-2 g-lg-4 mb-3 mb-lg-4">
  @foreach ($cards as $card)
  <div class="col-6 col-md-3">
    <div class="premium-dashboard-card dashboard-item p-4">
      <div class="card-body p-0">
        <a href="{{ $card['url'] }}" class="text-decoration-none">
        <div class="d-flex justify-content-between align-items-center">
          <div class="left">
            <span class="title text-muted text-uppercase small fw-bold mb-1 d-block">{{ $card['title'] }}</span>
            <div class="quantity text-dark fs-3 fw-bold">{{ $card['quantity'] }}</div>
          </div>
          <div class="right">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
              <i class="{{ $card['icon'] }} icon fs-3" style="color: var(--primary);"></i>
            </div>
          </div>
        </div>
        </a>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="row">
  <div class="col-12 col-md-6 mb-4">
    <div class="premium-dashboard-card h-100 p-4">
      <div class="fw-bold fs-5 text-dark mb-4 border-bottom pb-3"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>{{ __('panel/dashboard.order_trends') }}</div>
      <div class="card-body p-0" style="min-height: 300px;">
        <canvas id="chart-new-quantity"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 mb-4">
    <div class="premium-dashboard-card top-sale-products h-100 p-4">
      <div class="fw-bold fs-5 text-dark mb-4 border-bottom pb-3"><i class="bi bi-trophy me-2 text-warning"></i>{{ __('panel/dashboard.top_products') }}</div>
      <div class="card-body pb-0 p-0">
        @if ($top_sale_products)
          <div class="table-responsive">
            <table class="table table-borderless align-middle mt-n3 mb-0">
              <tbody>
                @foreach($top_sale_products as $product)
                <tr class="border-bottom" style="border-color: #f1f1f1 !important;">
                  <td class="text-center py-3" style="width: 50px;">
                    @if ($loop->iteration <= 3)
                      <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 35px; height: 35px;">
                        <span class="text-warning fw-bold">{{ $loop->iteration }}</span>
                      </div>
                    @else
                      <span class="badge bg-light text-secondary border rounded-circle" style="width: 25px; height: 25px; line-height: 18px;">{{ $loop->iteration }}</span>
                    @endif
                  </td>
                  <td class="py-3">
                    <a class="d-flex align-items-center text-dark text-decoration-none transition-all hover-primary" href="{{ panel_route('products.edit', $product['product_id']) }}">
                      <div class="wh-40 rounded overflow-hidden shadow-sm border border-light me-3"><img src="{{ image_resize($product['image'], 60, 60) }}" alt="{{ $product['name'] }}" class="img-fluid w-100 h-100" style="object-fit: cover;"></div>
                      <span class="fw-bold">{{ $product['summary'] }}</span>
                    </a>
                  </td>
                  <td class="text-end py-3 fw-bold text-success">{{ $product['order_count'] }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <x-common-no-data :width="240" />
        @endif
      </div>
    </div>
  </div>
</div>
<img src="{{ dashboard_url() }}" class="d-none" alt="dashboard"/>
@endsection

@push('footer')
<script>
  const ctx1 = document.getElementById('chart-new-quantity').getContext('2d');
  const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: false
    },
    interaction: {
      mode: 'index',
      intersect: false,
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          drawBorder: false,
          borderDash: [3],
        },
      },
      x: {
        beginAtZero: true,
        grid: {
          drawBorder: false,
          display: false
        },
      }
    },
  };

  const orderGradient = ctx1.createLinearGradient(0, 0, 0, 380);
  orderGradient.addColorStop(0, 'rgba(111, 78, 55, 0.4)'); // Primary Theme Color
  orderGradient.addColorStop(1, 'rgba(111, 78, 55, 0)');

  const chart1 = new Chart(ctx1, {
    type: 'line',
    data: {
      labels: @json($order['latest_week']['period']),
      datasets: [{
        label: '{{ __('panel/dashboard.order_quantity') }}',
        data: @json($order['latest_week']['totals']),
        responsive: true,
        backgroundColor : orderGradient,
        borderColor : "#6F4E37",
        fill: true,
        lineTension: 0.4,
        datasetStrokeWidth: 3,
        pointBackgroundColor: '#6F4E37',
        pointDotStrokeWidth: 4,
        pointHoverBorderWidth: 8,
        tension: 0.1
      }]
    },
    options: options
  });
</script>
@endpush
