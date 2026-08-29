<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Travalorics\Common\Models\Page;
use Travalorics\Common\Repositories\PageRepo;
use Travalorics\Panel\Requests\PageRequest;

class PageController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws \Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();
        $data    = [
            'criteria' => PageRepo::getCriteria(),
            'pages'    => PageRepo::getInstance()->list($filters),
        ];

        return travalorics_view('panel::pages.index', $data);
    }

    /**
     * Page creation page.
     *
     * @return mixed
     */
    public function create(): mixed
    {
        $data = [
            'page' => new Page,
        ];

        return travalorics_view('panel::pages.form', $data);
    }

    /**
     * @param  PageRequest  $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(PageRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            PageRepo::getInstance()->create($data);

            return back()->with('success', panel_trans('common.updated_success'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Page  $page
     * @return mixed
     */
    public function edit(Page $page): mixed
    {
        $data = [
            'page' => $page,
        ];

        return travalorics_view('panel::pages.form', $data);
    }

    /**
     * @param  PageRequest  $request
     * @param  Page  $page
     * @return RedirectResponse
     */
    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        try {
            $data = $request->all();
            $page = PageRepo::getInstance()->update($page, $data);

            return redirect(panel_route('pages.index'))
                ->with('instance', $page)
                ->with('success', panel_trans('common.updated_success'));
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Page  $page
     * @return RedirectResponse
     */
    public function destroy(Page $page): RedirectResponse
    {
        try {
            PageRepo::getInstance()->destroy($page);

            return back()->with('success', panel_trans('common.deleted_success'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
