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

class OptionValueRequest extends FormRequest
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
            'option_id' => 'required|exists:options,id',
            'position'  => 'integer|min:0',
            'active'    => 'boolean',
            'image'     => 'nullable|string|max:255',
            'name'      => 'required|array',
            'name.*'    => 'required|string|max:255',
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
            'option_id' => __('panel/options.option_group'),
            'position'  => __('panel/common.sort'),
            'active'    => __('panel/common.status'),
            'image'     => __('panel/common.image'),
            'name'      => __('panel/common.name'),
            'name.*'    => __('panel/common.name'),
        ];
    }
}
