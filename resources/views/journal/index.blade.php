@extends('layouts.app')
@section('title', 'Journal Entries')
@section('page-title', 'Accounting Journal Entries')

@section('content')

<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form action="{{ route('journal.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Types</option>
                <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>Sale</option>
                <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Payment</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
            Filter
        </button>
        <a href="{{ route('journal.index') }}" class="text-gray-500 text-sm px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Reset
        </a>
    </form>
</div>

<div class="space-y-4">
    @forelse($entries as $entry)
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <span class="font-mono text-sm font-bold text-blue-600">{{ $entry->entry_no }}</span>
                <span class="text-gray-400 text-xs">Ref: {{ $entry->reference }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full capitalize">{{ $entry->type }}</span>
                <span class="text-sm text-gray-500">{{ $entry->entry_date->format('d M Y') }}</span>
                <a href="{{ route('journal.show', $entry) }}" class="text-blue-600 text-xs hover:underline">View →</a>
            </div>
        </div>
        <div class="px-5 py-2 text-xs text-gray-500 italic border-b border-gray-100 bg-yellow-50">
            📝 {{ $entry->narration }}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-2.5 text-xs font-medium text-gray-500">Account Name</th>
                        <th class="text-left px-5 py-2.5 text-xs font-medium text-gray-500">Type</th>
                        <th class="text-right px-5 py-2.5 text-xs font-medium text-red-500">Debit (DR)</th>
                        <th class="text-right px-5 py-2.5 text-xs font-medium text-green-600">Credit (CR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entry->lines as $line)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-5 py-2.5 text-gray-700 font-medium">
                            @if($line->debit > 0)
                                {{ $line->account_name }}
                            @else
                                <span class="ml-6">{{ $line->account_name }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-2.5">
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded capitalize">{{ $line->account_type }}</span>
                        </td>
                        <td class="px-5 py-2.5 text-right font-mono font-semibold text-red-600">
                            {{ $line->debit > 0 ? '৳'.number_format($line->debit, 2) : '—' }}
                        </td>
                        <td class="px-5 py-2.5 text-right font-mono font-semibold text-green-600">
                            {{ $line->credit > 0 ? '৳'.number_format($line->credit, 2) : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="2" class="px-5 py-2.5 text-xs font-bold text-gray-600">TOTAL</td>
                        <td class="px-5 py-2.5 text-right font-mono font-bold text-red-600">৳{{ number_format($entry->lines->sum('debit'), 2) }}</td>
                        <td class="px-5 py-2.5 text-right font-mono font-bold text-green-600">৳{{ number_format($entry->lines->sum('credit'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
        <div class="text-5xl mb-4">📒</div>
        <div class="text-gray-500 font-medium">No journal entries found</div>
        <p class="text-gray-400 text-sm mt-1">Journal entries are created automatically when you record a sale</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $entries->withQueryString()->links() }}</div>
@endsection