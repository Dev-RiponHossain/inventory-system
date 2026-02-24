@extends('layouts.app')
@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. Sample Product">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">SKU <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. PRD-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Opening Stock <span class="text-red-500">*</span></label>
                    <input type="number" name="opening_stock" value="{{ old('opening_stock', 0) }}" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Purchase Price (TK) <span class="text-red-500">*</span></label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" min="0" step="0.01" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="100.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Sell Price (TK) <span class="text-red-500">*</span></label>
                    <input type="number" name="sell_price" value="{{ old('sell_price') }}" min="0" step="0.01" required
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="200.00">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Optional...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition text-sm">
                    Save Product
                </button>
                <a href="{{ route('products.index') }}"
                   class="text-gray-600 font-medium px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection