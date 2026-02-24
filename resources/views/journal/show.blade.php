@extends('layouts.app')
@section('title', 'Journal Entry')
@section('page-title', 'Journal Entry — ' . $journalEntry->entry_no)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-mono text-lg font-bold text-blue-600">{{ $journalEntry->entry_no }}</div>
                    <div class="text-sm text-gray-500 mt-0.5">Ref: {{ $journalEntry->reference }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-700">{{ $journalEntry->entry_date->format('d M Y') }}</div>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full capitalize">{{ $journalEntry->type }}</span>
                </div>
            </div>
        </div>
        <div class="px-6 py-3 bg-yellow-50 border-b border-yellow-100 text-sm text-gray-600 italic">
            📝 {{ $journalEntry->narration }}
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Account</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Type</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-red-500 uppercase">Debit (DR)</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-green-600 uppercase">Credit (CR)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($journalEntry->lines as $line)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3.5 font-medium text-gray-800">
                        @if($line->debit > 0)
                            {{ $line->account_name }}
                        @else
                            <span class="ml-6">{{ $line->account_name }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded capitalize">{{ $line->account_type }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-right font-mono font-bold text-red-600">
                        {{ $line->debit > 0 ? '৳'.number_format($line->debit, 2) : '' }}
                    </td>
                    <td class="px-6 py-3.5 text-right font-mono font-bold text-green-600">
                        {{ $line->credit > 0 ? '৳'.number_format($line->credit, 2) : '' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-blue-50">
                <tr>
                    <td colspan="2" class="px-6 py-3 font-bold text-gray-700">TOTAL</td>
                    <td class="px-6 py-3 text-right font-mono font-bold text-red-700">৳{{ number_format($journalEntry->lines->sum('debit'), 2) }}</td>
                    <td class="px-6 py-3 text-right font-mono font-bold text-green-700">৳{{ number_format($journalEntry->lines->sum('credit'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
        @if($journalEntry->lines->sum('debit') == $journalEntry->lines->sum('credit'))
        <div class="px-6 py-3 bg-green-50 border-t border-green-100 text-xs text-green-700 font-medium">
            ✅ Entry is balanced — Debit = Credit
        </div>
        @else
        <div class="px-6 py-3 bg-red-50 border-t border-red-100 text-xs text-red-700 font-medium">
            ⚠️ Entry is NOT balanced!
        </div>
        @endif
    </div>
    <div class="mt-4">
        <a href="{{ route('journal.index') }}" class="text-blue-600 text-sm hover:underline">← Back to Journal</a>
    </div>
</div>
@endsection