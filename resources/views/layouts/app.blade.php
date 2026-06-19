<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Genesis') }} — @yield('title', 'Design System Files')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-bg text-text-primary antialiased">
    <nav class="sticky top-0 z-50 h-14 border-b border-border bg-surface/80 backdrop-blur-lg">
        <div class="mx-auto flex h-full max-w-[1280px] items-center justify-between px-6">
            <a href="/" class="font-display text-lg font-semibold tracking-tight">MWL 13</a>
            <div class="hidden items-center gap-6 md:flex">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">Dashboard</a>
                        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">Produk</a>
                        <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">Kategori</a>
                        <a href="{{ route('admin.transactions.index') }}" class="text-sm font-medium {{ request()->routeIs('admin.transactions.*') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">Transaksi</a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">Marketplace</a>
                        <a href="{{ route('customer.cart.index') }}" class="relative text-sm font-medium {{ request()->routeIs('customer.cart.*') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">
                            <span class="inline-flex items-center gap-1.5">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Keranjang
                                @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp
                                @if ($cartCount > 0)
                                    <span class="absolute -right-2.5 -top-2 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold leading-none text-white">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                                @endif
                            </span>
                        </a>
                        <a href="{{ route('customer.transactions.index') }}" class="text-sm font-medium {{ request()->routeIs('customer.transactions.*') ? 'text-primary' : 'text-text-secondary hover:text-text-primary' }} transition-colors">Riwayat</a>
                    @endif
                @else
                    <span class="text-sm text-text-secondary">Temukan produk terbaik di sini</span>
                @endauth
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost text-sm cursor-pointer">Log out</button>
                    </form>
                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @else
                    <a href="{{ url('/login') }}" class="btn btn-secondary text-sm">Log in</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary text-sm">Register</a>
                @endauth
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
