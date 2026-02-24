<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\JournalEntry;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalSales      = Sale::sum('net_amount');
        $totalCogs       = Sale::sum('cogs');
        $totalProfit     = $totalSales - $totalCogs;
        $totalDue        = Sale::sum('due_amount');
        $totalVat        = Sale::sum('vat_amount');
        $totalDiscount   = Sale::sum('discount');
        $totalJournals   = JournalEntry::count();

        $recentSales = Sale::with('product')->latest()->take(5)->get();
        $products    = Product::all();

        $lowStockProducts = Product::where('current_stock', '<=', 5)->get();

        $todaySales = Sale::whereDate('sale_date', today())->sum('net_amount');
        $todayCogs  = Sale::whereDate('sale_date', today())->sum('cogs');

        return view('dashboard', compact(
            'totalProducts', 'totalSales', 'totalCogs', 'totalProfit',
            'totalDue', 'totalVat', 'totalDiscount', 'totalJournals',
            'recentSales', 'products', 'lowStockProducts',
            'todaySales', 'todayCogs'
        ));
    }
}