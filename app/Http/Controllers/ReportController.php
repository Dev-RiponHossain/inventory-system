<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->toDateString();
        $toDate   = $request->to_date   ?? now()->toDateString();

        $dailyReport = Sale::selectRaw("
                sale_date,
                COUNT(*)                    as total_transactions,
                SUM(gross_amount)           as total_gross_sales,
                SUM(discount)               as total_discount,
                SUM(net_amount)             as total_net_sales,
                SUM(vat_amount)             as total_vat,
                SUM(total_payable)          as total_payable,
                SUM(amount_paid)            as total_collected,
                SUM(due_amount)             as total_due,
                SUM(cogs)                   as total_cogs,
                SUM(net_amount) - SUM(cogs) as total_profit
            ")
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'desc')
            ->get();

        $totals = Sale::whereBetween('sale_date', [$fromDate, $toDate])
            ->selectRaw("
                SUM(gross_amount)           as gross_sales,
                SUM(discount)               as total_discount,
                SUM(net_amount)             as net_sales,
                SUM(vat_amount)             as total_vat,
                SUM(total_payable)          as total_payable,
                SUM(amount_paid)            as total_collected,
                SUM(due_amount)             as total_due,
                SUM(cogs)                   as total_cogs,
                SUM(net_amount) - SUM(cogs) as total_profit
            ")
            ->first();

        $productReport = Sale::with('product')
            ->selectRaw("
                product_id,
                SUM(quantity)               as total_qty,
                SUM(net_amount)             as total_sales,
                SUM(cogs)                   as total_cogs,
                SUM(net_amount) - SUM(cogs) as profit
            ")
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->groupBy('product_id')
            ->get();

        return view('reports.index', compact(
            'dailyReport', 'totals', 'productReport', 'fromDate', 'toDate'
        ));
    }
}