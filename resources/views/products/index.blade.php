@extends('layouts.app')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">Manage your product inventory</p>
    <a href="{{ route('products.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Add Product
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Product</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">SKU</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Purchase Price</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Sell Price</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Opening Stock</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Current Stock</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Stock Value</th>
                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <div class="font-medium text-gray-800">{{ $product->name }}</div>
                    @if($product->description)
                        <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($product->description, 40) }}</div>
                    @endif
                </td>
                <td class="px-5 py-4 font-mono text-xs text-gray-500">{{ $product->sku }}</td>
                <td class="px-5 py-4 text-right font-semibold text-gray-700">৳{{ number_format($product->purchase_price, 2) }}</td>
                <td class="px-5 py-4 text-right font-semibold text-blue-600">৳{{ number_format($product->sell_price, 2) }}</td>
                <td class="px-5 py-4 text-right text-gray-600">{{ $product->opening_stock }}</td>
                <td class="px-5 py-4 text-right">
                    <span class="font-bold {{ $product->current_stock <= 5 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $product->current_stock }}
                    </span>
                    @if($product->current_stock <= 5)
                        <span class="ml-1 text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded">Low</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right text-amber-600 font-semibold">৳{{ number_format($product->stock_value, 2) }}</td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('products.edit', $product) }}"
                           class="text-blue-600 text-xs font-medium border border-blue-200 px-2.5 py-1 rounded hover:bg-blue-50 transition">
                            Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 text-xs font-medium border border-red-200 px-2.5 py-1 rounded hover:bg-red-50 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center">
                    <div class="text-gray-400 text-4xl mb-3">📦</div>
                    <div class="text-gray-500 font-medium">No products found</div>
                    <a href="{{ route('products.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">Add your first product →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection