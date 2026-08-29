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
use Travalorics\Common\Models\Address;
use Travalorics\Common\Models\Order;

class AccountController extends Controller
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $customer   = current_customer();
        $customerID = $customer->id;
        $data       = [
            'customer'      => $customer,
            'order_total'   => Order::query()->where('customer_id', $customerID)->count(),
            'fav_total'     => 0, // Mocked as customer_favorites table is removed
            'address_total' => Address::query()->where('customer_id', $customerID)->count(),
            'latest_orders' => Order::query()->where('customer_id', $customerID)->orderByDesc('id')->limit(3)->get(),
        ];

        return travalorics_view('account.home', $data);
    }
}
