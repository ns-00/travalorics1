<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Controllers\Account;

use Exception;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;
use Travalorics\Common\Repositories\Customer\SocialRepo;
use Travalorics\Front\Controllers\BaseController;

class SocialController extends BaseController
{
    /**
     * @param  string  $provider
     * @return RedirectResponse
     * @throws Exception
     */
    public function redirect(string $provider): RedirectResponse
    {
        SocialRepo::getInstance()->initSocialConfig();
        if ($provider == 'twitter') {
            $provider = 'twitter-oauth-2';
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param  string  $provider
     * @return mixed
     * @throws Throwable
     */
    public function callback(string $provider): mixed
    {

        try {
            SocialRepo::getInstance()->initSocialConfig();
            if ($provider == 'twitter') {
                $provider = 'twitter-oauth-2';
            }
            $user     = Socialite::driver($provider)->user();
            $userData = [
                'uid'    => $user->getId(),
                'email'  => $user->getEmail(),
                'name'   => $user->getName(),
                'avatar' => $user->getAvatar(),
                'token'  => $user->token,
                'raw'    => $user->getRaw(),
            ];
            $customer = SocialRepo::getInstance()->createCustomer($provider, $userData);
            auth('customer')->login($customer);

            return travalorics_view('account.social_callback');

        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}
