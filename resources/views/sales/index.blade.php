@extends('layouts.app')
@section('title', 'Sales')
@section('page-title', 'Sales')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">All sales transactions</p>
    <a href="{{ route('sales.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + New Sale
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Invoice</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Customer</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Product</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Qty</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Gross</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Discount</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">VAT</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Total</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Paid</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Due</th>
                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Status</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sales as $sale)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3.5">
                    <a href="{{ route('sales.show', $sale) }}" class="font-mono text-xs text-blue-600 hover:underline">
                        {{ $sale->invoice_no }}
                    </a>
                </td>
                <td class="px-5 py-3.5 text-gray-700">{{ $sale->customer_name }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $sale->product->name }}</td>
                <td class="px-5 py-3.5 text-right font-medium">{{ $sale->quantity }}</td>
                <td class="px-5 py-3.5 text-right text-gray-600">৳{{ number_format($sale->gross_amount, 2) }}</td>
                <td class="px-5 py-3.5 text-right text-red-500">-৳{{ number_format($sale->discount, 2) }}</td>
                <td class="px-5 py-3.5 text-right text-purple-600">৳{{ number_format($sale->vat_amount, 2) }}</td>
                <td class="px-5 py-3.5 text-right font-bold text-gray-800">৳{{ number_format($sale->total_payable, 2) }}</td>
                <td class="px-5 py-3.5 text-right text-green-600 font-medium">৳{{ number_format($sale->amount_paid, 2) }}</td>
                <td class="px-5 py-3.5 text-right text-red-600 font-medium">৳{{ number_format($sale->due_amount, 2) }}</td>
                <td class="px-5 py-3.5 text-center">
                    @if($sale->payment_status === 'paid')
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Paid</span>
                    @elseif($sale->payment_status === 'partial')
                        <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">Partial</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Due</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $sale->sale_date->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="px-5 py-12 text-center">
                    <div class="text-gray-400 text-4xl mb-3">🛒</div>
                    <div class="text-gray-500 font-medium">No sales yet</div>
                    <a href="{{ route('sales.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Create first sale →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection