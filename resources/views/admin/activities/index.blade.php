<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-purple-600/20 border border-purple-500/30 flex items-center justify-center text-xl shadow-inner">
                    📊
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight font-heading">
                        سجل النشاطات والتحليلات — Activity Logs & Analytics
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        فريق مؤمن للتطوير البرمجي • مراقبة نشاطات العملاء، عمليات تسجيل الدخول، وتفاصيل الأجهزة والإجراءات
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.activities.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-900/30 transition transform active:scale-95" title="تصدير السجل المفلتر كملف إكسل CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>تصدير تقرير CSV (Excel)</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#1F2A24] text-white hover:bg-[#374A3C] border border-[#506055]/40 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>العودة للوحة الإدارة</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Top Analytics & KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Logins Today -->
            <div class="p-5 rounded-2xl bg-[#1F2A24] border border-[#374A3C] shadow-md relative overflow-hidden group hover:border-blue-500/50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">تسجيلات الدخول اليوم</span>
                        <div class="text-2xl font-black text-white mt-1 font-heading flex items-baseline gap-2">
                            <span>{{ number_format($todayLogins) }}</span>
                            <span class="text-xs font-normal text-blue-400 font-sans">عملية دخول</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-xl shadow-inner">
                        🔑
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-gray-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>نشاط اليوم المباشر</span>
                </div>
            </div>

            <!-- 2. Active Clients in Last 7 Days -->
            <div class="p-5 rounded-2xl bg-[#1F2A24] border border-[#374A3C] shadow-md relative overflow-hidden group hover:border-emerald-500/50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">العملاء النشطون (7 أيام)</span>
                        <div class="text-2xl font-black text-white mt-1 font-heading flex items-baseline gap-2">
                            <span>{{ number_format($activeClientsPast7Days) }}</span>
                            <span class="text-xs font-normal text-emerald-400 font-sans">عميل متفاعل</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-inner">
                        👥
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-gray-400">
                    <span>من إجمالي {{ $clients->count() }} عميل مسجل</span>
                </div>
            </div>

            <!-- 3. Total Actions Today -->
            <div class="p-5 rounded-2xl bg-[#1F2A24] border border-[#374A3C] shadow-md relative overflow-hidden group hover:border-amber-500/50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">إجمالي العمليات اليوم</span>
                        <div class="text-2xl font-black text-white mt-1 font-heading flex items-baseline gap-2">
                            <span>{{ number_format($todayActions) }}</span>
                            <span class="text-xs font-normal text-amber-400 font-sans">إجراء مسجل</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl shadow-inner">
                        ⚡
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-gray-400">
                    <span>سجلات البريد، التحويل، والتصدير</span>
                </div>
            </div>

            <!-- 4. Device Breakdown -->
            <div class="p-5 rounded-2xl bg-[#1F2A24] border border-[#374A3C] shadow-md relative overflow-hidden group hover:border-purple-500/50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">توزيع أجهزة الدخول</span>
                        <div class="text-sm font-black text-white mt-1.5 flex items-center gap-3">
                            <span class="flex items-center gap-1" title="كمبيوتر / لابتوب">
                                💻 <span class="font-mono text-emerald-400">{{ $totalDeviceKnown > 0 ? round(($desktopCount / $totalDeviceKnown) * 100) : 0 }}%</span>
                            </span>
                            <span class="flex items-center gap-1" title="هاتف ذكي">
                                📱 <span class="font-mono text-blue-400">{{ $totalDeviceKnown > 0 ? round(($mobileCount / $totalDeviceKnown) * 100) : 0 }}%</span>
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl shadow-inner">
                        🌐
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] text-gray-400">
                    <span>{{ number_format($totalLogsCount) }} إجمالي العمليات المسجلة</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="p-6 rounded-2xl bg-[#1F2A24] border border-[#374A3C] shadow-lg">
            <form method="GET" action="{{ route('admin.activities.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- 1. Search Term -->
                    <div class="lg:col-span-1">
                        <label class="block text-xs font-bold text-gray-300 mb-1">بحث نصي (Keyword)</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="بحث بالاسم، البريد، IP..." class="w-full px-3.5 py-2 rounded-xl text-xs bg-[#141B17] border border-[#374A3C] text-white placeholder-gray-500 focus:border-emerald-500 focus:ring-emerald-500">
                            @if (!empty($filters['search']))
                                <a href="{{ route('admin.activities.index', array_merge(request()->except('search'))) }}" class="absolute left-2.5 top-2.5 text-gray-400 hover:text-white text-xs" title="مسح">✕</a>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Client User Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1">تصفية بحساب العميل</label>
                        <select name="user_id" class="w-full px-3 py-2 rounded-xl text-xs bg-[#141B17] border border-[#374A3C] text-white focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">جميع العملاء والمدراء</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @selected($filters['user_id'] == $client->id)>
                                    {{ $client->name }} ({{ $client->assigned_domain }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Action Type Filter -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1">نوع العملية (Action)</label>
                        <select name="action_type" class="w-full px-3 py-2 rounded-xl text-xs bg-[#141B17] border border-[#374A3C] text-white focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach ($actionTypes as $val => $lbl)
                                <option value="{{ $val }}" @selected($filters['action_type'] === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Date From -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1">من تاريخ (From)</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="w-full px-3 py-2 rounded-xl text-xs bg-[#141B17] border border-[#374A3C] text-white focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <!-- 5. Date To -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1">إلى تاريخ (To)</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="w-full px-3 py-2 rounded-xl text-xs bg-[#141B17] border border-[#374A3C] text-white focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <!-- Submit / Reset Buttons -->
                <div class="flex items-center justify-between pt-2 border-t border-[#374A3C]/60 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>تطبيق الفلترة</span>
                        </button>

                        <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-400 hover:text-white bg-[#141B17] border border-[#374A3C] transition">
                            إعادة ضبط (Reset)
                        </a>
                    </div>

                    <div class="text-xs text-gray-400">
                        عرض <strong class="text-emerald-400 font-mono">{{ $activities->total() }}</strong> سجل مطابق
                    </div>
                </div>
            </form>
        </div>

        <!-- Activity Timeline Table -->
        <div class="rounded-2xl bg-[#1F2A24] border border-[#374A3C] shadow-xl overflow-hidden">
            <div class="p-5 border-b border-[#374A3C] flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📋</span>
                    <h3 class="text-base font-extrabold text-white">تفاصيل سجل النشاطات والإجراءات</h3>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">يتم تسجيل كل إجراء بالوقت الفعلي مع عنوان الـ IP والبيئة المطبقة</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-[#141B17] border-b border-[#374A3C] text-gray-400 font-semibold uppercase tracking-wider">
                            <th class="px-4 py-3.5">التاريخ والوقت</th>
                            <th class="px-4 py-3.5">العميل / الحساب</th>
                            <th class="px-4 py-3.5">نوع العملية</th>
                            <th class="px-4 py-3.5">تفاصيل الإجراء</th>
                            <th class="px-4 py-3.5">الجهاز والبيئة</th>
                            <th class="px-4 py-3.5">عنوان IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#374A3C]/40 text-gray-200">
                        @forelse ($activities as $log)
                            <tr class="hover:bg-[#2B3B32]/40 transition group">
                                <!-- 1. Date & Time -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="font-bold text-white text-xs">
                                        {{ $log->created_at->diffForHumans() }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5" dir="ltr">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </div>
                                </td>

                                <!-- 2. User / Client -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-[#141B17] border border-[#374A3C] flex items-center justify-center font-bold text-xs uppercase text-emerald-400 shrink-0">
                                            {{ substr($log->user_name ?: ($log->user_email ?: 'U'), 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-xs">
                                                {{ $log->user_name ?: ($log->user?->name ?: 'مستخدم غير معروف') }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 font-mono">
                                                {{ $log->user_email ?: '—' }}
                                            </div>
                                            @if ($log->assigned_domain)
                                                <div class="text-[9px] text-emerald-400/90 font-mono mt-0.5">
                                                    {{ $log->assigned_domain }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- 3. Action Type Badge -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $log->action_badge_class }}">
                                        <span>{{ $log->action_type_arabic }}</span>
                                    </span>
                                </td>

                                <!-- 4. Action Title & Details -->
                                <td class="px-4 py-3.5 max-w-md">
                                    <div class="font-bold text-gray-100 text-xs leading-relaxed">
                                        {{ $log->action_title }}
                                    </div>

                                    @if (!empty($log->details) && is_array($log->details))
                                        <div class="flex items-center gap-1.5 flex-wrap mt-1">
                                            @foreach ($log->details as $k => $v)
                                                @if (is_scalar($v) && !empty($v))
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#141B17] border border-[#374A3C] text-[10px] font-mono text-gray-300">
                                                        <span class="text-gray-500 font-sans">{{ $k }}:</span>
                                                        <span class="text-emerald-400">{{ (string)$v }}</span>
                                                    </span>
                                                @elseif (is_array($v))
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#141B17] border border-[#374A3C] text-[10px] font-mono text-gray-300">
                                                        <span class="text-gray-500 font-sans">{{ $k }}:</span>
                                                        <span class="text-emerald-400">{{ implode(', ', $v) }}</span>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <!-- 5. Device & Platform -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base" title="{{ $log->device_type }}">{{ $log->device_icon }}</span>
                                        <div>
                                            <div class="font-bold text-white text-xs">
                                                {{ $log->platform ?: 'غير محدد' }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 font-mono">
                                                {{ $log->browser ?: 'Browser' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- 6. IP Address -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-md bg-[#141B17] border border-[#374A3C] font-mono text-[11px] text-gray-300">
                                        {{ $log->ip_address ?: '—' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <span class="text-3xl">🔍</span>
                                        <p class="font-bold text-sm text-gray-300">لا توجد سجلات مطابقة لمعايير البحث الحالية.</p>
                                        <p class="text-xs text-gray-500">جرّب تعديل الفلاتر أو إعادة ضبط البحث لعرض كامل السجلات.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if ($activities->hasPages())
                <div class="p-4 border-t border-[#374A3C] bg-[#141B17]">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
