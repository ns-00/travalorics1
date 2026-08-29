<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Throwable;
use Travalorics\Common\Models\Order\Item;
use Travalorics\Common\Models\Review;

class ReviewRepo extends BaseRepo
{
    /**
     * @return array[]
     */
    public static function getCriteria(): array
    {
        return [
            ['name' => 'product', 'type' => 'input', 'label' => trans('panel/review.product')],
            ['name' => 'rating', 'type' => 'input', 'label' => trans('panel/review.rating')],
            ['name' => 'review_content', 'type' => 'input', 'label' => trans('panel/review.review_content')],
            ['name' => 'status', 'type' => 'select', 'label' => trans('panel/common.status') ?? 'Status', 'options' => [
                ['value' => 'pending', 'label' => 'Pending'],
                ['value' => 'published', 'label' => 'Published'],
                ['value' => 'shadow_hidden', 'label' => 'Shadow Hidden']
            ]],
            ['name' => 'created_at', 'type' => 'date_range', 'label' => trans('panel/review.created_at')],
        ];
    }

    /**
     * @param  $productID
     * @param  $customerID
     * @return bool
     */
    public static function productReviewed($customerID, $productID): bool
    {
        if (empty($customerID) || empty($productID)) {
            return false;
        }

        return Review::query()
            ->where('customer_id', $customerID)
            ->where('product_id', $productID)
            ->exists();
    }

    /**
     * @param  $orderItemID
     * @param  $customerID
     * @return bool
     */
    public static function orderReviewed($customerID, $orderItemID): bool
    {
        if (empty($customerID) || empty($orderItemID)) {
            return false;
        }

        return Review::query()
            ->where('customer_id', $customerID)
            ->where('order_item_id', $orderItemID)
            ->exists();
    }

