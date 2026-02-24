@extends('layouts.app')
@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@section('content')

<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
            <input type="date" name="from_date" value="{{ $fromDate }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
            <input type="date" name="to_date" value="{{ $toDate }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
            🔍 Apply Filter
        </button>
        <a href="{{ route('reports.index') }}" class="text-gray-500 text-sm px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Reset
        </a>
    </form>
</div>

@if($totals)
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-blue-600 text-white rounded-xl p-4">
        <div class="text-xs font-medium text-blue-200 mb-1">Total Net Sales</div>
        <div class="text-2xl font-bold">৳{{ number_format($totals->net_sales ?? 0, 2) }}</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="text-xs text-gray-500 mb-1">Gross Sales</div>
        <div class="text-xl font-bold text-gray-800">৳{{ number_format($totals->gross_sales ?? 0, 2) }}</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="text-xs text-gray-500 mb-1">Total COGS</div>
        <div class="text-xl font-bold text-amber-600">৳{{ number_format($totals->total_cogs ?? 0, 2) }}</div>
    </div>
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="text-xs text-green-600 mb-1">Gross Profit</div>
        <div class="text-xl font-bold text-green-700">৳{{ number_format($totals->total_profit ?? 0, 2) }}</div>
    </div>
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="text-xs text-red-500 mb-1">Total Due</div>
        <div class="text-xl font-bold text-red-600">৳{{ number_format($totals->total_due ?? 0, 2) }}</div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">📅 Date-wise Financial Report</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Txn</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-blue-600 uppercase">Net Sales</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-amber-600 uppercase">COGS</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-red-500 uppercase">Discount</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-purple-600 uppercase">VAT</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-green-600 uppercase">Profit</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-red-600 uppercase">Due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dailyReport as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($row->sale_date)->format('d M Y') }}
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($row->sale_date)->format('l') }}</div>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500">{{ $row->total_transactions }}</td>
                            <td class="px-4 py-3 text-right font-bold text-blue-600">৳{{ number_format($row->total_net_sales, 2) }}</td>
                            <td class="px-4 py-3 text-right text-amber-600">৳{{ number_format($row->total_cogs, 2) }}</td>
                            <td class="px-4 py-3 text-right text-red-500">৳{{ number_format($row->total_discount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-purple-600">৳{{ number_format($row->total_vat, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $row->total_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ৳{{ number_format($row->total_profit, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-red-500">৳{{ number_format($row->total_due, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                                <div class="text-4xl mb-3">📊</div>
                                No data for selected date range
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">📦 Product-wise Breakdown</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($productReport as $row)
                <div class="px-5 py-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-medium text-gray-800 text-sm">{{ $row->product->name ?? 'N/A' }}</div>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $row->total_qty }} units</span>
                    </div>
                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between text-gray-500">
                            <span>Net Sales</span>
                            <span class="font-semibold text-blue-600">৳{{ number_format($row->total_sales, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>COGS</span>
                            <span class="font-semibold text-amber-600">৳{{ number_format($row->total_cogs, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-100 pt-1">
                            <span>Profit</span>
                            <span class="font-bold {{ $row->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ৳{{ number_format($row->profit, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">No data</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection