// ProductSelector.js
const ProductSelector = {
  init: function (callback, options = {}) {
    const defaultOptions = {
      keyword: '',
    };

    const finalOptions = { ...defaultOptions, ...options };

    // Register global callback directly
    window.productSelectorCallback = function (product) {
      if (typeof callback === "function") {
        callback(product);
      }
    };


    layer.open({
      type: 2,
      title: options.title || (typeof lang !== 'undefined' ? lang.product_selector : "Product Selector"),
      shadeClose: false,
      shade: 0.8,
      area: ["800px", "800px"],
      content: urls.base_url + '/products/selector',
    });
  }
};

export default ProductSelector;
