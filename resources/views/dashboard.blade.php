@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Products</div>
        <div class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</div>
        <div class="text-xs text-gray-400 mt-1">in inventory</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Net Sales</div>
        <div class="text-3xl font-bold text-blue-600">৳{{ number_format($totalSales, 2) }}</div>
        <div class="text-xs text-gray-400 mt-1">after discount</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Profit</div>
        <div class="text-3xl font-bold text-green-600">৳{{ number_format($totalProfit, 2) }}</div>
        <div class="text-xs text-gray-400 mt-1">sales - COGS</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Due</div>
        <div class="text-3xl font-bold text-red-500">৳{{ number_format($totalDue, 2) }}</div>
        <div class="text-xs text-gray-400 mt-1">outstanding</div>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-amber-50 rounded-xl border border-amber-200 p-4">
        <div class="text-xs text-amber-600 font-medium mb-1">Total COGS</div>
        <div class="text-xl font-bold text-amber-700">৳{{ number_format($totalCogs, 2) }}</div>
    </div>
    <div class="bg-purple-50 rounded-xl border border-purple-200 p-4">
        <div class="text-xs text-purple-600 font-medium mb-1">Total VAT Collected</div>
        <div class="text-xl font-bold text-purple-700">৳{{ number_format($totalVat, 2) }}</div>
    </div>
    <div class="bg-pink-50 rounded-xl border border-pink-200 p-4">
        <div class="text-xs text-pink-600 font-medium mb-1">Total Discount Given</div>
        <div class="text-xl font-bold text-pink-700">৳{{ number_format($totalDiscount, 2) }}</div>
    </div>
    <div class="bg-indigo-50 rounded-xl border border-indigo-200 p-4">
        <div class="text-xs text-indigo-600 font-medium mb-1">Journal Entries</div>
        <div class="text-xl font-bold text-indigo-700">{{ $totalJournals }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Recent Sales</h2>
            <a href="{{ route('sales.index') }}" class="text-blue-600 text-sm hover:underline">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-5 py-3 font-mono text-xs text-blue-600">
                            <a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_no }}</a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">{{ $sale->product->name }}</td>
                        <td class="px-5 py-3 font-semibold">৳{{ number_format($sale->total_payable, 2) }}</td>
                        <td class="px-5 py-3">
                            @if($sale->payment_status === 'paid')
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Paid</span>
                            @elseif($sale->payment_status === 'partial')
                                <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">Partial</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Due</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $sale->sale_date->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                            No sales yet. <a href="{{ route('sales.create') }}" class="text-blue-600 hover:underline">Create first sale →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Stock Status</h2>
            <a href="{{ route('products.index') }}" class="text-blue-600 text-sm hover:underline">View all →</a>
        </div>
        <div class="p-4 space-y-3">
            @forelse($products as $product)
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <div>
                    <div class="text-sm font-medium text-gray-800">{{ $product->name }}</div>
                    <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold {{ $product->current_stock <= 5 ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $product->current_stock }} units
                    </div>
                    @if($product->current_stock <= 5)
                        <div class="text-xs text-red-500 font-medium">Low Stock!</div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-gray-400 text-sm">
                No products. <a href="{{ route('products.create') }}" class="text-blue-600 hover:underline">Add one →</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection