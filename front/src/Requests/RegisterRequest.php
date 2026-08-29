<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => [
                'required',
                'email',
                'unique:customers,email',
                function ($attribute, $value, $fail) {
                    if (\Illuminate\Support\Facades\DB::table('admins')->where('email', $value)->exists()) {
                        $fail('هذا البريد مسجل كمدير للنظام، يرجى استخدام بريد إلكتروني مختلف للعملاء.');
                    }
                },
            ],
            'password' => 'required|confirmed',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email'    => front_trans('login.email'),
            'password' => front_trans('login.password'),
        ];
    }
}
