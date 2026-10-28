<nav x-data="{ open: false }" class="bg-[#1F2A24] border-b border-[#374A3C] shadow-md text-white sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Brand / Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('emails.index') }}" class="flex items-center gap-3 group">
                        <div class="logo-badge w-9 h-9"></div>
                        <div class="flex flex-col">
                            <span class="text-sm font-extrabold tracking-tight text-white group-hover:text-emerald-400 transition">
                                MOEMEN<span class="text-emerald-400">TEAM</span>
                            </span>
                            <span class="text-[9px] font-semibold text-[#A7B7A7] tracking-widest uppercase">
                                Managed Email Panel
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:gap-2 sm:ms-4">
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-300 hover:text-white hover:bg-[#374A3C]/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>لوحة الإدارة (Admin)</span>
                        </a>

                        <a href="{{ route('admin.activities.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.activities.*') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-300 hover:text-white hover:bg-[#374A3C]/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span>سجل النشاطات والتحليلات</span>
                        </a>
                    @endif

                    <!-- Email Accounts link for all users -->
                    <a href="{{ route('emails.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('emails.index') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-300 hover:text-white hover:bg-[#374A3C]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        <span>حسابات البريد (Email Accounts)</span>
                    </a>

                    <!-- Forwarders for all users -->
                    <a href="{{ route('forwarders.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('forwarders.*') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-300 hover:text-white hover:bg-[#374A3C]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        <span>إعادة التوجيه (Forwarders)</span>
                    </a>

                    <!-- Setup Guide -->
                    <a href="{{ route('emails.guide') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('emails.guide') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-300 hover:text-white hover:bg-[#374A3C]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>دليل الاستخدام (Guide)</span>
                    </a>

                    <!-- DNS settings -->
                    <a href="{{ route('domains.dns') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('domains.dns') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-300 hover:text-white hover:bg-[#374A3C]/60' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>إعدادات DNS</span>
                    </a>
                </div>
            </div>

            <!-- Right Side: Domain Badge & Profile Menu -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <!-- Managed Domain Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#141B17] text-[#A7B7A7] border border-[#374A3C]">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-gray-400">الدومين:</span>
                    <span class="font-mono text-emerald-400">{{ session('active_domain', Auth::user()->getActiveDomain()) }}</span>
                </div>

                <!-- Webmail Shortcut Button -->
                <a href="https://mail.albrolosy.com/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-[#374A3C] text-emerald-300 hover:bg-[#506055] border border-emerald-500/30 shadow-sm transition" title="فتح واجهة بريد الويب">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Webmail</span>
                </a>

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-200 bg-[#141B17] hover:bg-[#2B3B32] border border-[#374A3C] transition">
                            <div class="w-6 h-6 rounded-lg bg-emerald-600/30 text-emerald-400 flex items-center justify-center font-bold text-[11px] uppercase border border-emerald-500/30">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-[#374A3C] text-right">
                            <span class="block text-xs font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                            <span class="block text-[11px] text-gray-400 font-mono truncate mt-0.5">{{ Auth::user()->email }}</span>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-[10px] text-gray-400">الدومين: {{ Auth::user()->getActiveDomain() }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ Auth::user()->isAdmin() ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                    {{ Auth::user()->isAdmin() ? 'مدير النظام' : 'عميل مخصص' }}
                                </span>
                            </div>
                        </div>

                        @if (Auth::user()->isAdmin())
                            <x-dropdown-link :href="route('admin.dashboard')" class="text-right text-xs">
                                🛡️ لوحة الإدارة (Admin Console)
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.activities.index')" class="text-right text-xs">
                                📊 سجل النشاطات والتحليلات (Activity Logs)
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('emails.index')" class="text-right text-xs">
                            ✉️ حسابات البريد الإلكتروني
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('forwarders.index')" class="text-right text-xs">
                            🔄 إعادة التوجيه (Forwarders)
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('emails.guide')" class="text-right text-xs">
                            📖 دليل الاستخدام والربط
                        </x-dropdown-link>

                        <x-dropdown-link href="https://mail.albrolosy.com/" target="_blank" class="text-right text-xs text-emerald-400">
                            🌐 فتح بريد الويب (Webmail)
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')" class="text-right text-xs">
                            ⚙️ إعدادات الحساب
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-right text-xs text-rose-500 hover:text-rose-600">
                                🚪 تسجيل الخروج
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-white hover:bg-[#374A3C] focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-[#374A3C] bg-[#141B17]">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-gray-200 hover:bg-[#2B3B32]">
                    🛡️ لوحة الإدارة (Admin)
                </a>
                <a href="{{ route('admin.activities.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-gray-200 hover:bg-[#2B3B32]">
                    📊 سجل النشاطات والتحليلات
                </a>
            @endif
            <a href="{{ route('emails.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-gray-200 hover:bg-[#2B3B32]">
                ✉️ حسابات البريد الإلكتروني
            </a>
            <a href="{{ route('forwarders.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-gray-200 hover:bg-[#2B3B32]">
                🔄 إعادة التوجيه (Forwarders)
            </a>
            <a href="{{ route('emails.guide') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-gray-200 hover:bg-[#2B3B32]">
                📖 دليل الاستخدام والربط
            </a>
            <a href="{{ route('domains.dns') }}" class="block px-3 py-2 rounded-xl text-sm font-bold text-gray-200 hover:bg-[#2B3B32]">
                🌐 إعدادات DNS
            </a>
            <a href="https://mail.albrolosy.com/" target="_blank" class="block px-3 py-2 rounded-xl text-sm font-bold text-emerald-400 hover:bg-[#2B3B32]">
                🌐 فتح بريد الويب (Webmail)
            </a>
        </div>

        <div class="pt-4 pb-3 border-t border-[#374A3C] px-4">
            <div class="text-xs font-bold text-white">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400 font-mono">{{ Auth::user()->email }}</div>
            <div class="mt-2 text-xs text-emerald-400 font-semibold">الدومين: {{ session('active_domain', Auth::user()->getActiveDomain()) }}</div>

            <div class="mt-4 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-right px-3 py-2 rounded-xl text-xs font-bold text-rose-400 hover:bg-[#2B3B32]">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
