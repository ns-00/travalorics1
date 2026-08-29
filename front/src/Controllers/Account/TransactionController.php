<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers\Account;

use Travalorics\Common\Repositories\Customer\TransactionRepo;
use Travalorics\Common\Repositories\Customer\WithdrawalRepo;
use Travalorics\RestAPI\FrontApiControllers\BaseController;

class TransactionController extends BaseController
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $customer   = current_customer();
        $customerID = $customer->id;

        $filters = [
            'customer_id' => $customerID,
        ];

        $customer->syncBalance();
        $balance = $customer->balance;
        $frozen  = (new WithdrawalRepo)->getFrozenAmount($customerID);
        $data    = [
            'balance'      => $balance,
            'frozen'       => $frozen,
            'available'    => $balance - $frozen,
            'transactions' => TransactionRepo::getInstance()->list($filters),
        ];

        return travalorics_view('account.transactions_index', $data);
    }
}
