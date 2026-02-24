<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StockLedger') — Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: #1e40af; color: white; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <div class="px-6 py-5 border-b border-gray-700">
            <div class="text-xl font-bold text-amber-400">📦 StockLedger</div>
            <div class="text-xs text-gray-400 mt-0.5">Inventory Management System</div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                🏠 Dashboard
            </a>
            <a href="{{ route('products.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('products.*') ? 'active' : '' }}">
                📦 Products
            </a>
            <a href="{{ route('sales.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                🛒 Sales
            </a>
            <a href="{{ route('journal.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('journal.*') ? 'active' : '' }}">
                📒 Journal Entries
            </a>
            <a href="{{ route('reports.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                📈 Financial Reports
            </a>
        </nav>

        <div class="px-4 py-3 border-t border-gray-700 text-xs text-gray-500">
            © 2025 StockLedger v1.0
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                <a href="{{ route('sales.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    + New Sale
                </a>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>