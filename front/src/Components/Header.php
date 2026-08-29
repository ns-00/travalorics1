<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Components;

use Illuminate\View\Component;
use Travalorics\Front\Repositories\HeaderMenuRepo;

/**
 * Header component class
 * Responsible for rendering the website header, including navigation menu, search box, user account, etc.
 */
class Header extends Component
{
    public array $headerMenus;

    public $currentLocale;

    public $customer;

    public int $favTotal;

    /**
     * Constructor - Initialize data required for Header component
     *
     * @throws \Exception
     */
    public function __construct()
    {
        // Get header menu data
        $this->headerMenus = HeaderMenuRepo::getInstance()->getMenus();

        // Get current locale settings
        $this->currentLocale = current_locale();

        // Get current customer information
        $this->customer = current_customer();

        // Get favorites count
        if ($this->customer) {
            $this->favTotal = $this->customer->favorites()->count();
        } else {
            $this->favTotal = 0;
        }
    }

    /**
     * Render Header component view
     *
     * @return mixed
     */
    public function render(): mixed
    {
        $data = [
            'header_menus' => $this->headerMenus,
        ];

        return view('components.header', $data);
    }
}
