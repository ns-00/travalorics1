<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\RestAPI\FrontApiControllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Travalorics\Front\Requests\RegisterRequest;
use Travalorics\Front\Services\AccountService;

class AuthController extends BaseController
{
    /**
     * @param  RegisterRequest  $request
     * @return mixed
     * @throws Throwable
     */
    public function register(RegisterRequest $request): mixed
    {
        try {
            $credentials = $request->only('email', 'password');
            $customer    = AccountService::getInstance()->register($credentials);
            auth('customer')->attempt($credentials);

            $token = $customer->createToken('customer-token')->plainTextToken;

            return create_json_success(['token' => $token]);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function login(Request $request): mixed
    {
        try {
            $credentials = $request->only('email', 'password');
            if (! auth('customer')->attempt($credentials)) {
                throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
            }
            $token = auth('customer')->user()->createToken('customer-token')->plainTextToken;

            return create_json_success(['token' => $token]);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
