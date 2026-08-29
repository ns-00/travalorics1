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
use Travalorics\Common\Models\Review;
use Travalorics\Common\Repositories\ReviewRepo;
use Travalorics\Panel\Requests\ReviewRequest;

class ReviewController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();
        $data    = [
            'criteria' => ReviewRepo::getCriteria(),
            'reviews'  => ReviewRepo::getInstance()->list($filters),
        ];

        return travalorics_view('panel::reviews.index', $data);
    }

    /**
     * Review creation page.
     *
     * @return mixed
     * @throws Exception
     */
    public function create(): mixed
    {
        return $this->form(new Review);
    }

    /**
     * @param  ReviewRequest  $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(ReviewRequest $request): RedirectResponse
    {
        try {
            $data   = $request->all();
            $review = ReviewRepo::getInstance()->create($data);

            return redirect(panel_route('reviews.index'))
                ->with('instance', $review)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect(panel_route('reviews.index'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Review  $review
     * @return mixed
     * @throws Exception
     */
    public function edit(Review $review): mixed
    {
        return $this->form($review);
    }

    /**
     * @param  $review
     * @return mixed
     * @throws Exception
     */
    public function form($review): mixed
    {
        $data = [
            'review' => $review,
        ];

        return travalorics_view('panel::reviews.form', $data);
    }

    /**
     * @param  ReviewRequest  $request
     * @param  Review  $review
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(ReviewRequest $request, Review $review): RedirectResponse
    {
        try {
            $data = $request->all();
            ReviewRepo::getInstance()->update($review, $data);

            return redirect(panel_route('reviews.index'))
                ->with('instance', $review)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect(panel_route('reviews.index'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Review  $review
     * @return mixed
     */
    public function destroy(Review $review): mixed
    {
        try {
            ReviewRepo::getInstance()->destroy($review);

            return delete_json_success();
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param  Review  $review
     * @return mixed
     */
    public function feature(Review $review): mixed
    {
        try {
            // Toggle featured rank (e.g. 1 means featured, 0 means not)
            $review->featured_rank = $review->featured_rank > 0 ? 0 : 1;
            $review->save();

            return response()->json(['success' => true, 'message' => 'Review feature status updated.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param  Request $request
     * @param  Review  $review
     * @return mixed
     */
    public function updateStatus(Request $request, Review $review): mixed
    {
        try {
            if ($request->has('status')) {
                $review->status = $request->status;
                $review->save();
            }

            return response()->json(['success' => true, 'message' => 'Review status updated.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
