<?php
/**
 * Copyright (c) Since 2024 Travalorics - All Rights Reserved
 *
 * @link       https://www.Travalorics.com
 * @author     Travalorics <team@Travalorics.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Travalorics\Panel\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;
use Travalorics\Common\Models\Category;
use Travalorics\Common\Repositories\CategoryRepo;
use Travalorics\Common\Resources\CategorySimple;
use Travalorics\Panel\Requests\CategoryRequest;

class CategoryController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();

        $filters['parent_id'] = 0;

        $categories = CategoryRepo::getInstance()->all($filters);

        $data = [
            'categories' => CategorySimple::collection($categories)->jsonSerialize(),
        ];

        return travalorics_view('panel::categories.index', $data);
    }

    /**
     * Category creation page.
     *
     * @return mixed
     * @throws Exception
     */
    public function create(): mixed
    {
        return $this->form(new Category);
    }

    /**
     * @param  CategoryRequest  $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            $data     = $request->all();
            $category = CategoryRepo::getInstance()->create($data);

            return redirect(panel_route('categories.index'))
                ->with('instance', $category)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Category  $category
     * @return mixed
     * @throws Exception
     */
    public function edit(Category $category): mixed
    {
        return $this->form($category);
    }

    /**
     * @param  Category  $category
     * @return mixed
     */
    public function form(Category $category): mixed
    {
        $childIDs = $category->children->pluck('id')->toArray();
        if ($category->id) {
            $childIDs = array_merge($childIDs, [$category->id]);
        }

        $excludeIDs = array_unique($childIDs);
        $filters    = [
            'active'      => 1,
            'exclude_ids' => $excludeIDs,
        ];

        $hierarchicalCategories = CategoryRepo::getInstance()->getHierarchicalCategories($filters);

        array_unshift($hierarchicalCategories, [
            'id'    => 0,
            'name'  => panel_trans('category.root_category'),
            'level' => 0,
        ]);

        $data = [
            'category'   => $category,
            'categories' => $hierarchicalCategories,
        ];

        return travalorics_view('panel::categories.form', $data);
    }

    /**
     * @param  CategoryRequest  $request
     * @param  Category  $category
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $data = $request->all();
            CategoryRepo::getInstance()->update($category, $data);

            return redirect(panel_route('categories.index'))
                ->with('instance', $category)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Category  $category
     * @return mixed
     */
    public function destroy(Category $category): mixed
    {
        try {
            if ($category->children()->count()) {
                throw new \Exception(panel_trans('category.has_children'));
            }
            CategoryRepo::getInstance()->destroy($category);

            return json_success(panel_trans('common.deleted_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
