<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers\Account;

use Exception;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\Order\Item;
use Travalorics\Common\Models\Product;
use Travalorics\Common\Models\Review;
use Travalorics\Common\Repositories\ReviewRepo;
use Travalorics\RestAPI\FrontApiControllers\BaseController;

class ReviewController extends BaseController
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $filters = [
            'customer_id' => current_customer_id(),
        ];

        $data = [
            'reviews' => ReviewRepo::getInstance()->list($filters),
        ];

        return travalorics_view('account.reviews_index', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(Request $request): mixed
    {
        $referrerUrl = $request->header('referer');
        try {
            $productID   = $request->get('product_id');
            $orderItemID = $request->get('order_item_id');
            if ($productID) {
                $product = Product::query()->findOrFail($productID);
            } elseif ($orderItemID) {
                $orderItem = Item::query()->findOrFail($orderItemID);
                $product   = $orderItem->product;
            }

            if (empty($product)) {
                throw new Exception('Invalid product.');
            }

            $data = $request->all();
            $data['customer_id'] = current_customer_id();

            // Application Validation: Prevent duplicate review submission
            if (!empty($data['order_item_id'])) {
                if (ReviewRepo::orderReviewed($data['customer_id'], $data['order_item_id'])) {
                    throw new Exception(__('front/product.already_reviewed') ?? 'You have already reviewed this item.');
                }
            } else if (!empty($data['product_id'])) {
                // If it's a general review (no order item), ensure they haven't submitted a general review already
                $hasGeneralReview = \Travalorics\Common\Models\Review::query()
                    ->where('customer_id', $data['customer_id'])
                    ->where('product_id', $data['product_id'])
                    ->where(function($query) {
                        $query->whereNull('order_item_id')->orWhere('order_item_id', 0);
                    })
                    ->exists();
                    
                if ($hasGeneralReview) {
                    throw new Exception(__('front/product.already_reviewed') ?? 'You have already reviewed this product.');
                }
            }

            $review = ReviewRepo::getInstance()->create($data);
            // تحديث seo_rating و seo_reviews تلقائياً
            if (isset($data['product_id'])) {
                $product = \Travalorics\Common\Models\Product::find($data['product_id']);
                if ($product) {
                    $activeReviews = $product->reviews()->where('active', 1);
                    $count = $activeReviews->count();
                    $avg = $count > 0 ? round($activeReviews->avg('rating'), 1) : 0;
                    $product->update(['seo_rating' => $avg, 'seo_reviews' => $count]);
                }
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => __('front/product.review_submitted') ?? 'Review submitted successfully']);
            }

            return redirect($referrerUrl)
                ->with('success', front_route('common.saved_success'));
        } catch (Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect($referrerUrl)
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Review  $review
     * @return mixed
     */
    public function destroy(Review $review): mixed
    {
        try {
            if ($review->customer_id !== current_customer_id()) {
                return json_fail('Unauthorized: You can only delete your own reviews', null, 403);
            }

            $review->delete();

            return delete_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
