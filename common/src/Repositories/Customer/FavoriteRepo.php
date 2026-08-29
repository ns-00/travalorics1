<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Repositories\Customer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Travalorics\Common\Models\Customer\Favorite;
use Travalorics\Common\Repositories\BaseRepo;

class FavoriteRepo extends BaseRepo
{
    protected string $model = Favorite::class;

    /**
     * Get list of favorites.
     *
     * @param  array  $filters
     * @return LengthAwarePaginator
     */
    public function list(array $filters = []): LengthAwarePaginator
    {
        $builder = $this->builder($filters);
        
        $limit = $filters['limit'] ?? 15;
        return $builder->paginate($limit);
    }

    /**
     * @param  $data
     * @return mixed
     */
    public function create($data): mixed
    {
        return Favorite::firstOrCreate([
            'customer_id' => $data['customer_id'],
            'product_id'  => $data['product_id'],
        ]);
    }

    /**
     * @param  array  $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        $builder = Favorite::query()->with([
            'customer',
            'product.translation',
        ]);

        $customerID = $filters['customer_id'] ?? 0;
        if ($customerID) {
            $builder->where('customer_id', $customerID);
        }

        $productID = $filters['product_id'] ?? 0;
        if ($productID) {
            $builder->where('product_id', $productID);
        }

        return fire_hook_filter('repo.customer.favorite.builder', $builder);
    }
}
