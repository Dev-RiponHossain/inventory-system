@extends('layouts.app')
@section('title', 'Invoice ' . $sale->invoice_no)
@section('page-title', 'Sale Details — ' . $sale->invoice_no)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Invoice</h2>
                <p class="font-mono text-blue-600 text-sm mt-0.5">{{ $sale->invoice_no }}</p>
            </div>
            @if($sale->payment_status === 'paid')
                <span class="bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-full">✓ PAID</span>
            @elseif($sale->payment_status === 'partial')
                <span class="bg-amber-100 text-amber-700 text-sm font-bold px-3 py-1 rounded-full">⚡ PARTIAL</span>
            @else
                <span class="bg-red-100 text-red-700 text-sm font-bold px-3 py-1 rounded-full">⚠ DUE</span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide mb-1">Customer</div>
                <div class="font-medium">{{ $sale->customer_name }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide mb-1">Date</div>
                <div class="font-medium">{{ $sale->sale_date->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide mb-1">Product</div>
                <div class="font-medium">{{ $sale->product->name }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide mb-1">SKU</div>
                <div class="font-mono text-xs text-gray-600">{{ $sale->product->sku }}</div>
            </div>
        </div>

        <div class="space-y-2 text-sm border-t border-gray-100 pt-3">
            <div class="flex justify-between">
                <span class="text-gray-500">Quantity × Unit Price</span>
                <span>{{ $sale->quantity }} × ৳{{ number_format($sale->unit_price, 2) }}</span>
            </div>
            <div class="flex justify-between font-medium">
                <span class="text-gray-500">Gross Amount</span>
                <span>৳{{ number_format($sale->gross_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-red-600">
                <span>(-) Discount</span>
                <span>৳{{ number_format($sale->discount, 2) }}</span>
            </div>
            <div class="flex justify-between font-medium border-t border-gray-100 pt-2">
                <span>Net Amount</span>
                <span>৳{{ number_format($sale->net_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-purple-600">
                <span>(+) VAT ({{ $sale->vat_rate }}%)</span>
                <span>৳{{ number_format($sale->vat_amount, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-base border-t border-gray-200 pt-2">
                <span>Total Payable</span>
                <span class="text-gray-800">৳{{ number_format($sale->total_payable, 2) }}</span>
            </div>
            <div class="flex justify-between text-green-600 font-medium">
                <span>(-) Amount Paid</span>
                <span>৳{{ number_format($sale->amount_paid, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-red-600 text-base border-t border-gray-200 pt-2">
                <span>Due Amount</span>
                <span>৳{{ number_format($sale->due_amount, 2) }}</span>
            </div>
        </div>

        <div class="mt-4 bg-blue-50 rounded-lg p-3 text-xs text-blue-700">
            <strong>COGS:</strong> {{ $sale->quantity }} × ৳{{ number_format($sale->product->purchase_price, 2) }} = ৳{{ number_format($sale->cogs, 2) }}<br>
            <strong>Gross Profit:</strong> ৳{{ number_format($sale->net_amount - $sale->cogs, 2) }}
        </div>
    </div>

    <div class="space-y-4">
        <h2 class="font-semibold text-gray-800">Accounting Journal Entries</h2>
        @foreach($journalEntries as $entry)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <span class="font-mono text-xs text-blue-600 font-semibold">{{ $entry->entry_no }}</span>
                    <span class="text-gray-400 text-xs ml-2">{{ $entry->entry_date->format('d M Y') }}</span>
                </div>
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full capitalize">{{ $entry->type }}</span>
            </div>
            <div class="px-4 py-2 text-xs text-gray-500 italic border-b border-gray-100">
                {{ $entry->narration }}
            </div>
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-4 py-2 text-gray-500">Account</th>
                        <th class="text-right px-4 py-2 text-red-500">DR</th>
                        <th class="text-right px-4 py-2 text-green-600">CR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entry->lines as $line)
                    <tr class="border-b border-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-700">
                            @if($line->debit > 0)
                                {{ $line->account_name }}
                            @else
                                <span class="ml-4">{{ $line->account_name }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right font-mono font-semibold text-red-600">
                            {{ $line->debit > 0 ? '৳'.number_format($line->debit, 2) : '' }}
                        </td>
                        <td class="px-4 py-2 text-right font-mono font-semibold text-green-600">
                            {{ $line->credit > 0 ? '৳'.number_format($line->credit, 2) : '' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-4 py-2 font-bold text-xs text-gray-600">Total</td>
                        <td class="px-4 py-2 text-right font-mono font-bold text-red-600">৳{{ number_format($entry->lines->sum('debit'), 2) }}</td>
                        <td class="px-4 py-2 text-right font-mono font-bold text-green-600">৳{{ number_format($entry->lines->sum('credit'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endforeach
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('sales.index') }}" class="text-blue-600 text-sm hover:underline">← Back to Sales</a>
</div>
@endsection