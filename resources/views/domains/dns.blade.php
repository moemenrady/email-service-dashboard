<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('DNS Configuration') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    DNS records required for domain <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $activeDomain }}</span>
                </p>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Active Routing</span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if ($apiError)
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ $apiError }}</span>
            </div>
        @endif

        <!-- Instructions Card -->
        <div class="p-5 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-800 text-xs text-indigo-900 dark:text-indigo-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-semibold text-sm block">How to configure your domain DNS:</strong>
                <p class="mt-1">Add the following DNS records at your domain registrar (e.g., Cloudflare, Namecheap, GoDaddy) to enable inbound and outbound email routing.</p>
            </div>
        </div>

        <!-- MX Records Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">1. Mail Exchanger (MX) Records</h3>
            <p class="text-xs text-gray-500 mb-4">Required for receiving mail on your domain.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-[11px] uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Host / Name</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Target Mail Server</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @if (!empty($dnsInfo['mx_records']))
                            @foreach ($dnsInfo['mx_records'] as $mx)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-semibold text-xs text-indigo-600">MX</td>
                                    <td class="px-4 py-3 font-mono text-xs">@ (or {{ $activeDomain }})</td>
                                    <td class="px-4 py-3 font-mono text-xs font-bold">{{ $mx['priority'] ?? 10 }}</td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $mx['hostname'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-4 py-3 font-mono font-semibold text-xs text-indigo-600">MX</td>
                                <td class="px-4 py-3 font-mono text-xs">@</td>
                                <td class="px-4 py-3 font-mono text-xs font-bold">10</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ config('mxroute.server') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SPF & TXT Records Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">2. Sender Policy Framework (SPF)</h3>
            <p class="text-xs text-gray-500 mb-4">Prevents spammers from spoofing your domain name.</p>

            <div class="p-4 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-200 dark:border-gray-700 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400">Record Type: <strong class="text-indigo-600 font-mono">TXT</strong></span>
                    <span class="text-gray-400">Host: <strong class="text-gray-700 dark:text-gray-300 font-mono">@</strong></span>
                </div>
                <div class="mt-2 font-mono text-xs bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-700 select-all text-gray-800 dark:text-gray-200">
                    {{ $dnsInfo['spf']['value'] ?? 'v=spf1 include:mxroute.com -all' }}
                </div>
            </div>
        </div>

        @if (!empty($dnsInfo['dkim']))
            <!-- DKIM Records Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700/60">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">3. DomainKeys Identified Mail (DKIM)</h3>
                <p class="text-xs text-gray-500 mb-4">Cryptographically signs outbound emails to guarantee deliverability.</p>

                <div class="p-4 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-400">Record Type: <strong class="text-indigo-600 font-mono">TXT</strong></span>
                        <span class="text-gray-400">Host / Selector: <strong class="text-gray-700 dark:text-gray-300 font-mono">{{ $dnsInfo['dkim']['name'] ?? 'x._domainkey' }}</strong></span>
                    </div>
                    <div class="mt-2 font-mono text-xs bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-700 select-all text-gray-800 dark:text-gray-200 break-all">
                        {{ $dnsInfo['dkim']['value'] ?? '' }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
