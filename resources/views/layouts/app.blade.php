<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'email-service-dashboard') }} — مؤمن تيم</title>

        <!-- Fonts: Cairo & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F6F7F5] dark:bg-[#141B17] text-gray-900 dark:text-[#E3E7E1] min-h-screen flex flex-col selection:bg-emerald-500 selection:text-white">
        <div class="flex-1">
            @include('layouts.navigation')

            <!-- Global Toast / Flash Notifications -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-5 flex items-center justify-between p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 dark:text-emerald-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-bold text-base shrink-0">
                                ✓
                            </div>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 text-xl font-bold leading-none p-1">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-5 flex items-center justify-between p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-800 dark:text-rose-200 shadow-sm" role="alert">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-500 flex items-center justify-center font-bold text-base shrink-0">
                                !
                            </div>
                            <span class="text-sm font-bold">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700 text-xl font-bold leading-none p-1">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div x-data="{ show: true }" x-show="show" class="mb-5 p-5 bg-amber-500/10 border border-amber-500/30 rounded-2xl text-amber-900 dark:text-amber-200 shadow-sm" role="alert">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-amber-500 font-bold text-lg">⚠️</span>
                                <span class="text-sm font-extrabold">يرجى تصحيح الأخطاء التالية:</span>
                            </div>
                            <button @click="show = false" class="text-amber-500 hover:text-amber-700 text-xl font-bold leading-none p-1">&times;</button>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 text-amber-800 dark:text-amber-300 pr-4">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 dark:bg-[#1F2A24]/90 backdrop-blur-md border-b border-gray-200/80 dark:border-[#374A3C] shadow-sm mb-6">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-12">
                {{ $slot }}
            </main>
        </div>

        <!-- Moemen Team Production Footer -->
        <footer class="mt-auto border-t border-gray-200 dark:border-[#374A3C] bg-white dark:bg-[#1F2A24] py-5 text-xs text-gray-500 dark:text-[#A7B7A7]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="logo-badge w-6 h-6"></div>
                    <span class="font-extrabold text-gray-800 dark:text-white">فريق مؤمن للتطوير البرمجي</span>
                    <span>•</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">email-service-dashboard • مؤمن تيم</span>
                </div>
                <div class="text-[11px] text-gray-400">
                    بنية بريد سحابية آمنة ومحمية بالكامل © {{ date('Y') }}
                </div>
            </div>
        </footer>
    </body>
</html>
