<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Travalorics\Common\Models\Product;
use Travalorics\Common\Repositories\CategoryRepo;
use Travalorics\Common\Repositories\ProductRepo;
use Travalorics\Common\Repositories\ReviewRepo;
use Travalorics\Common\Resources\ProductVariable;
use Travalorics\Common\Resources\SkuListItem;
use Travalorics\Front\Traits\FilterSidebarTrait;

class ProductController extends Controller
{
    use FilterSidebarTrait;

    /**
     * Product list page with filter support
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        // Use RequestFilterParser to extract filter conditions
        $filterParser = new \Travalorics\Common\Services\RequestFilterParser;
        $filters      = $filterParser->extractFilters($request, [
            'keyword',
            'sort',
            'order',
            'per_page',
            'price_from',
            'price_to',
            'brand_ids',
            'attribute_values',
            'in_stock',
        ]);

        // Get product list
        $products = ProductRepo::getInstance()->getFrontList($filters);

        // Use Trait method to get filter sidebar data
        $filterData = $this->getFilterSidebarData($request);

        $data = [
            'products'       => $products,
            'categories'     => CategoryRepo::getInstance()->getTwoLevelCategories(),
            'per_page_items' => CategoryRepo::getInstance()->getPerPageItems(),
        ];

        // Merge filter data
        $data = array_merge($data, $filterData);

        return travalorics_view('products.index', $data);
    }

    /**
     * @param  Request  $request
     * @param  Product  $product
     * @return mixed
     */
    public function show(Request $request, Product $product): mixed
    {
        if (! $product->active) {
            abort(404);
        }

        $skuId = $request->get('sku_id');

        return $this->renderShow($product, $skuId);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function slugShow(Request $request): mixed
    {
        $slug    = $request->slug;
        $product = ProductRepo::getInstance()->withActive()->builder(['slug' => $slug])->firstOrFail();

        $skuId = $request->get('sku_id');

        return $this->renderShow($product, $skuId);
    }

    /**
     * @param  Product  $product
     * @param  $skuId
     * @return mixed
     */
    private function renderShow(Product $product, $skuId): mixed
    {
        if ($skuId) {
            $sku = Product\Sku::query()->find($skuId);
        }

        if (empty($sku)) {
            $sku = $product->masterSku;
        }

        $product->increment('viewed');
        $sort = request('sort', 'top');
        $filterRating = request('filter_rating');
        $reviews = ReviewRepo::getInstance()->getListByProduct($product, 10, 1, $sort, $filterRating);
        $customerID = current_customer_id();

        // Calculate Amazon-style review stats with weighted rating
        $allReviews = \Illuminate\Support\Facades\DB::table('reviews')
            ->select('rating', 'like', 'order_item_id', 'created_at', 'attribute_ratings')
            ->where('product_id', $product->id)
            ->where('active', 1)
            ->where('status', 'published')
            ->get();
            
        $histogram = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $totalReviews = $allReviews->count();
        $totalWeight = 0;
        $weightedRatingSum = 0;
        
        $attributeSummary = [];
        $now = now();
        
        foreach($allReviews as $review) {
            $rating = (int) $review->rating;
            if (isset($histogram[$rating])) {
                $histogram[$rating]++;
            }
            
            // Extract Attribute Ratings
            $attrs = [];
            if (!empty($review->attribute_ratings)) {
                $attrs = json_decode($review->attribute_ratings, true) ?? [];
            }
            foreach ($attrs as $key => $value) {
                $k = is_array($value) ? ($value['key'] ?? $key) : $key;
                $v = is_array($value) ? ($value['value'] ?? $value) : $value;
                if (!isset($attributeSummary[$k])) {
                    $attributeSummary[$k] = ['total' => 0, 'count' => 0];
                }
                $attributeSummary[$k]['total'] += (float)$v;
                $attributeSummary[$k]['count']++;
            }
            
            // Calculate review weight - mimicking Amazon
            $weight = 1.0;
            
            // Verified purchase bonus
            if (!empty($review->order_item_id)) {
                $weight += 1.5;
            }
            
            // Helpfulness bonus (cap at +2.0 to prevent abuse)
            if ($review->like > 0) {
                $weight += min($review->like * 0.1, 2.0);
            }
            
            // Recency decay (older reviews have slightly less weight)
            $createdAt = \Illuminate\Support\Carbon::parse($review->created_at);
            $daysOld = max(1, $now->diffInDays($createdAt));
            $timeWeight = exp(-$daysOld / 365); // Decay over a year
            $weight *= (0.5 + 0.5 * $timeWeight); // Base 0.5 weight + up to 0.5 based on recency
            
            $totalWeight += $weight;
            $weightedRatingSum += ($rating * $weight);
        }
        
        $calculatedAverage = $totalWeight > 0 ? round($weightedRatingSum / $totalWeight, 1) : 0;
        
        $radarLabels = [];
        $radarValues = [];
        $insights = [];
        $locale = app()->getLocale();
        $criteriaAr = [];
        $criteriaEn = [];
        $vars = is_array($product->variables) ? $product->variables : [];
        if (isset($vars['review_criteria'])) {
            $criteriaAr = array_map('trim', explode(',', $vars['review_criteria']));
        }
        if (isset($vars['review_criteria_en'])) {
            $criteriaEn = array_map('trim', explode(',', $vars['review_criteria_en']));
        }

        foreach ($attributeSummary as $key => $attrData) {
            $avg = round($attrData['total'] / $attrData['count'], 1);
            $attributeSummary[$key]['avg'] = $avg;
            
            $keyName = str_replace('_', ' ', $key);
            $displayKeyName = $keyName;

            if ($locale === 'en' && !empty($criteriaAr) && !empty($criteriaEn)) {
                $index = array_search(trim($keyName), $criteriaAr);
                if ($index !== false && isset($criteriaEn[$index])) {
                    $displayKeyName = $criteriaEn[$index];
                }
            }
            
            $radarLabels[] = ucwords($displayKeyName);
            $radarValues[] = $avg;
            
            if ($avg >= 4.5) {
                $insights[] = "Excellent " . strtolower($keyName);
            } elseif ($avg >= 3.5) {
                $insights[] = "Balanced " . strtolower($keyName);
            } else {
                $insights[] = "Mild " . strtolower($keyName);
            }
        }
        
        $calculatedRecommendPercent = $totalReviews > 0 ? round(($histogram[5] + $histogram[4]) / $totalReviews * 100) : 100;

        $arabicInsights = implode('، ', array_map(function($insight) {
             $key = "front/product.insight_" . str_replace(' ', '_', strtolower($insight));
             $translated = __($key);
             return ($translated === $key) ? str_replace('_', ' ', $insight) : $translated;
        }, $insights));

        $summaryText = empty($insights)
            ? __('front/product.default_smart_summary')
            : __('front/product.dynamic_smart_summary', ['insights' => $arabicInsights]);
        
        $product->seo_rating = $calculatedAverage;
        $product->seo_reviews = $totalReviews;
        
        $reviewStats = [
            'total' => $totalReviews,
            'average' => $calculatedAverage,
            'histogram' => $histogram,
            'attributes' => $attributeSummary,
            'smart_summary' => $summaryText,
            'radar_labels' => $radarLabels,
            'radar_values' => $radarValues,
            'recommend_percent' => $calculatedRecommendPercent
        ];

        $vars = is_array($product->variables) ? $product->variables : [];
        unset($vars['review_criteria']);
        unset($vars['review_criteria_en']);
        $variables  = ProductVariable::collection(array_values($vars))->jsonSerialize();

        $product->load([
            'productOptions' => function ($query) {
                $query->join('options', 'product_options.option_id', '=', 'options.id')
                    ->orderBy('options.position');
            },
            'productOptionValues' => function ($query) {
                $query->join('option_values', 'product_option_values.option_value_id', '=', 'option_values.id')
                    ->orderBy('option_values.position');
            },
        ]);
        $productOptions      = $product->productOptions;
        $productOptionValues = $product->productOptionValues;

        $data = [
            'product'             => $product,
            'sku'                 => (new SkuListItem($sku))->jsonSerialize(),
            'skus'                => SkuListItem::collection($product->skus)->jsonSerialize(),
            'variants'            => $variables,
            'attributes'          => $product->groupedAttributes(),
            'reviews'             => $reviews,
            'reviewStats'         => $reviewStats,
            'reviewed'            => ReviewRepo::productReviewed($customerID, $product->id),
            'related'             => $product->relationProducts,
            'bundle_items'        => ProductRepo::getInstance()->getBundleItems($product),
            'productOptions'      => $productOptions,
            'productOptionValues' => $productOptionValues,
        ];

        return travalorics_view('products.show', $data);
    }

    /**
     * @param  Request  $request
     * @param  Product  $product
     * @return mixed
     */
    public function reviews(Request $request, Product $product): mixed
    {
        $page    = $request->get('page', 1);
        $sort    = $request->get('sort', 'top');
        $filterRating = $request->get('filter_rating');

        $reviews = ReviewRepo::getInstance()->getListByProduct($product, 10, $page, $sort, $filterRating);

        $html = view('products.components._review_list', [
            'reviews' => $reviews,
            'product' => $product,
        ])->render();

        return response()->json([
            'success' => true,
            'data'    => [
                'html'     => $html,
                'has_more' => $reviews->hasMorePages(),
            ],
        ]);
    }

    /**
     * @param  Request  $request
     * @param  $id
     * @return mixed
     */
    public function helpful(Request $request, $id): mixed
    {
        $review = \Travalorics\Common\Models\Review::findOrFail($id);
        $votedReviews = $request->session()->get('voted_reviews', []);

        if (in_array($id, $votedReviews)) {
            return response()->json(['success' => false, 'message' => __('front/product.already_voted') ?? 'Already voted']);
        }

        $review->increment('like');
        $votedReviews[] = $id;
        $request->session()->put('voted_reviews', $votedReviews);

        return response()->json(['success' => true, 'count' => $review->like]);
    }
}
