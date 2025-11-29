<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'POS Warung') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50">

    <!-- Online Indicator -->
    <div id="connection-status" class="fixed top-4 right-4 z-50 hidden">
        <div class="flex items-center gap-2 px-4 py-2 rounded-lg shadow-lg">
            <div class="w-3 h-3 rounded-full"></div>
            <span class="text-sm font-medium"></span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('images\WarungKu_Logo.png') }}" alt="Logo" width="40" height="40">
                        <h1 class="text-2xl font-bold text-indigo-600">WarungKu</h1>
                    </div>
                    <!-- Desktop Menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('cashier') }}" class="@if(request()->routeIs('cashier')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Kasir
                        </a>
                        <a href="{{ route('products') }}" class="@if(request()->routeIs('products')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Produk
                        </a>
                        <a href="{{ route('transactions') }}" class="@if(request()->routeIs('transactions')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Riwayat
                        </a>
                    </div>
                </div>

                <!-- User Info & Mobile Menu Button -->
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-4 hidden sm:inline">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                    </form>

                    <!-- Mobile Hamburger -->
                    <div class="sm:hidden flex items-center">
                        <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="sm:hidden hidden px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Dashboard</a>
            <a href="{{ route('cashier') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Kasir</a>
            <a href="{{ route('products') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Produk</a>
            <a href="{{ route('transactions') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Riwayat</a>
            <span class="block px-3 py-2 rounded-md text-base font-medium text-gray-700">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/indexeddb.js') }}"></script>
    <script src="{{ asset('js/sync.js') }}"></script>
    
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Service Worker registered'))
                .catch(err => console.log('Service Worker registration failed'));
        }

        // Connection status indicator
        function updateConnectionStatus() {
            const statusDiv = document.getElementById('connection-status');
            const isOnline = navigator.onLine;
            
            statusDiv.classList.remove('hidden');
            const indicator = statusDiv.querySelector('.w-3');
            const text = statusDiv.querySelector('span');
            const container = statusDiv.querySelector('.flex');
            
            if (isOnline) {
                container.classList.remove('bg-red-100');
                container.classList.add('bg-green-100');
                indicator.classList.remove('bg-red-500');
                indicator.classList.add('bg-green-500');
                text.textContent = 'Online';
                text.classList.remove('text-red-700');
                text.classList.add('text-green-700');
                
                setTimeout(() => statusDiv.classList.add('hidden'), 1000);
            } else {
                container.classList.remove('bg-green-100');
                container.classList.add('bg-red-100');
                indicator.classList.remove('bg-green-500');
                indicator.classList.add('bg-red-500');
                text.textContent = 'Offline';
                text.classList.remove('text-green-700');
                text.classList.add('text-red-700');
            }
        }

        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);
        setTimeout(updateConnectionStatus, 500);

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
