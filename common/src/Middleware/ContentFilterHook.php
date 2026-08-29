<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentFilterHook
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $hookName = $this->parseHookName($request);

        $filteredRequest = fire_hook_filter($hookName.'.request', $request);

        $response = $next($filteredRequest);

        $filteredResponse = fire_hook_filter($hookName.'.response', $response);

        if (is_string($filteredResponse)) {
            $response->setContent($filteredResponse);
        }

        return $response;
    }

    /**
     * Parse hook name
     *
     * @param  Request  $request
     * @return string
     */
    private function parseHookName(Request $request): string
    {
        $route = $request->route();

        $controllerAction = $route->getActionName();
        $controllerAction = str_replace(['Travalorics\\', 'Controllers\\'], '', $controllerAction);
        $controllerAction = str_replace('Controller@', '\\', $controllerAction);

        return strtolower(str_replace('\\', '.', $controllerAction));
    }
}
