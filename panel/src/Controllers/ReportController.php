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
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Travalorics\Common\Repositories\OrderRepo;
use Travalorics\Common\Repositories\CustomerRepo;
use Travalorics\Common\Repositories\ProductRepo;

class ReportController extends BaseController
{
    /**
     * Display reports interface
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $type = $request->get('type', 'orders');
        $filters = $request->all();
        
        $data = [
            'type' => $type,
            'filters' => $filters,
        ];

        switch ($type) {
            case 'orders':
                $data['criteria'] = OrderRepo::getCriteria();
                $data['items'] = OrderRepo::getInstance()->list($filters);
                $ordersQuery = OrderRepo::getInstance()->builder($filters);

                // حساب الإجمالي حسب كل عملة
                $revenueByСurrency = (clone $ordersQuery)
                    ->selectRaw('currency_code, SUM(total) as total_amount, COUNT(*) as orders_count')
                    ->groupBy('currency_code')
                    ->get();

                $data['stats'] = [
                    'total_count'       => $ordersQuery->count(),
                    'total_revenue'     => $ordersQuery->sum('total'),
                    'revenue_by_currency' => $revenueByСurrency,
                ];
                break;
            case 'customers':
                $data['criteria'] = CustomerRepo::getCriteria();
                $data['items'] = CustomerRepo::getInstance()->list($filters);
                $customersQuery = CustomerRepo::getInstance()->builder($filters);
                $data['stats'] = [
                    'total_count' => $customersQuery->count(),
                    'active_count' => (clone $customersQuery)->where('active', 1)->count()
                ];
                break;
            case 'products':
                $data['criteria'] = ProductRepo::getCriteria();
                $data['items'] = ProductRepo::getInstance()->list($filters);
                $productsQuery = ProductRepo::getInstance()->builder($filters);
                $data['stats'] = [
                    'total_count' => $productsQuery->count(),
                    'active_count' => (clone $productsQuery)->where('active', 1)->count()
                ];
                break;
            default:
                abort(404);
        }

        return travalorics_view('panel::reports.index', $data);
    }

    /**
     * Export reports
     *
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'orders');
        $filters = $request->all();

        $exportData = collect();

        switch ($type) {
            case 'orders':
                $items = OrderRepo::getInstance()->builder($filters)->get();
                foreach ($items as $item) {
                    $exportData->push([
                        panel_trans('order.number') => $item->number,
                        panel_trans('order.created_at') => $item->created_at,
                        panel_trans('order.customer_name') => $item->customer_name ?? ($item->customer->name ?? ''),
                        panel_trans('order.status') => $item->status_format ?? $item->status,
                        panel_trans('order.total') => $item->total_format,
                        panel_trans('order.payment_method') => $item->billing_method_name,
                    ]);
                }
                $filename = 'orders_report_' . date('YmdHis') . '.xlsx';
                break;
            case 'customers':
                $items = CustomerRepo::getInstance()->builder($filters)->get();
                foreach ($items as $item) {
                    $exportData->push([
                        panel_trans('customer.name') => $item->name,
                        panel_trans('customer.email') => $item->email,
                        panel_trans('common.created_at') => $item->created_at,
                        'From' => $item->from,
                        'Active' => $item->active ? 'Yes' : 'No',
                    ]);
                }
                $filename = 'customers_report_' . date('YmdHis') . '.xlsx';
                break;
            case 'products':
                $items = ProductRepo::getInstance()->builder($filters)->get();
                foreach ($items as $item) {
                    $exportData->push([
                        panel_trans('product.name') => $item->fallbackName(),
                        panel_trans('product.model') => $item->masterSku->model ?? $item->spu_code,
                        panel_trans('product.price') => currency_format($item->masterSku->price ?? 0),
                        panel_trans('product.quantity') => $item->totalQuantity(),
                        panel_trans('common.created_at') => $item->created_at,
                        'Active' => $item->active ? 'Yes' : 'No',
                    ]);
                }
                $filename = 'products_report_' . date('YmdHis') . '.xlsx';
                break;
            default:
                abort(404);
        }

        return (new FastExcel($exportData))->download($filename);
    }

    /**
     * Print reports
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function print(Request $request): mixed
    {
        $type = $request->get('type', 'orders');
        $filters = $request->all();

        $data = [
            'type' => $type,
            'filters' => $filters,
        ];

        // Retrieve all records for printing (no pagination, or large pagination)
        switch ($type) {
            case 'orders':
                $data['criteria'] = OrderRepo::getCriteria();
                $data['items'] = OrderRepo::getInstance()->builder($filters)->get();
                break;
            case 'customers':
                $data['criteria'] = CustomerRepo::getCriteria();
                $data['items'] = CustomerRepo::getInstance()->builder($filters)->get();
                break;
            case 'products':
                $data['criteria'] = ProductRepo::getCriteria();
                $data['items'] = ProductRepo::getInstance()->builder($filters)->get();
                break;
            default:
                abort(404);
        }

        return travalorics_view('panel::reports.print', $data);
    }
}
