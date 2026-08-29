@push('header')
  <script src="{{ asset('vendor/vue/3.5/vue.global.prod.js') }}"></script>
  <script src="{{ asset('vendor/vuedraggable/sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/vuedraggable/vuedraggable.umd.min.js') }}"></script>
@endpush

<div class="card variants-box mb-3" id="variants-box">
  <div class="card-header">
    <h5 class="card-title mb-0">{{ __('panel/product.variant') }}</h5>
  </div>

  <div class="card-body py-0">
    <div class="variant-wrap" v-if="variants.length">
      <input type="hidden" name="variants" :value="JSON.stringify(variants)">
      <input type="hidden" name="skus" :value="JSON.stringify(skus)">
      <draggable
        v-model="variants"
        handle=".drag-variants-handle"
        :animation="300"
        @end="dragVariantsEnd"
        item-key="index">
        <template #item="{element: variant, index}">
          <div class="variant-item">
            <div class="variant-data" v-if="!variant.variantFormShow">
              <div class="variant-header d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center">
                  <div class="icon drag-variants-handle me-2"><i class="bi bi-grip-vertical"></i></div>
                  <div class="title">@{{ variant.name[defaultLocale] || getFirstAvailableLocaleValue(variant.name) }}</div>
                  <label class="form-check-label ms-3">
                    <input type="checkbox" class="form-check-input" v-model="variant.isImage" @change="toggleVariantImage(index)">
                    {{ __('panel/product.is_image_variant') }}
                  </label>
                </div>
                <div class="action-buttons">
                  <button type="button" class="btn btn-outline-primary btn-sm" @click="openVariantDialog(index, null)">{{ __('panel/common.edit') }}</button>
                  <button type="button" class="btn btn-outline-danger btn-sm ms-2" @click="deleteVariant(index)">{{ __('panel/common.delete') }}</button>
                </div>
              </div>
              <div class="variant-values">
                <div class="variant-values-container">
                  <div class="variant-values-list d-flex flex-wrap">
                    <div v-for="(value, valueIndex) in variant.values" :key="valueIndex" 
                         class="variant-value-item me-2 mb-2 position-relative" 
                         @dblclick="openVariantDialog(index, valueIndex)">
                      <div class="variant-value-delete-btn" @click="deleteVariantValue(index, valueIndex)">
                        <i class="bi bi-x-circle-fill"></i>
                      </div>
                      <div v-if="variant.isImage" class="variant-image-container open-file-manager" 
                           @click="selectVariantValueImage(index, valueIndex)">
                        <img v-if="value.image" :src="thumbnail(value.image)" class="variant-value-image">
                        <i v-else class="bi bi-image variant-placeholder-icon"></i>
                      </div>
                      <span class="variant-value-name">@{{ value.name[defaultLocale] || getFirstAvailableLocaleValue(value.name) }}</span>
                    </div>
                    <div class="variant-value-item me-2 mb-2 add-value-btn" @click="openVariantDialog(index, -1)">
                      <i class="bi bi-plus-circle"></i>
                    </div>
                  </div>
                  <div class="mt-2">
                    <small class="text-muted">
                      <i class="bi bi-info-circle me-1"></i>
                      {{ __('panel/product.variant_value_edit_tip') }}
                    </small>
                  </div>
                </div>
              </div>
            </div>
            <div class="add-variant-form" v-else>
              <div class="mb-3 add-variant-title">
                <div class="variant-label">
                  <label class="form-label">{{ __('panel/product.variant_name') }}</label>
                  <div class="v-locales-input">
                    <div v-for="locale in locales" class="input-group" :key="locale.code">
                      <span class="input-group-text"><img :src="'/images/flag/'+ locale.code +'.png'" class="img-fluid">@{{ locale.name }}</span>
                      <input type="text" class="form-control" v-model="variant.name[locale.code]" placeholder="{{ __('panel/product.variant_name_help') }}">
                    </div>
                    <span class="text-12 text-danger" style="margin-left: 100px" v-if="variant.error"><i class="bi bi-exclamation-circle"></i> {{ __('panel/common.verify_required') }}</span>
                  </div>
                    @hookinsert('panel.product.edit.variant_name.after')
                </div>
              </div>
              <div class="add-variant-values">
                <label class="form-label">{{ __('panel/product.variant_value') }}</label>
                <div class="add-variant-value">
                  <div class="add-variant-value-item" v-for="(value, index) in variant.values" :key="index">
                    <div class="icon"><i class="bi bi-grip-vertical"></i></div>
                    <div class="v-locales-input variant-value">
                      <div v-for="locale in locales" class="input-group" :key="locale.code">
                        <span class="input-group-text"><img :src="'/images/flag/'+ locale.code +'.png'" class="img-fluid">@{{ locale.name }}</span>
                        <input type="text" class="form-control" v-model="value.name[locale.code]" placeholder="{{ __('panel/product.variant_value_help') }}" ref="variantValue">
                      </div>
                      <span class="text-12 text-danger" style="margin-left: 100px" v-if="value.error"><i class="bi bi-exclamation-circle"></i> {{ __('panel/common.verify_required') }}</span>
                    </div>
                    <div class="delete-icon" v-if="variant.values.length > 1" @click="variant.values.splice(index, 1)"><i class="bi bi-trash"></i></div>
                    <div class="delete-icon" v-else></div>
                  </div>
                  <div class="add-variant-btns">
                    <div class="text-primary text-12 mb-3">
                      <div class="d-inline-block cursor-pointer" @click="addVariantValue(index)"><i class="bi bi-plus-lg"></i> {{ __('panel/product.add_variant_value') }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                      <button type="button" class="btn btn-outline-danger" @click="deleteVariant(index)">{{ __('panel/common.delete') }}</button>
                      <button type="button" class="btn btn-outline-primary" @click="saveVariant(index)">{{ __('panel/common.btn_save') }}</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </draggable>
    </div>
    <div :class="['text-primary add-variant', !variants.length ? 'no-variants' : '']">
      <div class="d-inline-block cursor-pointer" @click="openVariantDialog(-1, null)"><i class="bi bi-plus-square me-1"></i> {{ __('panel/product.add_variant') }}</div>
    </div>
    <div class="variant-skus-wrap" v-if="smallVariants.length">
      <div class="batch-settings-panel mb-3">
        <div class="card shadow-sm" style="border: none;">
          <div class="card-body py-3">
            <div class="mb-2" v-if="variants.length > 0">
              <label class="form-label small fw-bold mb-2">{{ __('panel/product.sku_batch_setting') }}</label>
              <div class="variant-selector-container">
                <div class="row g-2 mb-2" v-for="(variant, vIndex) in variants" :key="vIndex">
                  <div class="col-md-2">
                    <label class="form-label small mb-1">@{{ getFirstAvailableLocaleValue(variant.name) }}</label>
                  </div>
                  <div class="col-md-10">
                    <div class="d-flex flex-wrap gap-1 align-items-center">
                      <div class="form-check me-2" v-for="(value, valueIndex) in variant.values" :key="valueIndex">
                        <input class="form-check-input" type="checkbox" 
                               :id="`variant_${vIndex}_${valueIndex}`"
                               v-model="batchData.selectedVariants[vIndex]"
                               :value="valueIndex">
                        <label class="form-check-label" :for="`variant_${vIndex}_${valueIndex}`">
                          @{{ getFirstAvailableLocaleValue(value.name) }}
                        </label>
                      </div>
                      <button type="button" class="btn btn-outline-primary btn-sm ms-2" 
                              @click="selectAllVariantValues(vIndex)">
                        {{ __('panel/product.select_all') }}
                      </button>
                      <button type="button" class="btn btn-outline-secondary btn-sm" 
                              @click="clearVariantSelection(vIndex)">
                        {{ __('panel/product.clear') }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <!-- SKU编码前缀 -->
              <div class="col-md-2">
                <label class="form-label small mb-1">SKU {{ __('panel/product.bulk_fill') }}</label>
                <input type="text" class="form-control form-control-sm" v-model="batchData.skuPrefix" 
                       placeholder="{{ __('panel/product.bulk_fill_sku') }}" style="height: 31px;">
              </div>
              
              <!-- 价格 -->
              <div class="col-md-2">
                <label class="form-label small mb-1">{{ __('panel/product.price') }}</label>
                <input type="number" class="form-control form-control-sm" v-model="batchData.price" 
                       placeholder="{{ __('panel/product.bulk_fill_price') }}" min="0" @input="validateBatchPrice" style="height: 31px;">
              </div>
              
              <!-- 原价 -->
              <div class="col-md-2">
                <label class="form-label small mb-1">{{ __('panel/product.origin_price') }}</label>
                <input type="number" class="form-control form-control-sm" v-model="batchData.originPrice" 
                       placeholder="{{ __('panel/product.bulk_fill_origin_price') }}" min="0" @input="validateBatchOriginPrice" style="height: 31px;">
              </div>
              
              <!-- 型号 -->
              <div class="col-md-2">
                <label class="form-label small mb-1">{{ __('panel/product.model') }}</label>
                <input type="text" class="form-control form-control-sm" v-model="batchData.model" 
                       placeholder="{{ __('panel/product.bulk_fill_model') }}" style="height: 31px;">
              </div>
              
              <!-- 数量 -->
              <div class="col-md-2">
                <label class="form-label small mb-1">{{ __('panel/product.quantity') }}</label>
                <input type="number" class="form-control form-control-sm" v-model="batchData.quantity" 
                       placeholder="{{ __('panel/product.bulk_fill_quantity') }}" min="0" @input="validateBatchQuantity" style="height: 31px;">
              </div>
              
              <!-- SKU图片 -->
              <div class="col-md-2">
                <label class="form-label small mb-1">{{ __('panel/product.sku_image') }}</label>
                <div class="d-flex align-items-center" style="height: 31px;">
                  <input type="hidden" v-model="batchData.image">
                  <div class="image-preview me-2" v-if="batchData.image" style="width: 25px; height: 25px; border-radius: 3px; overflow: hidden; border: 1px solid #ddd;">
                    <img :src="batchData.image" style="width: 100%; height: 100%; object-fit: cover;">
                  </div>
                  <button type="button" class="btn btn-outline-secondary btn-sm" @click="selectBatchImage" style="font-size: 11px; padding: 2px 8px;">
                    <i class="bi bi-image me-1"></i>{{ __('panel/product.select_image') }}
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-sm ms-1" @click="clearBatchImage" v-if="batchData.image" style="font-size: 11px; padding: 2px 6px;">
                    <i class="bi bi-x"></i>
                  </button>
                </div>
              </div>
              
              <!-- 批量设置按钮 -->
              <div class="col-md-2">
                <label class="form-label small mb-1" style="visibility: hidden;">占位</label>
                <button type="button" class="btn btn-success w-100 fw-bold" @click="batchApplySelected" style="height: 31px; font-size: 12px;">
                  <i class="bi bi-lightning-charge-fill me-1"></i>{{ __('panel/product.bulk_fill') }}
                </button>
              </div>
              
              @hookinsert('panel.product.edit.sku.batch.input.item.after')
            </div>
          </div>
        </div>
      </div>
      
      <!-- SKU数据表格 -->
      <div class="variant-skus-table table-responsive">
        <table class="table align-middle table-bordered">
          <thead class="table-light">
            <tr>
              <th style="min-width: 220px">{{ __('panel/product.variant') }}</th>
              <th>SKU Code</th>
              <th>{{ __('panel/product.price') }}</th>
              <th>{{ __('panel/product.origin_price') }}</th>
              <th>{{ __('panel/product.model') }}</th>
              <th>{{ __('panel/product.quantity') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sku, index) in skus" :key="index">
              <td>
                <div class="sku-image-name">
                  <div class="up-variant-image" @click="upVariantImage(null, index)">
                    <img :src="thumbnail(sku.image, 50, 50)" v-if="sku.image" class="img-fluid">
                    <i class="bi bi-folder-plus" v-else></i>
                  </div>
                  <div>
                    <div class="sku-text">@{{ sku.text }}</div>
                    <div class="up-master text-12">
                      <span v-if="sku.is_default" class="text-success">
                        <i class="bi bi-check-circle-fill"></i> {{ __('panel/product.main_variant') }}
                      </span>
                      <span class="opacity-50 cursor-pointer" v-else @click="setMasterSku(index)">
                        <i class="bi bi-circle"></i> {{ __('panel/product.main_variant') }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <input type="text" :class="['form-control form-control-sm', sku.error ? 'is-invalid other-error' : '']"
                  v-model="sku.code" placeholder="SKU Code">
                <div class="invalid-feedback">{{ __('panel/product.error_sku_repeat') }}</div>
              </td>
              <td>
                <input type="text" class="form-control form-control-sm"
                  v-model="sku.price" placeholder="{{ __('panel/product.price') }}"
                  @input="validatePrice(sku)">
                  @hookinsert('panel.product.edit.sku.input.item.price.after')
              </td>
              <td>
                <input type="text" class="form-control form-control-sm"
                  v-model="sku.origin_price" placeholder="{{ __('panel/product.origin_price') }}"
                  @input="validateOriginPrice(sku)">
              </td>
              <td>
                <input type="text" class="form-control form-control-sm"
                  v-model="sku.model" placeholder="{{ __('panel/product.model') }}">
              </td>
              <td>
                <input type="text" class="form-control form-control-sm"
                  v-model="sku.quantity" placeholder="{{ __('panel/product.quantity') }}"
                  @input="validateQuantity(sku)">
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
    @hookinsert('panel.product.edit.variant.after')

    <!-- 规格/规格值编辑弹窗 -->
    <div class="modal fade" id="variantEditModal" tabindex="-1" aria-hidden="true" v-if="dialogVariables.show">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">@{{ dialogVariables.title }}</h5>
            <button type="button" class="btn-close" @click="closeVariantDialog"></button>
          </div>
          <div class="modal-body">
            <form ref="variantForm">
              <div class="mb-3">
                <div v-for="locale in locales" :key="locale.code" class="input-group mb-2">
                  <div class="input-group-text">
                    <div class="d-flex align-items-center wh-20">
                      <img :src="'/images/flag/'+ locale.code +'.png'" 
                           class="img-fluid" 
                           :alt="locale.name">
                    </div>
                  </div>
                  <input type="text" class="form-control" 
                         v-model="dialogVariables.form.name[locale.code]" 
                         :placeholder="'{{ __('panel/product.name') }}'"
                         :aria-label="locale.name"
                         :data-locale="locale.code">
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeVariantDialog">{{ __('panel/common.cancel') }}</button>
            <button type="button" class="btn btn-primary" @click="saveVariantDialog">{{ __('panel/common.save') }}</button>
          </div>
        </div>
      </div>
    </div>
</div>

@push('footer')
<script>
  const { createApp, ref, watch, onMounted, getCurrentInstance, nextTick } = Vue;
  const draggable = window.vuedraggable;

  const $locales = @json(locales());
  const localesFill = (text) => {
    let obj = {};
    $locales.map(e => {
      obj[e.code] = text
    });
    return obj;
  }

  let variantsBoxApp = createApp({
    components: { draggable },

    setup() {
      const locales = $locales;
      const defaultLocale = @json(panel_locale_code());
      const showAllVariant = ref(false);
      const mainVariantKey = ref(0);
      
      const variants = ref(@json(old('variants', $product->variables ?? [])));
      if (typeof variants.value === 'string') {
        variants.value = JSON.parse(variants.value);
      }
      
      const skus = ref((() => {
        let rawSkus = @json(old('skus', $skus ?? []));
        if (typeof rawSkus === 'string') {
          try { rawSkus = JSON.parse(rawSkus); } catch(e) { rawSkus = []; }
        }
        return rawSkus;
      })());
      
      const smallVariants = ref([]);
      const batchData = ref({
        skuPrefix: '',
        price: '',
        originPrice: '',
        model: '',
        quantity: '',
        image: '',
        selectedVariants: []
      });

      const dialogVariables = ref({
        show: false,
        variantIndex: null,
        variantValueIndex: null,
        title: '',
        form: { name: {} }
      });

      watch([mainVariantKey, variants], () => {
        if (!variants.value.length) {
          $('.skus-single-box').removeClass('d-none');
        } else {
          $('.skus-single-box').addClass('d-none');
        }
        if (variants.value.length == 1 && isObjectValuesEmpty(variants.value[0].values[0].name)) {
          return;
        }
        generateSku();
        smallVariantsFormat();
      });

      watch(skus, () => {
        validateSkus();
      }, { deep: true });

      watch(variants, (newValue) => {
        if (newValue.length > 0) {
          const singleSkuPrice = $('input[name="skus[0][price]"]').val();
          const singleSkuQuantity = $('input[name="skus[0][quantity]"]').val();
          const singleSkuCode = $('input[name="skus[0][code]"]').val();

          if (singleSkuPrice || singleSkuQuantity || singleSkuCode) {
            const firstSku = skus.value[0];
            if (firstSku) {
              firstSku.price = singleSkuPrice || '';
              firstSku.quantity = singleSkuQuantity || '';
              firstSku.code = singleSkuCode || '';
              firstSku.is_default = 1;
            }
            $('input[name="skus[0][price]"]').val('');
            $('input[name="skus[0][quantity]"]').val('');
            $('input[name="skus[0][code]"]').val('');
          }
        } else {
          const defaultSku = skus.value.find(sku => sku.is_default === 1);
          if (defaultSku) {
            $('input[name="skus[0][price]"]').val(defaultSku.price);
            $('input[name="skus[0][quantity]"]').val(defaultSku.quantity);
            $('input[name="skus[0][code]"]').val(defaultSku.code);
          }
        }
      }, { deep: true });

      onMounted(() => {
        generateSku();
        smallVariantsFormat();
        $('#product-form').on('submit', function(e) {
          if (!validateForm()) {
            e.preventDefault();
            layer.msg('Please fill in single specification information or add multiple specification product information', {icon: 2});
            return false;
          }
        });
      });

      const smallVariantsFormat = () => {
        if (variants.value.length === 0) {
          smallVariants.value = [];
          return;
        }
        smallVariants.value = skus.value.map((sku, index) => ({
          ...sku,
          init_index: index,
          show_variant: false,
          sku_quantity: null
        }));
      };

      const addVariantValue = (index) => {
        variants.value[index].values.push({name: localesFill(''), error: false, image: ''});
      };

      const addVariant = () => {
        variants.value.push({
          name: localesFill(''),
          error: false,
          variantFormShow: true,
          isImage: false,
          values: [{name: localesFill(''), error: false, image: ''}],
        });
        batchData.value.selectedVariants.push([]);
      };

      const deleteVariant = (index) => {
        variants.value.splice(index, 1);
        batchData.value.selectedVariants.splice(index, 1);
        if (index < mainVariantKey.value) {
          mainVariantKey.value--;
        } else if (index === mainVariantKey.value) {
          mainVariantKey.value = 0;
        }
      };

      const saveVariant = (index) => {
        let isError = true;
        variants.value.forEach((e) => {
          if (isObjectValuesEmpty(e.name)) { e.error = true; isError = false; } else { e.error = false; }
          e.values.forEach((value) => {
            if (isObjectValuesEmpty(value.name)) { value.error = true; isError = false; } else { value.error = false; }
          });
        });
        if (!isError) return;
        variants.value[index].variantFormShow = false;
        localStorage.setItem('variants', JSON.stringify(variants.value));
      };

      const getFirstAvailableLocaleValue = (localeObject) => {
        if (!localeObject) return '';
        const systemDefaultLocale = @json(setting_locale_code());
        if (localeObject[systemDefaultLocale]) return localeObject[systemDefaultLocale];
        for (const locale of locales) {
          if (localeObject[locale.code] && localeObject[locale.code].trim() !== '') return localeObject[locale.code];
        }
        return '';
      };

      const generateSku = () => {
        if (variants.value.length === 0) return;
        let mainVariant = variants.value[mainVariantKey.value];
        let tempVariants = [mainVariant, ...variants.value.filter((e, i) => i !== mainVariantKey.value)];
        let sku = [];
        let skuVariantsLength = tempVariants.length;
        let skuVariantsIndex = Array(skuVariantsLength).fill(0);
        let skuVariantsValues = tempVariants.map(e => e.values.length);
        const totalCombinations = skuVariantsValues.reduce((a, b) => a * b);

        for (let i = 0; i < totalCombinations; i++) {
          let skuItem = {
            code: skus.value[i] ? skus.value[i].code : '',
            price: skus.value[i] ? skus.value[i].price : '',
            quantity: skus.value[i] ? skus.value[i].quantity : '',
            image: skus.value[i] ? skus.value[i].image : '',
            image_url: skus.value[i] ? skus.value[i].image_url : '',
            model: skus.value[i] ? skus.value[i].model : '',
            origin_price: skus.value[i] ? skus.value[i].origin_price : '',
            is_default: skus.value[i] ? skus.value[i].is_default : 0,
            error: false,
            text: '',
            variants: []
          };
          for (let j = 0; j < skuVariantsLength; j++) {
            skuItem.variants.push(skuVariantsIndex[j]);
            const valueName = tempVariants[j].values[skuVariantsIndex[j]].name[defaultLocale] || getFirstAvailableLocaleValue(tempVariants[j].values[skuVariantsIndex[j]].name);
            skuItem.text += ' ' + valueName + ' /';
          }
          skuItem.text = skuItem.text.slice(0, -1);
          sku.push(skuItem);
          for (let j = skuVariantsLength - 1; j >= 0; j--) {
            if (skuVariantsIndex[j] < skuVariantsValues[j] - 1) {
              skuVariantsIndex[j]++;
              break;
            } else {
              skuVariantsIndex[j] = 0;
            }
          }
        }
        let isMaster = sku.filter(e => e.is_default == 1);
        if (isMaster.length === 0) sku[0].is_default = 1;
        skus.value = sku;
        initializeVariantSelectors();
      };

      const modifySku = (init_index, index, type) => {
        let sku_quantity = smallVariants.value[index].sku_quantity;
        let sku = smallVariants.value[index];
        let tempSkus = skus.value.slice(init_index * sku_quantity, (init_index + 1) * sku_quantity);
        tempSkus.forEach(e => { e[type] = sku[type]; });
        if (typeof init_index != 'undefined') {
          let sameSku = skus.value.filter(e => e.code.split('-')[0] === sku.code);
          sameSku.forEach((e, i) => { e.code = sku.code + '-' + i; });
        }
      };

      const upVariantImage = (init_index, index) => {
        inno.fileManagerIframe((file) => {
          if (file.path) skus.value[index].image = file.path;
        }, { type: 'image', multiple: false });
      };

      const dragVariantsEnd = (evt) => {
        const oldIndex = evt.oldIndex;
        const newIndex = evt.newIndex;
        if (oldIndex === mainVariantKey.value) {
          mainVariantKey.value = newIndex;
        } else if (oldIndex < mainVariantKey.value && newIndex >= mainVariantKey.value) {
          mainVariantKey.value--;
        } else if (oldIndex > mainVariantKey.value && newIndex <= mainVariantKey.value) {
          mainVariantKey.value++;
        }
      };

      const thumbnail = (image) => {
        const asset = document.querySelector('meta[name="asset"]').content;
        if (!image) return 'image/placeholder.png';
        if (image.indexOf('http') === 0) return image;
        return asset + image;
      };

      const setMasterSku = (index) => {
        skus.value.forEach(e => e.is_default = 0);
        getSkusItem(index).is_default = 1;
      };

      const getSkusItem = (index) => {
        return skus.value.find(e => e.variants.toString() === smallVariants.value[index].variants.toString());
      };

      const validateVariants = () => {
        variants.value.forEach(e => {
          e.error = isObjectValuesEmpty(e.name);
          e.values.forEach(value => { value.error = isObjectValuesEmpty(value.name); });
        });
      };

      const validateSkus = () => {
        skus.value.forEach(e => {
          const sameSku = skus.value.filter(s => s.code === e.code);
          e.error = sameSku.length > 1;
        });
      };

      const allVariantEC = () => {
        showAllVariant.value = !showAllVariant.value;
        smallVariants.value.forEach(e => { e.show_variant = showAllVariant.value; });
        smallVariantsFormat();
      };

      const validateForm = () => {
        const singleSkuPrice = $('input[name="skus[0][price]"]').val();
        const singleSkuQuantity = $('input[name="skus[0][quantity]"]').val();
        const hasValidVariants = variants.value.length > 0 && skus.value.some(sku => sku.price && sku.quantity && sku.is_default === 1);
        return hasValidVariants || (singleSkuPrice && singleSkuQuantity);
      };

      const validateBatchPrice = () => {
        if (batchData.value.price < 0) batchData.value.price = 0;
        if (batchData.value.originPrice && parseFloat(batchData.value.price) > parseFloat(batchData.value.originPrice)) batchData.value.price = batchData.value.originPrice;
      };
      const validateBatchOriginPrice = () => {
        if (batchData.value.originPrice < 0) batchData.value.originPrice = 0;
        if (batchData.value.price && parseFloat(batchData.value.originPrice) < parseFloat(batchData.value.price)) batchData.value.originPrice = batchData.value.price;
      };
      const validateBatchQuantity = () => {
        if (batchData.value.quantity < 0) batchData.value.quantity = 0;
      };
      const validatePrice = (sku) => {
        let price = parseFloat(sku.price);
        if (isNaN(price) || price < 0) sku.price = '0';
        if (sku.origin_price && price > parseFloat(sku.origin_price)) sku.price = sku.origin_price;
      };
      const validateOriginPrice = (sku) => {
        let originPrice = parseFloat(sku.origin_price);
        if (isNaN(originPrice) || originPrice < 0) sku.origin_price = '0';
        if (sku.price && originPrice < parseFloat(sku.price)) sku.origin_price = sku.price;
      };
      const validateQuantity = (sku) => {
        let quantity = parseInt(sku.quantity);
        if (isNaN(quantity) || quantity < 0) sku.quantity = '0';
      };

      const batchFillSkuCode = () => {
        if (!batchData.value.skuPrefix) { layer.msg('Please enter SKU prefix', {icon: 2}); return; }
        skus.value.forEach((sku, index) => {
          const suffix = String(index + 1).padStart(2, '0');
          sku.code = `${batchData.value.skuPrefix}-${suffix}`;
        });
        layer.msg('SKU codes have been filled', {icon: 1});
      };

      const batchFillColumn = (column) => {
        if (!batchData.value[column]) { layer.msg('Please enter a value to fill', {icon: 2}); return; }
        const columnMap = { price: 'price', originPrice: 'origin_price', model: 'model', quantity: 'quantity' };
        skus.value.forEach(sku => { sku[columnMap[column]] = batchData.value[column]; });
        layer.msg('Batch fill completed', {icon: 1});
      };

      const initializeVariantSelectors = () => {
        batchData.value.selectedVariants = variants.value.map(() => []);
      };
      const selectAllVariantValues = (variantIndex) => {
        if (!batchData.value.selectedVariants[variantIndex]) batchData.value.selectedVariants[variantIndex] = [];
        batchData.value.selectedVariants[variantIndex] = variants.value[variantIndex].values.map((_, index) => index);
      };
      const clearVariantSelection = (variantIndex) => {
        if (batchData.value.selectedVariants[variantIndex]) batchData.value.selectedVariants[variantIndex] = [];
      };
      const isSkuMatchingSelection = (sku) => {
        if (!batchData.value.selectedVariants.length) return true;
        return batchData.value.selectedVariants.every((selectedValues, variantIndex) => {
          if (!selectedValues || selectedValues.length === 0) return true;
          return selectedValues.includes(sku.variants[variantIndex]);
        });
      };
      const getMatchingSKUs = () => skus.value.filter(isSkuMatchingSelection);
      const batchApplySelected = () => {
        const matchingSKUs = getMatchingSKUs();
        let appliedCount = 0;
        if (matchingSKUs.length === 0) { layer.msg('没有匹配的SKU，请检查规格选择', {icon: 2}); return; }
        if (batchData.value.skuPrefix) {
          matchingSKUs.forEach((sku, index) => { sku.code = `${batchData.value.skuPrefix}-${String(index+1).padStart(2,'0')}`; });
          appliedCount++;
        }
        if (batchData.value.price) { matchingSKUs.forEach(sku => sku.price = batchData.value.price); appliedCount++; }
        if (batchData.value.originPrice) { matchingSKUs.forEach(sku => sku.origin_price = batchData.value.originPrice); appliedCount++; }
        if (batchData.value.model) { matchingSKUs.forEach(sku => sku.model = batchData.value.model); appliedCount++; }
        if (batchData.value.quantity) { matchingSKUs.forEach(sku => sku.quantity = batchData.value.quantity); appliedCount++; }
        if (batchData.value.image) { matchingSKUs.forEach(sku => sku.image = batchData.value.image); appliedCount++; }
        if (appliedCount === 0) { layer.msg('请至少填写一个字段进行批量设置', {icon: 2}); return; }
        layer.msg(`批量设置完成，已应用 ${appliedCount} 个字段到 ${matchingSKUs.length} 个SKU`, {icon: 1});
      };
      const selectBatchImage = () => {
        inno.fileManagerIframe((file) => { if (file.path) batchData.value.image = file.path; }, { type: 'image', multiple: false });
      };
      const clearBatchImage = () => { batchData.value.image = ''; };

      const openVariantDialog = (variantIndex, valueIndex = null) => {
        dialogVariables.value.variantIndex = variantIndex;
        dialogVariables.value.variantValueIndex = valueIndex;
        let name = {};
        let title = '';
        if (variantIndex === -1) { name = localesFill(''); title = '{{ __('panel/product.add_variant') }}'; }
        else if (valueIndex === null) {
          name = variants.value[variantIndex]?.name || localesFill('');
          title = '{{ __('panel/product.edit_variant') }}';
        } else if (valueIndex === -1) { name = localesFill(''); title = '{{ __('panel/product.add_variant_value') }}'; }
        else {
          name = variants.value[variantIndex]?.values[valueIndex]?.name || localesFill('');
          title = '{{ __('panel/product.edit_variant_value') }}';
        }
        dialogVariables.value.form.name = JSON.parse(JSON.stringify(name));
        dialogVariables.value.title = title;
        dialogVariables.value.show = true;
        nextTick(() => {
          const modal = new bootstrap.Modal(document.getElementById('variantEditModal'));
          modal.show();
        });
      };

      const closeVariantDialog = () => {
        dialogVariables.value.show = false;
        dialogVariables.value.variantIndex = null;
        dialogVariables.value.variantValueIndex = null;
        dialogVariables.value.title = '';
        dialogVariables.value.form.name = {};
        const modal = bootstrap.Modal.getInstance(document.getElementById('variantEditModal'));
        if (modal) modal.hide();
      };

      const saveVariantDialog = () => {
        const name = JSON.parse(JSON.stringify(dialogVariables.value.form.name));
        const variantIndex = dialogVariables.value.variantIndex;
        const valueIndex = dialogVariables.value.variantValueIndex;
        if (isObjectValuesEmpty(name)) { layer.msg('{{ __('panel/common.verify_required') }}', {icon: 2}); return; }
        if (valueIndex !== null) {
          if (valueIndex === -1) variants.value[variantIndex].values.push({name, image: ''});
          else variants.value[variantIndex].values[valueIndex].name = name;
        } else {
          if (variantIndex === -1) variants.value.push({name, values: [], isImage: false});
          else variants.value[variantIndex].name = name;
        }
        closeVariantDialog();
        layer.msg('{{ __('panel/common.saved_success') }}', {icon: 1});
      };

      const toggleVariantImage = (variantIndex) => {
        const variant = variants.value[variantIndex];
        if (!variant.isImage) {
          variant.values.forEach(value => value.image = '');
        } else {
          variant.values.forEach(value => { if (!value.image) value.image = ''; });
        }
      };

      const selectVariantValueImage = (variantIndex, valueIndex) => {
        inno.fileManagerIframe((file) => {
          if (file.path) variants.value[variantIndex].values[valueIndex].image = file.path;
        }, { type: 'image', multiple: false });
      };

      const deleteVariantValue = (variantIndex, valueIndex) => {
        if (confirm('{{ __('panel/common.confirm_delete') }}')) {
          variants.value[variantIndex].values.splice(valueIndex, 1);
          layer.msg('{{ __('panel/common.deleted_success') }}', {icon: 1});
        }
      };

      return {
        skus, variants, addVariant, addVariantValue, deleteVariant, saveVariant, locales, defaultLocale,
        mainVariantKey, smallVariants, modifySku, upVariantImage, dragVariantsEnd, thumbnail, setMasterSku,
        showAllVariant, allVariantEC, batchData, batchFillSkuCode, batchFillColumn, getFirstAvailableLocaleValue,
        validateBatchPrice, validateBatchOriginPrice, validateBatchQuantity, validatePrice, validateOriginPrice,
        validateQuantity, dialogVariables, openVariantDialog, closeVariantDialog, saveVariantDialog,
        toggleVariantImage, selectVariantValueImage, deleteVariantValue,
        initializeVariantSelectors, selectAllVariantValues, clearVariantSelection, batchApplySelected,
        selectBatchImage, clearBatchImage
      };
    }
  }).mount('#variants-box');

  function chunkArray(array, chunkSize) { return Array.from({ length: Math.ceil(array.length / chunkSize) }, (_, i) => array.slice(i * chunkSize, i * chunkSize + chunkSize)); }
  function splitArrayIntoGroups(array, groupCount) { if (groupCount <= 0) throw new Error('Group count must be greater than 0'); const result = []; const groupSize = Math.ceil(array.length / groupCount); for (let i = 0; i < groupCount; i++) { result.push(array.slice(i * groupSize, (i + 1) * groupSize)); } return result; }
  function isObjectValuesEmpty(obj) { for (let key in obj) { if (obj[key] != '') return false; } return true; }
</script>
@endpush
