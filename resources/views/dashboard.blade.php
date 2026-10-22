<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Email Dashboard') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Managed email infrastructure for <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $activeDomain }}</span>
                </p>
            </div>

            @if(count($availableDomains) > 1)
                <form action="{{ route('domains.switch') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <label for="domain_select" class="text-xs font-medium text-gray-500">Switch Domain:</label>
                    <select id="domain_select" name="domain" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($availableDomains as $d)
                            <option value="{{ $d }}" {{ $d === $activeDomain ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if ($errorMessage)
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <!-- Quick Summary Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Email Accounts -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Mailboxes</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalEmails }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/70 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between text-xs">
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Domain Active</span>
                    <a href="{{ route('emails.index') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Manage &rarr;</a>
                </div>
            </div>

            <!-- Forwarders -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Forwarders</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalForwarders }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-950/70 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between text-xs">
                    <span class="text-gray-500">Aliases & Routing</span>
                    <a href="{{ route('forwarders.index') }}" class="font-semibold text-cyan-600 dark:text-cyan-400 hover:underline">Manage &rarr;</a>
                </div>
            </div>

            <!-- Disk Quota Usage -->
            @php
                $usedBytes = $quota['total_used'] ?? 0;
                $limitBytes = $quota['total_limit'] ?? 0;
                $usedMb = round($usedBytes / (1024 * 1024), 1);
                $limitMb = $limitBytes > 0 ? round($limitBytes / (1024 * 1024), 1) : null;
                $percent = $quota['percent_used'] ?? ($limitMb ? min(100, round(($usedMb / $limitMb) * 100, 1)) : 0);
            @endphp
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Disk Quota</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">
                            {{ $usedMb }} <span class="text-xs font-medium text-gray-500">MB</span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-950/70 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>{{ $percent }}% used</span>
                        <span>{{ $limitMb ? $limitMb . ' MB max' : 'Unlimited' }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" style="width: {{ min(100, $percent) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Security & Gateway Health -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-5 border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Security Proxy</p>
                        <h3 class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1.5">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            Secure
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 flex items-center justify-between text-xs">
                    <span class="text-gray-500">Isolation: Multi-Tenant</span>
                    <a href="{{ route('domains.dns') }}" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">DNS Status &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Domain Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Active Mailboxes Preview Card -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h4 class="font-bold text-lg text-gray-900 dark:text-white">Active Mailboxes ({{ count($emails) }})</h4>
                        <p class="text-xs text-gray-500">List of primary addresses on @ {{ $activeDomain }}</p>
                    </div>
                    <a href="{{ route('emails.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Email
                    </a>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700/60 mt-3">
                    @forelse(array_slice($emails, 0, 5) as $email)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300 text-xs">
                                    {{ strtoupper(substr($email['username'] ?? 'M', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $email['email'] ?? ($email['username'] . '@' . $activeDomain) }}</p>
                                    <p class="text-xs text-gray-500">Quota: {{ $email['quota'] ?? 1024 }} MB • Daily limit: {{ $email['limit'] ?? 9600 }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ !empty($email['suspended']) ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                {{ !empty($email['suspended']) ? 'Suspended' : 'Active' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-500 text-sm">
                            <p>No email mailboxes found on this domain yet.</p>
                            <a href="{{ route('emails.index') }}" class="mt-2 inline-block font-semibold text-indigo-600 hover:underline">Create your first email account</a>
                        </div>
                    @endforelse
                </div>

                @if(count($emails) > 5)
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-center">
                        <a href="{{ route('emails.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                            View all {{ count($emails) }} mailboxes &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <!-- DNS & Security Panel -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h4 class="font-bold text-base text-gray-900 dark:text-white">Domain Connection</h4>
                    </div>

                    <div class="mt-4 space-y-3 text-xs">
                        <div class="p-3 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-gray-400 font-medium">Domain:</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $activeDomain }}</span>
                        </div>

                        <div class="p-3 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-gray-400 font-medium">SPF Record Status:</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">Configured & Protected</span>
                        </div>

                        <div class="p-3 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="block text-gray-400 font-medium">Anti-Spam Filtering:</span>
                            <span class="font-semibold text-indigo-600 dark:text-indigo-400">AI Spam Filter Active</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('domains.dns') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        View Full DNS Records
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
