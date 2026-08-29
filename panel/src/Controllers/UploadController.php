<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Travalorics\Common\Requests\UploadFileRequest;
use Travalorics\Common\Requests\UploadImageRequest;
use Travalorics\RestAPI\Services\UploadService;

class UploadController
{
    /**
     * Upload images.
     *
     * @param  UploadImageRequest  $request
     * @return mixed
     */
    public function images(UploadImageRequest $request): mixed
    {
        $image = $request->file('image');
        $type  = $request->file('type', 'common');

        // Use unified upload service with security validation
        $data = UploadService::getInstance()->uploadForPanel($image, $type);

        return json_success(trans('common/upload.upload_success'), $data);
    }

    /**
     * Upload document files
     *
     * @param  UploadFileRequest  $request
     * @return mixed
     */
    public function files(UploadFileRequest $request): mixed
    {
        $file = $request->file('file');
        $type = $request->file('type', 'files');

        // Use unified upload service with security validation
        $data = UploadService::getInstance()->uploadForPanel($file, $type);

        return json_success(trans('common/upload.upload_success'), $data);
    }
}
