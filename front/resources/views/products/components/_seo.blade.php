{{-- ================= SEO PRO ================= --}}

<link rel="canonical" href="{{ url()->current() }}"/>

<meta name="robots" content="index, follow">

{{-- OpenGraph --}}
<meta property="og:type" content="product">
<meta property="og:title" content="{{ $product->fallbackName() }}">
<meta property="og:description"
      content="{{ strip_tags($product->fallbackName('summary')) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $product->image ? image_origin($product->image) : asset(system_setting('front_logo', 'images/logo.svg')) }}">
<meta property="og:site_name" content="Travalorics Coffee">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $product->fallbackName() }}">
<meta name="twitter:description"
      content="{{ strip_tags($product->fallbackName('summary')) }}">
<meta name="twitter:image" content="{{ $product->image ? image_origin($product->image) : asset(system_setting('front_logo', 'images/logo.svg')) }}">

{{-- Structured Data Generation (JSON-LD) --}}
@php
    // 1. Product Schema
    $productSchema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->fallbackName(),
        'image' => $product->image ? [$product->image] : [],
        'description' => strip_tags($product->fallbackName('summary') ?? ''),
        'sku' => $sku['code'] ?? '',
        'offers' => [
            '@type' => 'Offer',
            'url' => url()->current(),
            'priceCurrency' => system_setting('currency_code', 'USD'),
            'price' => $product->masterSku?->price ?? 0,
            'availability' => (isset($sku['quantity']) && $sku['quantity'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
        ]
    ];

    if ($product->seo_rating && $product->seo_reviews) {
        $productSchema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $product->seo_rating,
            'reviewCount' => $product->seo_reviews
        ];
    }

    // Conditionally Add Brand
    if ($product->brand) {
        $productSchema['brand'] = [
            '@type' => 'Brand',
            'name' => $product->brand?->name ?? 'Travalorics Coffee'
        ];
    }

    // Conditionally Add Additional Properties (e.g. Origin, Roast Level, Flavor Notes from $attributes)
    if (!empty($attributes)) {
        $productSchema['additionalProperty'] = [];
        foreach($attributes as $group) {
            if (!empty($group['attributes'])) {
                foreach($group['attributes'] as $item) {
                    $productSchema['additionalProperty'][] = [
                        '@type' => 'PropertyValue',
                        'name' => $item['attribute'] ?? '',
                        'value' => $item['attribute_value'] ?? ''
                    ];
                }
            }
        }
    }

    // 2. BreadcrumbList Schema
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => __('front/home.home'),
                'item' => url('/')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $product->categories->first()?->fallbackName() ?? 'Coffee',
                // 'item' => $product->categories->first() ? $product->categories->first()->url : url('/') // if needed
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->fallbackName(),
                'item' => url()->current()
            ]
        ]
    ];
@endphp

{{-- Google Product Schema --}}
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- Google Breadcrumb Schema --}}
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
