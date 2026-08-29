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
use Throwable;
use Travalorics\Common\Services\CartService;
use Travalorics\Front\Requests\RegisterRequest;
use Travalorics\Front\Services\AccountService;

class RegisterController extends Controller
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function index(): mixed
    {
        if (current_customer()) {
            return redirect(front_route('account.index'));
        }

        return travalorics_view('account.register');
    }

    /**
     * @param  RegisterRequest  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(RegisterRequest $request): mixed
    {
        try {
            $credentials = $request->only('email', 'password');
            $email = $credentials['email'];

            // إنشاء كود OTP عشوائي
            $otp = rand(100000, 999999);

            // حفظ البيانات في الـ Cache لمدة 10 دقائق
            \Illuminate\Support\Facades\Cache::put("otp_reg_{$email}", $otp, now()->addMinutes(10));
            \Illuminate\Support\Facades\Cache::put("reg_data_{$email}", $credentials, now()->addMinutes(10));

            // إرسال الكود عبر البريد
            \Illuminate\Support\Facades\Mail::raw("رمز التحقق الخاص بك للتسجيل هو: {$otp}", function ($message) use ($email) {
                $message->to($email)
                        ->subject('رمز التحقق للتسجيل');
            });

            return response()->json([
                'success' => true,
                'require_otp' => true,
                'message' => 'تم إرسال كود التحقق (OTP) إلى بريدك الإلكتروني.',
                'email' => $email
            ]);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function verifyOtp(\Illuminate\Http\Request $request): mixed
    {
        try {
            $email = $request->input('email');
            $otp = $request->input('otp');

            $cachedOtp = \Illuminate\Support\Facades\Cache::get("otp_reg_{$email}");

            if (!$cachedOtp || $cachedOtp != $otp) {
                return json_fail('كود التحقق غير صحيح أو منتهي الصلاحية.');
            }

            $credentials = \Illuminate\Support\Facades\Cache::get("reg_data_{$email}");
            if (!$credentials) {
                return json_fail('انتهت مهلة التسجيل، يرجى المحاولة مرة أخرى.');
            }

            // إتمام عملية التسجيل بعد التحقق
            $oldGuestId  = current_guest_id();
            $customer    = AccountService::getInstance()->register($credentials);
            auth('customer')->attempt($credentials);

            CartService::getInstance(current_customer_id())->mergeCart($oldGuestId);

            // مسح الـ Cache
            \Illuminate\Support\Facades\Cache::forget("otp_reg_{$email}");
            \Illuminate\Support\Facades\Cache::forget("reg_data_{$email}");

            return json_success(front_trans('register.register_success'), ['customer' => $customer]);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
