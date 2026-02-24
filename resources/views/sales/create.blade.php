@extends('layouts.app')
@section('title', 'New Sale')
@section('page-title', 'Create New Sale')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Product <span class="text-red-500">*</span></label>
                        <select name="product_id" id="product_id" required onchange="updatePrice()"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                        data-price="{{ $product->sell_price }}"
                                        data-stock="{{ $product->current_stock }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Stock: {{ $product->current_stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Customer Name <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', 'Walk-in Customer') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required
                               oninput="calculate()"
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p id="stock-info" class="text-xs text-gray-400 mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit Sell Price (TK)</label>
                        <input type="text" id="unit_price_display" readonly
                               class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Discount (TK)</label>
                        <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}" min="0" step="0.01"
                               oninput="calculate()"
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">VAT Rate (%)</label>
                        <input type="number" name="vat_rate" id="vat_rate" value="{{ old('vat_rate', 5) }}" min="0" max="100" step="0.01"
                               oninput="calculate()"
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount Paid (TK) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount_paid" id="amount_paid" value="{{ old('amount_paid', 0) }}" min="0" step="0.01" required
                               oninput="calculate()"
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sale Date <span class="text-red-500">*</span></label>
                        <input type="date" name="sale_date" value="{{ old('sale_date', now()->toDateString()) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Optional...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition text-sm">
                        💾 Record Sale
                    </button>
                    <a href="{{ route('sales.index') }}"
                       class="text-gray-600 font-medium px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div>
        <div class="bg-gray-900 text-white rounded-xl p-5 sticky top-24">
            <div class="text-xs font-semibold text-amber-400 uppercase tracking-widest mb-4">Live Calculation</div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Quantity</span>
                    <span id="p_qty" class="font-mono font-semibold">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Unit Price</span>
                    <span id="p_unit" class="font-mono font-semibold">৳0.00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Gross Amount</span>
                    <span id="p_gross" class="font-mono font-semibold">৳0.00</span>
                </div>
                <div class="flex justify-between text-red-400">
                    <span>(-) Discount</span>
                    <span id="p_discount" class="font-mono font-semibold">৳0.00</span>
                </div>
                <div class="flex justify-between border-t border-gray-700 pt-3">
                    <span class="text-gray-300 font-medium">Net Amount</span>
                    <span id="p_net" class="font-mono font-bold text-amber-400">৳0.00</span>
                </div>
                <div class="flex justify-between text-purple-400">
                    <span>(+) VAT</span>
                    <span id="p_vat" class="font-mono font-semibold">৳0.00</span>
                </div>
                <div class="flex justify-between border-t border-gray-700 pt-3">
                    <span class="text-white font-bold">Total Payable</span>
                    <span id="p_total" class="font-mono font-bold text-green-400 text-base">৳0.00</span>
                </div>
                <div class="flex justify-between text-green-400">
                    <span>(-) Amount Paid</span>
                    <span id="p_paid" class="font-mono font-semibold">৳0.00</span>
                </div>
                <div class="flex justify-between border-t border-gray-700 pt-3">
                    <span class="text-red-300 font-bold">Due Amount</span>
                    <span id="p_due" class="font-mono font-bold text-red-400 text-base">৳0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentPrice = 0;

    function updatePrice() {
        const select = document.getElementById('product_id');
        const option = select.options[select.selectedIndex];
        currentPrice = parseFloat(option.dataset.price || 0);
        const stock = option.dataset.stock || 0;
        document.getElementById('unit_price_display').value = '৳' + currentPrice.toFixed(2);
        document.getElementById('stock-info').textContent = 'Available: ' + stock + ' units';
        calculate();
    }

    function calculate() {
        const qty      = parseFloat(document.getElementById('quantity').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const vatRate  = parseFloat(document.getElementById('vat_rate').value) || 0;
        const paid     = parseFloat(document.getElementById('amount_paid').value) || 0;

        const gross  = qty * currentPrice;
        const net    = Math.max(0, gross - discount);
        const vat    = parseFloat((net * vatRate / 100).toFixed(2));
        const total  = parseFloat((net + vat).toFixed(2));
        const due    = Math.max(0, total - paid);

        document.getElementById('p_qty').textContent      = qty;
        document.getElementById('p_unit').textContent     = '৳' + currentPrice.toFixed(2);
        document.getElementById('p_gross').textContent    = '৳' + gross.toFixed(2);
        document.getElementById('p_discount').textContent = '৳' + discount.toFixed(2);
        document.getElementById('p_net').textContent      = '৳' + net.toFixed(2);
        document.getElementById('p_vat').textContent      = '৳' + vat.toFixed(2);
        document.getElementById('p_total').textContent    = '৳' + total.toFixed(2);
        document.getElementById('p_paid').textContent     = '৳' + paid.toFixed(2);
        document.getElementById('p_due').textContent      = '৳' + due.toFixed(2);
    }

    calculate();
</script>
@endpush
@endsection