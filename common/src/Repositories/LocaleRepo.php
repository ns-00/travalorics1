<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Common\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Travalorics\Common\Models\Locale;

class LocaleRepo extends BaseRepo
{
    /**
     * @return array[]
     */
    public static function getCriteria(): array
    {
        return [
            ['name' => 'name', 'type' => 'input', 'label' => trans('panel/common.name')],
            ['name' => 'code', 'type' => 'input', 'label' => trans('panel/currency.code')],
            ['name' => 'status', 'type' => 'input', 'label' => trans('panel/common.status')],
        ];
    }

    public static ?Collection $enabledLocales = null;

    /**
     * https://lingohub.com/blog/right-to-left-vs-left-to-right
     *
     * Get all RTL languages.
     * @return string[]
     */
    public static function getRtlLanguages(): array
    {
        return [
            'ar'  => 'Arabic',
            'arc' => 'Aramaic',
            'dv	' => 'Divehi',
            'fa	' => 'Persian',
            'ha	' => 'Hausa',
            'he	' => 'Hebrew',
            'khw' => 'Khowar',
            'ks	' => 'Kashmiri',
            'ku	' => 'Kurdish',
            'ps	' => 'Pashto',
            'ur	' => 'Urdu',
            'yi	' => 'Yiddish',
        ];
    }

    /**
     * @param  $data
     * @return mixed
     */
    public function create($data): mixed
    {
        return (object) $data;
    }

    /**
     * @throws Exception
     */
    public function getFrontListWithPath(): array
    {
        $languages = self::getHardcodedLocales()->keyBy('code');

        $result = [];
        foreach (front_lang_path_codes() as $localeCode) {
            $langFile = lang_path("/$localeCode/common/base.php");
            if (! is_file($langFile)) {
                throw new Exception("File ($langFile) not exist!");
            }
            $baseData = require $langFile;
            $name     = $baseData['name'] ?? $localeCode;
            $result[] = [
                'code'     => $localeCode,
                'name'     => $name,
                'id'       => $languages[$localeCode]->id ?? 0,
                'image'    => $languages[$localeCode]->image ?? "images/flag/$localeCode.png",
                'position' => $languages[$localeCode]->position ?? 0,
                'active'   => $languages[$localeCode]->active ?? true,
            ];
        }

        return $result;
    }

    /**
     * @param  array  $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        return Locale::query()->whereRaw('1 = 0');
    }

    /**
     * Get hardcoded locales to bypass database.
     *
     * @return Collection
     */
    public static function getHardcodedLocales(): Collection
    {
        return collect([
            new Locale([
                'id'       => 1,
                'name'     => 'English',
                'code'     => 'en',
                'image'    => 'images/flag/en.png',
                'position' => 0,
                'active'   => 1,
            ]),
            new Locale([
                'id'       => 2,
                'name'     => 'Arabic',
                'code'     => 'ar',
                'image'    => 'images/flag/ar.png',
                'position' => 1,
                'active'   => 1,
            ]),
        ]);
    }

    /**
     * Get active list.
     *
     * @return mixed
     * @throws Exception
     */
    public function getActiveList(): mixed
    {
        return self::getHardcodedLocales();
    }
}
