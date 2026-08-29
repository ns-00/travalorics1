<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Front\Repositories;

use Exception;
use Travalorics\Common\Repositories\CategoryRepo;
use Travalorics\Common\Repositories\PageRepo;
use Travalorics\Common\Repositories\SpecialPageRepo;
use Travalorics\Common\Resources\PageSimple;

class HeaderMenuRepo
{
    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return new static;
    }

    /**
     * Generate header menus for frontend.
     *
     * @return array
     * @throws Exception
     */
    public function getMenus(): array
    {
        $specials   = $this->getSpecials(system_setting('menu_header_specials'));
        $categories = $this->getCategories(system_setting('menu_header_categories'));
        $pages      = $this->getPages(system_setting('menu_header_pages'));
        $menus      = array_merge($specials, $categories, $pages);

        return fire_hook_filter('global.header.menus', $menus);
    }

    /**
     * @param  $specials
     * @return array
     * @throws Exception
     */
    public function getSpecials($specials): array
    {
        if (empty($specials)) {
            return [];
        }

        return SpecialPageRepo::getInstance()->getSpecialLinks($specials);
    }

    /**
     * @param  $categoryIds
     * @return array
     */
    public function getCategories($categoryIds): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        return CategoryRepo::getInstance()->getTwoLevelCategories($categoryIds);
    }

    /**
     * @param  $pageIds
     * @return array
     */
    public function getPages($pageIds): array
    {
        if (empty($pageIds)) {
            return [];
        }

        $catalogs = PageRepo::getInstance()
            ->builder(['active' => true, 'page_ids' => $pageIds])
            ->orderBy('position')
            ->get();

        return PageSimple::collection($catalogs)->jsonSerialize();
    }
}
