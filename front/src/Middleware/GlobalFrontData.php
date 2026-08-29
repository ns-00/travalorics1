<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class GlobalFrontData
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $customer = current_customer();
        $favTotal = 0;
        if ($customer) {
            $favTotal = $customer->favorites()->count();
        }

        $frontApiToken = session('front_api_token');
        if ($customer && empty($frontApiToken)) {
            $apiToken = $customer->createToken('customer-token')->plainTextToken;
            session(['front_api_token' => $apiToken]);
        }

        view()->share('current_locale', current_locale());
        view()->share('customer', $customer);
        view()->share('fav_total', $favTotal);

        return $next($request);
    }
}
