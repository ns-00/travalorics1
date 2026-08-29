<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 获取验证规则
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type'                => 'required|in:select,radio,checkbox,text,textarea',
            'position'            => 'integer|min:0',
            'active'              => 'boolean',
            'translations'        => 'required|array',
            'translations.*.name' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'type'                => __('panel/options.option_type'),
            'position'            => __('panel/common.sort'),
            'active'              => __('panel/common.status'),
            'translations'        => __('panel/options.multilingual_info'),
            'translations.*.name' => __('panel/options.option_name'),
        ];
    }
}
