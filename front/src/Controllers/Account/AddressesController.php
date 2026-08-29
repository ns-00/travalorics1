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
use Throwable;
use Travalorics\Common\Models\Address;
use Travalorics\Common\Repositories\AddressRepo;
use Travalorics\Common\Resources\AddressListItem;

class AddressesController extends Controller
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        $filters = [
            'customer_id' => current_customer_id(),
        ];
        $items     = AddressRepo::getInstance()->builder($filters)->get();
        $addresses = (AddressListItem::collection($items))->jsonSerialize();

        $data = [
            'addresses' => $addresses,
        ];

        return travalorics_view('account.addresses', $data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(Request $request): mixed
    {
        $data                = $request->all();
        $data['customer_id'] = current_customer_id();

        $address = AddressRepo::getInstance()->create($data);
        $result  = new AddressListItem($address);

        return create_json_success($result);
    }

    /**
     * @param  Request  $request
     * @param  Address  $address
     * @return mixed
     */
    public function update(Request $request, Address $address): mixed
    {
        $currentCustomerId = current_customer_id();

        if ($address->customer_id !== $currentCustomerId) {
            return json_fail('Unauthorized', null, 403);
        }

        $data                = $request->all();
        $data['customer_id'] = $currentCustomerId;

        $address = AddressRepo::getInstance()->update($address, $data);
        $result  = new AddressListItem($address);

        return update_json_success($result);
    }

    /**
     * @param  Address  $address
     * @return mixed
     */
    public function destroy(Address $address): mixed
    {
        $currentCustomerId = current_customer_id();

        if ($address->customer_id !== $currentCustomerId) {
            return json_fail('Unauthorized', null, 403);
        }

        AddressRepo::getInstance()->destroy($address);

        return delete_json_success();
    }
}
