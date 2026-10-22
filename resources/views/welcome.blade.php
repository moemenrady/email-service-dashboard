<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Moemen Workspace - فريق مؤمن للتطوير البرمجي</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-b from-gray-900 via-gray-900 to-indigo-950 text-white min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">
        <!-- Header -->
        <header class="max-w-7xl mx-auto w-full px-6 py-6 flex items-center justify-between">
            <x-application-logo class="h-9 w-auto" />

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-500/20 transition">
                        Open Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-white backdrop-blur border border-white/10 transition">
                        Sign In
                    </a>
                @endauth
            </div>
        </header>

        <!-- Hero Section -->
        <main class="max-w-4xl mx-auto px-6 py-16 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 mb-6 backdrop-blur">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>فريق مؤمن للتطوير البرمجي • High-Security Proxy Architecture</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-tight sm:leading-none">
                Enterprise Email Management, <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-cyan-400 to-emerald-400">
                    Completely White-Labeled.
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-gray-300 max-w-2xl mx-auto leading-relaxed">
                A hardened, isolated email management proxy designed for Moemen Team clients. Control mailboxes, quotas, routing forwarders, and DNS security without exposing underlying infrastructure.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-xl shadow-indigo-600/30 transition">
                        Enter Workspace Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-xl shadow-indigo-600/30 transition">
                        Client Login &rarr;
                    </a>
                @endauth
            </div>

            <!-- Security Trust Pillars -->
            <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Strict Backend Proxy</h3>
                    <p class="text-xs text-gray-400 mt-1">Zero frontend exposure. All API credentials and endpoints remain encrypted on the server.</p>
                </div>

                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Adaptive Rate Guard</h3>
                    <p class="text-xs text-gray-400 mt-1">Automatic 429 interception with retry-after header parsing and asynchronous queue protection.</p>
                </div>

                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-sm mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Tenant Domain Isolation</h3>
                    <p class="text-xs text-gray-400 mt-1">Strict domain authorization checks prevent any unauthorized access across domains.</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="max-w-7xl mx-auto w-full px-6 py-6 text-center text-xs text-gray-500 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>Moemen Workspace - Managed Email Panel</span>
            <span>فريق مؤمن للتطوير البرمجي © {{ date('Y') }}</span>
        </footer>
    </body>
</html>
