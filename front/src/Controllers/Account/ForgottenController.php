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
use Exception;
use Travalorics\Front\Requests\ForgottenRequest;
use Travalorics\Front\Requests\VerifyCodeRequest;
use Travalorics\Front\Services\AccountService;

class ForgottenController extends Controller
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return view('account.forgotten');
    }

    /**
     * Receive the email address, generate a verification code, and send it to the email address.
     *
     * @param  VerifyCodeRequest  $request
     * @return mixed
     */
    public function sendVerifyCode(VerifyCodeRequest $request): mixed
    {
        try {
            $email = $request->get('email');
            AccountService::getInstance()->sendVerifyCode($email);

            return json_success(front_trans('forgotten.verification_code_sent'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Verify the code without changing the password.
     *
     * @param  VerifyCodeRequest  $request
     * @return mixed
     */
    public function checkVerifyCode(VerifyCodeRequest $request): mixed
    {
        try {
            $code  = $request->get('code');
            $email = $request->get('email');

            if (!$code) {
                throw new Exception(front_trans('account.verify_code_error'));
            }

            AccountService::getInstance()->checkVerifyCode($code, $email);

            return json_success(front_trans('common.success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Receive the verification code and new password, confirm the password, check if the verification code is correct,
     * and if the password matches the confirmed password, then change the password.
     *
     * @param  ForgottenRequest  $request
     * @return mixed
     * @throws Exception
     */
    public function changePassword(ForgottenRequest $request): mixed
    {
        try {
            $code     = $request->get('code');
            $email    = $request->get('email');
            $password = $request->get('password');

            AccountService::getInstance()->verifyUpdatePassword($code, $email, $password);

            return json_success(front_trans('forgotten.password_updated'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
