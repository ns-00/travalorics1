@extends('panel::layouts.app')
@section('title', __('panel/menu.dashboard'))
@section('subtitle', 'Overview of your store')

@push('header')
<script src="{{ asset('vendor/chart/chart.min.js') }}"></script>
@endpush

@section('content')
<!-- Stats -->
<div class="stats-grid">
    @foreach ($cards as $card)
    <div class="stats-card">
        <div class="stats-icon {{ $card['class'] ?? 'primary' }}">
            <i class="{{ $card['icon'] }}"></i>
        </div>
        <div class="stats-label">{{ $card['title'] }}</div>
        <div class="stats-value">{{ $card['quantity'] }}</div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <!-- Chart -->
    <div class="col-12 col-lg-8">
        <div class="panel-card">
            <div class="card-header">
                <span>{{ __('panel/dashboard.order_trends') }}</span>
                <span class="text-muted" style="font-size: 12px;">Last 7 days</span>
            </div>
            <div class="card-body" style="height: 280px;">
                <canvas id="chart-orders"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-12 col-lg-4">
        <div class="panel-card">
            <div class="card-header">
                <span>{{ __('panel/dashboard.top_products') }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($top_sale_products as $product)
                    <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom" style="border-color: var(--gray-100);">
                        <span class="text-muted" style="font-size: 12px; min-width: 28px; font-weight: 600;">
                            {{ $loop->iteration }}
                        </span>
                        <div class="wh-36 rounded overflow-hidden border flex-shrink-0">
                            <img src="{{ image_resize($product['image'], 40, 40) }}" 
                                 alt="{{ $product['name'] }}" 
                                 class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 text-truncate" style="font-size: 13px; font-weight: 500;">
                            {{ $product['summary'] }}
                        </div>
                        <span class="fw-bold" style="color: var(--success); font-size: 13px;">
                            {{ $product['order_count'] }}
                        </span>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-box-seam fs-2 d-block mb-2 opacity-25"></i>
                        <span style="font-size: 13px;">{{ __('panel/common.no_data') }}</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<img src="{{ dashboard_url() }}" class="d-none" alt="dashboard"/>
@endsection

@push('footer')
<script>
    const ctx = document.getElementById('chart-orders').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($order['latest_week']['period']),
            datasets: [{
                label: '{{ __('panel/dashboard.order_quantity') }}',
                data: @json($order['latest_week']['totals']),
                borderColor: '#1B4D3E',
                borderWidth: 2.5,
                fill: true,
                backgroundColor: 'rgba(27, 77, 62, 0.06)',
                tension: 0.4,
                pointBackgroundColor: '#1B4D3E',
                pointRadius: 3,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
</script>
@endpush
