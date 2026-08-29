@extends('layouts.app')
@section('body-class', 'page-categories')

@section('title', \Travalorics\Common\Libraries\MetaInfo::getInstance($category)->getTitle())
@section('description', \Travalorics\Common\Libraries\MetaInfo::getInstance($category)->getDescription())
@section('keywords', \Travalorics\Common\Libraries\MetaInfo::getInstance($category)->getKeywords())

@section('content')
  <x-front-breadcrumb type="category" :value="$category" :showFilter="true"/>

  @hookinsert('category.show.top')

  <div class="container">
    <div class="row">
      <div class="col-12 col-md-3">
        @include('shared.filter_sidebar')
      </div>

      <div class="col-12 col-md-9">
        @include('categories.partials._intro', ['category' => $category])
        @include('categories.partials._subcategories', ['category' => $category])
        @include('categories.partials._controls', ['products' => $products, 'per_page_items' => $per_page_items])
        @include('categories.partials._products', ['products' => $products])
        @include('categories.partials._description', ['category' => $category])
      </div>
    </div>

    @hookinsert('category.show.bottom')

  </div>
@endsection


@push('footer')
  <style>
    /* تأثير النبض للتحميل */
    @keyframes skeleton-pulse {
        0% { background-color: #f2f2f2; }
        50% { background-color: #e0e0e0; }
        100% { background-color: #f2f2f2; }
    }

    .skeleton-card {
        height: 350px;
        border-radius: 16px;
        animation: skeleton-pulse 1.5s infinite ease-in-out;
        margin-bottom: 24px;
        background-color: #f2f2f2;
    }
  </style>
  <script>
    $('.form-select, input[name="style_list"]').change(function(event) {
      filterProductData();
    });

    function filterProductData() {
      // إخفاء المنتجات الحالية وإظهار الهياكل المؤقتة
      $('.product-list, .row.gx-2, .row.gx-3').html(
          Array(6).fill('<div class="col-6 col-md-4"><div class="skeleton-card"></div></div>').join('')
      );

      let url = inno.removeURLParameters(window.location.href, 'price', 'sort', 'order');
      let order = $('.order-select').val();
      let perPage = $('.per-page-select').val();
      let styleList = $('input[name="style_list"]:checked').val();

      if (order) {
        let orderKeys = order.split('|');
        url = inno.updateQueryStringParameter(url, 'sort', orderKeys[0]);
        url = inno.updateQueryStringParameter(url, 'order', orderKeys[1]);
      }

      if (perPage) {
        url = inno.updateQueryStringParameter(url, 'per_page', perPage);
      }

      if (styleList) {
        url = inno.updateQueryStringParameter(url, 'style_list', styleList);
      }

      location = url;
    }

    function filterAttrChecked(data) {
      let filterAtKey = [];
      data.forEach((item) => {
        let checkedAtValues = [];
        item.values.forEach((val) => val.selected ? checkedAtValues.push(val.id) : '')
        if (checkedAtValues.length) {
          filterAtKey.push(`${item.id}:${checkedAtValues.join(',')}`)
        }
      })

      return filterAtKey.join('|')
    }
  </script>
@endpush
