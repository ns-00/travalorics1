<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Travalorics\Common\Repositories\Customer\FavoriteRepo;

class FavoriteController extends Controller
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $filters = [
            'customer_id' => current_customer_id(),
        ];
        $favorites = FavoriteRepo::getInstance()->list($filters);

        $data = [
            'favorites' => $favorites,
        ];

        return travalorics_view('account.favorites', $data);
    }

    /**
     * Add to favorite list.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        try {
            $data = [
                'customer_id' => current_customer_id(),
                'product_id'  => $request->get('product_id'),
            ];
            FavoriteRepo::getInstance()->create($data);

            return json_success(front_trans('common.saved_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    public function cancel(Request $request): mixed
    {
        try {
            $deleted = \Travalorics\Common\Models\Customer\Favorite::where('customer_id', current_customer_id())
                ->where('product_id', $request->get('product_id'))
                ->delete();
                
            return json_success(front_trans('common.deleted_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
