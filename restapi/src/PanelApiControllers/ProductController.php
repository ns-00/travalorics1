<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\PanelApiControllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;
use Travalorics\Common\Repositories\Product\SkuRepo;
use Travalorics\Common\Repositories\ProductRepo;
use Travalorics\Common\Resources\ProductSimple;
use Travalorics\Common\Resources\SkuSimple;
use Travalorics\RestAPI\Services\ProductImportService;

class ProductController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters  = $request->all();
        $products = ProductRepo::getInstance()->list($filters);

        return ProductSimple::collection($products);
    }

    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function names(Request $request): AnonymousResourceCollection
    {
        $products = ProductRepo::getInstance()->getListByProductIDs($request->get('ids'));

        return ProductSimple::collection($products);
    }

    /**
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function autocomplete(Request $request): AnonymousResourceCollection
    {
        $products = ProductRepo::getInstance()->autocomplete($request->get('keyword') ?? '');

        return ProductSimple::collection($products);
    }

    /**
     * SKU编码自动完成
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function skuAutocomplete(Request $request): AnonymousResourceCollection
    {
        $keyword = $request->get('keyword') ?? '';
        $limit   = $request->get('limit', 10);

        $skus = SkuRepo::getInstance()->searchByKeyword($keyword, $limit);

        return SkuSimple::collection($skus);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function import(Request $request): mixed
    {
        try {
            $data = $request->all();
            foreach ($data['products'] as $productData) {
                $product = null;
                $spuCode = $productData['spu_code'] ?? '';
                if (empty($spuCode)) {
                    throw new Exception('Empty SPU code!');
                }

                $product = ProductRepo::getInstance()->findBySpuCode($spuCode);
                ProductImportService::getInstance()->import($productData, $product);
            }

            return create_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @param  string  $spuCode
     * @return mixed
     * @throws Throwable
     */
    public function update(Request $request, string $spuCode): mixed
    {
        try {
            $data    = $request->all();
            $product = ProductRepo::getInstance()->findBySpuCode($spuCode);
            if (! $product) {
                throw new Exception('Product not found!');
            }

            ProductImportService::getInstance()->import($data, $product);

            return create_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @param  string  $spuCode
     * @return mixed
     * @throws Throwable
     */
    public function patch(Request $request, string $spuCode): mixed
    {
        try {
            $data    = $request->all();
            $product = ProductRepo::getInstance()->findBySpuCode($spuCode);
            if (! $product) {
                throw new Exception('Product not found!');
            }

            $data['spu_code'] = $spuCode;
            ProductImportService::getInstance()->patch($product, $data);

            return update_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
