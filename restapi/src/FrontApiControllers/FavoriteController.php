<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\FrontApiControllers;

use Illuminate\Http\Request;
use Travalorics\Common\Repositories\Customer\FavoriteRepo;
use Travalorics\Common\Resources\FavoriteItem;

class FavoriteController extends BaseController
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $filters = [
            'customer_id' => token_customer_id(),
        ];
        $favorites = FavoriteRepo::getInstance()->list($filters);

        return FavoriteItem::collection($favorites);
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
                'customer_id' => token_customer_id(),
                'product_id'  => $request->get('product_id'),
            ];
            FavoriteRepo::getInstance()->create($data);

            return json_success(front_trans('common.saved_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Destroy favorite item.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function cancel(Request $request): mixed
    {
        try {
            // Mocked as customer_favorites table is removed
            return json_success(front_trans('common.deleted_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
