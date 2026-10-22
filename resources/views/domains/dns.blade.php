<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-xl shadow-inner">
                    🌐
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight font-heading">
                        إعدادات وسجلات DNS — Domain Routing
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        سجلات الربط والتوجيه السحابي للدومين: <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $activeDomain }}</span>
                    </p>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>توجيه البريد مفعل ومحمي</span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if ($apiError)
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span><strong>تنبيه:</strong> {{ $apiError }}</span>
            </div>
        @endif

        <!-- Instructions Card -->
        <div class="p-5 rounded-2xl bg-[#1F2A24] border border-[#374A3C] text-xs text-gray-300 flex items-start gap-3 shadow-sm">
            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 font-bold">
                ℹ️
            </div>
            <div>
                <strong class="font-bold text-sm text-white block">كيفية ربط وتوجيه الدومين (DNS Setup):</strong>
                <p class="mt-1 leading-relaxed text-gray-300">
                    قم بإضافة السجلات الموضحة أدناه داخل لوحة إدارة الدومين الخاص بك (مثل Cloudflare، Namecheap، GoDaddy) لضمان استقبال وإرسال الرسائل بشكل فوري وتفادي وصولها لصندوق الـ Spam.
                </p>
            </div>
        </div>

        <!-- 1. MX Records -->
        <div class="bg-white dark:bg-[#1F2A24] shadow-sm rounded-2xl p-6 border border-gray-200 dark:border-[#374A3C]">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                <span>1. سجلات توجيه البريد — Mail Exchanger (MX) Records</span>
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">ضرورية لاستقبال رسائل البريد الإلكتروني على الدومين.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/70 dark:bg-[#141B17] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-[#A7B7A7] border-b border-gray-100 dark:border-[#374A3C]">
                        <tr>
                            <th class="px-5 py-3.5">النوع (Type)</th>
                            <th class="px-5 py-3.5">الاسم / المضيف (Host)</th>
                            <th class="px-5 py-3.5">الأولوية (Priority)</th>
                            <th class="px-5 py-3.5">الهدف (Target / Mail Server)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-[#374A3C]">
                        @if (!empty($dnsInfo['mx_records']))
                            @foreach ($dnsInfo['mx_records'] as $mx)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40">
                                    <td class="px-5 py-3.5 font-mono font-bold text-xs text-blue-500">MX</td>
                                    <td class="px-5 py-3.5 font-mono text-xs">@</td>
                                    <td class="px-5 py-3.5 font-mono text-xs font-bold text-emerald-500">{{ $mx['priority'] ?? 10 }}</td>
                                    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-gray-900 dark:text-white">{{ $mx['hostname'] ?? 'mail.' . $activeDomain }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40">
                                <td class="px-5 py-3.5 font-mono font-bold text-xs text-blue-500">MX</td>
                                <td class="px-5 py-3.5 font-mono text-xs">@</td>
                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-emerald-500">10</td>
                                <td class="px-5 py-3.5 font-mono text-xs font-semibold text-gray-900 dark:text-white">fusion.mxrouting.net</td>
                            </tr>
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40">
                                <td class="px-5 py-3.5 font-mono font-bold text-xs text-blue-500">MX</td>
                                <td class="px-5 py-3.5 font-mono text-xs">@</td>
                                <td class="px-5 py-3.5 font-mono text-xs font-bold text-emerald-500">20</td>
                                <td class="px-5 py-3.5 font-mono text-xs font-semibold text-gray-900 dark:text-white">fusion-relay.mxrouting.net</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. SPF Record -->
        <div class="bg-white dark:bg-[#1F2A24] shadow-sm rounded-2xl p-6 border border-gray-200 dark:border-[#374A3C]">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">2. سجل التحقق من مصدر البريد (SPF Record)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">يمنع تزييف الرسائل باسم دومينك ويوثق صلاحية إرسال الخادم السحابي.</p>

            <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/60 dark:border-[#374A3C] font-mono text-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="space-y-1">
                    <span class="text-[11px] text-gray-400 font-sans block">نوع السجل: TXT • المضيف: @</span>
                    <span class="text-emerald-500 font-bold block" dir="ltr">v=spf1 include:mxroute.com -all</span>
                </div>
                <button onclick="navigator.clipboard.writeText('v=spf1 include:mxroute.com -all'); alert('تم نسخ سجل SPF بنجاح!');" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#374A3C] text-white hover:bg-[#506055] transition shrink-0">
                    نسخ القيمة
                </button>
            </div>
        </div>

        <!-- 3. DKIM Record -->
        @if (!empty($dnsInfo['dkim']))
            <div class="bg-white dark:bg-[#1F2A24] shadow-sm rounded-2xl p-6 border border-gray-200 dark:border-[#374A3C]">
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">3. توقيع التشفير الرقمي (DKIM Record)</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">يضمن سلامة الرسائل الصادرة ويوثق هوية المرسل.</p>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/60 dark:border-[#374A3C] font-mono text-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="space-y-1">
                        <span class="text-[11px] text-gray-400 font-sans block">النوع: TXT • المضيف (Host): <strong class="text-white">{{ $dnsInfo['dkim']['name'] ?? 'x._domainkey' }}</strong></span>
                        <span class="text-emerald-500 font-bold block truncate max-w-xl" dir="ltr">{{ $dnsInfo['dkim']['value'] ?? '' }}</span>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ $dnsInfo['dkim']['value'] ?? '' }}'); alert('تم نسخ سجل DKIM بنجاح!');" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#374A3C] text-white hover:bg-[#506055] transition shrink-0">
                        نسخ القيمة
                    </button>
                </div>
            </div>
        @endif

        <!-- 4. Webmail CNAME -->
        <div class="bg-white dark:bg-[#1F2A24] shadow-sm rounded-2xl p-6 border border-gray-200 dark:border-[#374A3C]">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">3. رابط بريد الويب المخصص (Webmail CNAME)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">لفتح بريد الويب عبر رابط خاص بدومينك مباشرة مثل https://webmail.{{ $activeDomain }}.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/70 dark:bg-[#141B17] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-[#A7B7A7] border-b border-gray-100 dark:border-[#374A3C]">
                        <tr>
                            <th class="px-5 py-3.5">النوع</th>
                            <th class="px-5 py-3.5">المضيف (Host / Subdomain)</th>
                            <th class="px-5 py-3.5">القيمة / الهدف (Target)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-[#374A3C]">
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40">
                            <td class="px-5 py-3.5 font-mono font-bold text-xs text-purple-500">CNAME</td>
                            <td class="px-5 py-3.5 font-mono text-xs font-bold text-gray-900 dark:text-white">webmail</td>
                            <td class="px-5 py-3.5 font-mono text-xs text-emerald-500 font-bold">fusion.mxrouting.net</td>
                        </tr>
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40">
                            <td class="px-5 py-3.5 font-mono font-bold text-xs text-purple-500">CNAME</td>
                            <td class="px-5 py-3.5 font-mono text-xs font-bold text-gray-900 dark:text-white">mail</td>
                            <td class="px-5 py-3.5 font-mono text-xs text-emerald-500 font-bold">fusion.mxrouting.net</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