    /**
     * @param  $product
     * @return LengthAwarePaginator
     */
    public function getListByProduct($product, $limit = 10, $page = 1, $sort = 'top', $filter_rating = null): LengthAwarePaginator
    {
        if (is_object($product)) {
            $productID = $product->id;
        } else {
            $productID = (int) $product;
        }

        $filters = [
            'product_id' => $productID,
            'active'     => true,
            'status'     => 'published',
        ];

        if ($filter_rating) {
            $filters['rating'] = $filter_rating;
        }
        
        if ($sort) {
            $filters['sort'] = $sort;
        }

        return $this->builder($filters)->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * @param  $data
     * @return mixed
     * @throws Throwable
     */
    public function create($data): mixed
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $processedData = $this->handleData($data);
            $review = null;

            if ($processedData['customer_id'] && $processedData['order_item_id']) {
                $filters = [
                    'customer_id'   => $processedData['customer_id'],
                    'order_item_id' => $processedData['order_item_id'],
                ];
                $review = $this->builder($filters)->first();
            }

            if ($review) {
                return $review;
            }

            $review = new Review($processedData);
            $review->saveOrFail();

            if (isset($data['attribute_ratings']) && is_array($data['attribute_ratings'])) {
                foreach ($data['attribute_ratings'] as $attr => $val) {
                    \Travalorics\Common\Models\ReviewAttribute::create([
                        'review_id' => $review->id,
                        'key'       => $attr,
                        'value'     => (int) $val
                    ]);
                }
            } else {
                $coffeeAttributes = ['roast_level', 'aroma', 'acidity', 'body', 'flavor'];
                foreach ($coffeeAttributes as $attr) {
                    if (isset($data[$attr])) {
                        \Travalorics\Common\Models\ReviewAttribute::create([
                            'review_id' => $review->id,
                            'key'       => $attr,
                            'value'     => (int) $data[$attr]
                        ]);
                    }
                }
            }
            $this->recalculateProductRating($review->product_id);

            return $review;
        });
    }

    /**
     * @param $productID
     */
    public function recalculateProductRating($productID)
    {
        $allReviews = \Illuminate\Support\Facades\DB::table('reviews')
            ->select('rating', 'like', 'order_item_id', 'created_at')
            ->where('product_id', $productID)
            ->where('active', 1)
            ->where('status', 'published')
            ->get();
        
        $totalReviews = $allReviews->count();
        $totalWeight = 0;
        $weightedRatingSum = 0;
        $now = now();
        
        foreach($allReviews as $review) {
            $rating = (int) $review->rating;
            $weight = 1.0;
            if (!empty($review->order_item_id)) $weight += 1.5;
            if ($review->like > 0) $weight += min($review->like * 0.1, 2.0);
            $createdAt = \Illuminate\Support\Carbon::parse($review->created_at);
            $daysOld = max(1, $now->diffInDays($createdAt));
            $timeWeight = exp(-$daysOld / 365);
            $weight *= (0.5 + 0.5 * $timeWeight);
            $totalWeight += $weight;
            $weightedRatingSum += ($rating * $weight);
        }
        
        $calculatedAverage = $totalWeight > 0 ? round($weightedRatingSum / $totalWeight, 1) : 0;
        \Travalorics\Common\Models\Product::where('id', $productID)->update([
            'seo_rating' => $calculatedAverage,
            'seo_reviews' => $totalReviews
        ]);
    }

    /**
     * @param $review
     * @param $data
     * @return mixed
     */
    public function update($review, $data): mixed
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($review, $data) {
            $processedData = $this->handleData($data);
            
            // For update, we don't change customer_id or order_item_id or product_id manually
            unset($processedData['customer_id']);
            unset($processedData['product_id']);
            unset($processedData['order_item_id']);

            $review->update($processedData);

            // Handle Coffee Attributes updates
            if (isset($data['attribute_ratings']) && is_array($data['attribute_ratings'])) {
                // Delete old ones to handle attribute name changes
                \Travalorics\Common\Models\ReviewAttribute::where('review_id', $review->id)->delete();
                foreach ($data['attribute_ratings'] as $attr => $val) {
                    if ($val) {
                        \Travalorics\Common\Models\ReviewAttribute::create([
                            'review_id' => $review->id,
                            'key'       => $attr,
                            'value'     => (int) $val
                        ]);
                    }
                }
            } else {
                $coffeeAttributes = ['roast_level', 'aroma', 'acidity', 'body', 'flavor'];
                foreach ($coffeeAttributes as $attr) {
                    if (isset($data[$attr])) {
                        \Travalorics\Common\Models\ReviewAttribute::updateOrCreate(
                            ['review_id' => $review->id, 'key' => $attr],
                            ['value' => (int) $data[$attr]]
                        );
                    }
                }
            }

            $this->recalculateProductRating($review->product_id);

            return $review;
        });
    }

    /**
     * @param  array  $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        $builder = Review::query()->with([
            'customer',
            'product',
            'orderItem',
            'attributes',
        ]);

        $customerID = $filters['customer_id'] ?? 0;
        if ($customerID) {
            $builder->where('customer_id', $customerID);
        }

        $productID = $filters['product_id'] ?? 0;
        if ($productID) {
            $builder->where('product_id', $productID);
        }

        $orderItemID = $filters['order_item_id'] ?? 0;
        if ($orderItemID) {
            $builder->where('order_item_id', $orderItemID);
        }

        $content = $filters['content'] ?? ($filters['review_content'] ?? '');
        if ($content) {
            $builder->where('content', 'like', "%$content%");
        }

        $rating = $filters['rating'] ?? '';
        if ($rating) {
            $builder->where('rating', $rating);
        }

        $product = $filters['product'] ?? '';
        if ($product) {
            $builder->whereHas('product.translation', function (Builder $query) use ($product) {
                $query->where('name', 'like', "%$product%");
            });
        }

        if (isset($filters['active'])) {
            $builder->where('active', (bool) $filters['active']);
        }

        $createdStart = $filters['created_at_start'] ?? '';
        if ($createdStart) {
            $builder->where('created_at', '>', $createdStart);
        }

        $createdEnd = $filters['created_at_end'] ?? '';
        if ($createdEnd) {
            $builder->where('created_at', '<', $createdEnd);
        }

        if (isset($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'top';
        
        // Priority #1: Always float featured reviews to the top
        $builder->orderBy('featured_rank', 'desc');
        
        if ($sort === 'recent') {
            $builder->orderBy('created_at', 'desc');
        } elseif ($sort === 'top') {
            // Sort Score = helpful_votes + verified_purchase_bonus + review_length_score + recency_score
            // Approximate recency in MySQL by just adding created_at desc order next.
            $builder->orderByRaw("(`like` + IF(order_item_id IS NOT NULL, 5, 0) + (LENGTH(content) / 100)) DESC")->orderBy('created_at', 'desc');
        } else {
            $builder->orderBy('id', 'desc');
        }

        return $builder;
    }

    /**
     * @param  $requestData
     * @return array
     */
    private function handleData($requestData): array
    {
        $customerId = $requestData['customer_id'] ?? 0;
        $orderItemID = $requestData['order_item_id'] ?? 0;
        $orderItem = null;
        $status = 'pending';

        if ($orderItemID) {
            $orderItem = Item::query()->findOrFail($orderItemID);
            $order = \Illuminate\Support\Facades\DB::table('orders')->where('id', $orderItem->order_id)->first();
            
            // Verify order belongs to customer and is delivered/shipped/completed
            if ($order && $order->customer_id != $customerId) {
                // Fraud: Trying to review someone else's item
                $orderItemID = 0; 
                $orderItem = null;
            } else if ($order && !in_array($order->status, ['delivered', 'completed', 'shipped'])) {
                // Not delivered yet, strip the verified badge
                $orderItemID = 0;
            }
        }

        // Anti-Fraud Velocity Check: How many reviews submitted today?
        if ($customerId) {
            $reviewsToday = Review::query()
                ->where('customer_id', $customerId)
                ->whereDate('created_at', now()->toDateString())
                ->count();
                
            if ($reviewsToday >= 5) {
                $status = 'shadow_hidden'; // Silent ban for spam
            }
        }
        
        // Collect attribute ratings
        $attributeRatings = [];
        if (isset($requestData['attribute_ratings']) && is_array($requestData['attribute_ratings'])) {
            foreach ($requestData['attribute_ratings'] as $attr => $val) {
                if ($val) {
                    $attributeRatings[$attr] = (int) $val;
                }
            }
        } else {
            $coffeeAttributes = ['roast_level', 'aroma', 'acidity', 'body', 'flavor'];
            foreach ($coffeeAttributes as $attr) {
                if (isset($requestData[$attr])) {
                    $attributeRatings[$attr] = (int) $requestData[$attr];
                }
            }
        }

        return [
            'customer_id'       => $customerId,
            'product_id'        => $requestData['product_id'] ?? ($orderItem->product_id ?? 0),
            'order_item_id'     => $orderItemID,
            'rating'            => $requestData['rating'] ?? 0,
            'title'             => $requestData['title'] ?? null,
            'content'           => $requestData['content'] ?? '',
            'attribute_ratings' => !empty($attributeRatings) ? $attributeRatings : null,
            'like'              => 0,
            'dislike'           => 0,
            'active'            => true,
            'status'            => $status,
        ];
    }
}
