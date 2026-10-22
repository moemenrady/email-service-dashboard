<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center text-xl shadow-inner">
                    🛡️
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight font-heading">
                        لوحة تحكم الإدارة — Admin Console
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        فريق مؤمن للتطوير البرمجي • التحكم بحسابات العملاء، الدومينات وخادم البريد المدار
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('emails.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#1F2A24] text-white hover:bg-[#374A3C] border border-[#506055]/40 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    <span>استعراض صناديق البريد ({{ $activeDomain }})</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{
        isCreateClientOpen: false,
        isEditClientOpen: false,
        isCreateDomainOpen: false,
        editUser: { id: null, name: '', email: '', assigned_domain: '' },
        openEditModal(user) {
            this.editUser = { ...user };
            this.isEditClientOpen = true;
        }
    }">

        @if ($apiError)
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span><strong>تنبيه مزود الخدمة:</strong> {{ $apiError }}</span>
            </div>
        @endif

        <!-- Quick Metrics Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Total Clients -->
            <div class="bg-white dark:bg-[#1F2A24]/90 p-5 rounded-2xl border border-gray-100 dark:border-[#374A3C] shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-[#A7B7A7]">إجمالي العملاء</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg">
                        👥
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white">{{ $clientsCount }}</span>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">حسابات نشطة</span>
                </div>
                <div class="mt-2 text-[11px] text-gray-400">عملاء مخصص لهم دومينات خاصة</div>
            </div>

            <!-- Metric 2: Live Domains -->
            <div class="bg-white dark:bg-[#1F2A24]/90 p-5 rounded-2xl border border-gray-100 dark:border-[#374A3C] shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-[#A7B7A7]">الدومينات المدارة</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg">
                        🌐
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white">{{ $domainsCount }}</span>
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Live Domains</span>
                </div>
                <div class="mt-2 text-[11px] text-gray-400">مربوطة مباشرة بسيرفر البريد السحابي</div>
            </div>

            <!-- Metric 3: Total Mailboxes -->
            <div class="bg-white dark:bg-[#1F2A24]/90 p-5 rounded-2xl border border-gray-100 dark:border-[#374A3C] shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-[#A7B7A7]">صناديق البريد</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-lg">
                        ✉️
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white">{{ $mailboxesCount }}</span>
                    <span class="text-xs text-purple-600 dark:text-purple-400 font-semibold">صندوق نشط</span>
                </div>
                <div class="mt-2 text-[11px] text-gray-400">على الدومين الحالي ({{ $activeDomain }})</div>
            </div>

            <!-- Metric 4: API Status & Proxy -->
            <div class="bg-white dark:bg-[#1F2A24]/90 p-5 rounded-2xl border border-gray-100 dark:border-[#374A3C] shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-[#A7B7A7]">حالة خادم البريد</span>
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">متصل ومحمي بنجاح</span>
                </div>
                <div class="mt-2 text-[11px] text-gray-400 flex items-center justify-between">
                    <span>معدل الطلبات المتاح:</span>
                    <span class="font-mono text-emerald-500 font-semibold">{{ $rateLimitStatus['remaining'] ?? '200' }} / 200 req</span>
                </div>
            </div>
        </div>

        <!-- ==================== Section 1: Client Management ==================== -->
        <div class="bg-white dark:bg-[#1F2A24] rounded-2xl shadow-sm border border-gray-200 dark:border-[#374A3C] overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-[#374A3C] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>إدارة العملاء وربط الدومينات</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 font-semibold">
                            {{ $clientsCount }} عميل
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        يمكنك إضافة عميل جديد، تخصيص دومين محدد له ليتمكن من إدارة حساباته البريدية دون رؤية بيانات النظام الخلفي
                    </p>
                </div>

                <button @click="isCreateClientOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:from-emerald-500 hover:to-emerald-600 shadow-md shadow-emerald-900/20 transition transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>إضافة عميل جديد</span>
                </button>
            </div>

            <!-- Clients Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/70 dark:bg-[#141B17] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-[#A7B7A7] border-b border-gray-100 dark:border-[#374A3C]">
                        <tr>
                            <th scope="col" class="px-6 py-4">العميل / الحساب</th>
                            <th scope="col" class="px-6 py-4">الدومين المخصص</th>
                            <th scope="col" class="px-6 py-4">الصلاحية</th>
                            <th scope="col" class="px-6 py-4">تاريخ الإنشاء</th>
                            <th scope="col" class="px-6 py-4 text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-[#374A3C]">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs uppercase border border-emerald-500/20">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white block">{{ $user->name }}</span>
                                            <span class="text-xs text-gray-400 font-mono">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        {{ $user->assigned_domain ?: 'غير مخصص' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->isAdmin())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-300 border border-purple-500/20">
                                            🛡️ مدير النظام (Admin)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                            👤 عميل (Client)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    {{ $user->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal({{ json_encode($user) }})" class="p-2 rounded-lg text-gray-400 hover:text-emerald-600 hover:bg-emerald-500/10 dark:hover:bg-emerald-500/20 transition" title="تعديل بيانات العميل">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        @if ($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.clients.destroy', $user) }}" onsubmit="return confirm('هل أنت متأكد من حذف حساب العميل [{{ $user->name }}]؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-500/10 dark:hover:bg-rose-500/20 transition" title="حذف العميل">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                                    لا يوجد عملاء مسجلين حالياً.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== Section 2: Domains on Mail Server ==================== -->
        <div class="bg-white dark:bg-[#1F2A24] rounded-2xl shadow-sm border border-gray-200 dark:border-[#374A3C] overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-[#374A3C] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>الدومينات النشطة على خادم البريد (Live Domains)</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 font-semibold">
                            {{ count($domains) }} دومين
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        قائمة الدومينات المسجلة على الخادم السحابي. يمكنك إضافة دومين جديد أو إدارة حساباته البريدية مباشرة
                    </p>
                </div>

                <button @click="isCreateDomainOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#374A3C] text-white hover:bg-[#506055] border border-[#506055]/50 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>إضافة دومين للخادم</span>
                </button>
            </div>

            <!-- Domains Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/70 dark:bg-[#141B17] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-[#A7B7A7] border-b border-gray-100 dark:border-[#374A3C]">
                        <tr>
                            <th scope="col" class="px-6 py-4">اسم الدومين</th>
                            <th scope="col" class="px-6 py-4">استضافة البريد</th>
                            <th scope="col" class="px-6 py-4">العملاء المرتبطين</th>
                            <th scope="col" class="px-6 py-4 text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-[#374A3C]">
                        @forelse ($domains as $domainItem)
                            @php
                                $dName = is_array($domainItem) ? ($domainItem['domain'] ?? '') : $domainItem;
                                $assignedClients = $users->where('assigned_domain', $dName);
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs border border-blue-500/20">
                                            🌐
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white font-mono block">{{ $dName }}</span>
                                            <span class="text-[11px] text-gray-400">DNS & Mail Proxy Ready</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active & Hosted
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($assignedClients->count() > 0)
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @foreach ($assignedClients as $ac)
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 dark:bg-[#374A3C] text-gray-700 dark:text-[#A7B7A7]">
                                                    {{ $ac->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">لا يوجد عملاء مربوطين بهذا الدومين</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Switch to Domain & Manage Emails -->
                                        <form method="POST" action="{{ route('admin.domains.switch') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="domain" value="{{ $dName }}">
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-sm transition" title="فتح وإدارة صناديق البريد لهذا الدومين">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                <span>صناديق البريد</span>
                                            </button>
                                        </form>

                                        <!-- DNS Info -->
                                        <a href="{{ route('domains.dns', ['domain' => $dName]) }}" class="p-2 rounded-lg text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 transition" title="إعدادات سجلات DNS">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>

                                        <!-- Delete Domain -->
                                        <form method="POST" action="{{ route('admin.domains.destroy', $dName) }}" onsubmit="return confirm('تحذير: هل أنت متأكد من حذف الدومين [{{ $dName }}] نهائياً من خادم البريد؟ سيتم حذف جميع الحسابات البريدية التابعة له!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-500/10 transition" title="حذف الدومين">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                                    لا توجد دومينات مضافة بعد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== Modal: Create Client ==================== -->
        <div x-show="isCreateClientOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div @click="isCreateClientOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-right bg-white dark:bg-[#1F2A24] rounded-2xl shadow-2xl border border-gray-200 dark:border-[#374A3C] relative z-10 transform transition-all">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-[#374A3C]">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">إضافة حساب عميل جديد</h4>
                        <button @click="isCreateClientOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xl">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('admin.clients.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">اسم العميل / الشركة</label>
                            <input type="text" name="name" required placeholder="مثال: شركة البرلسي للتجارة" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">البريد الإلكتروني لتسجيل الدخول</label>
                            <input type="email" name="email" required placeholder="client@albrolosy.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">كلمة المرور للعميل</label>
                            <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500">
                            <span class="text-[11px] text-gray-400 mt-1 block">يجب ألا تقل عن 8 خانات وتحتوي على أحرف وأرقام.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">تخصيص الدومين للعميل (Assigned Domain)</label>
                            <select name="assigned_domain" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono">
                                <option value="" disabled selected>-- اختر الدومين من القائمة المتاحة --</option>
                                @foreach ($domains as $dItem)
                                    @php $domainVal = is_array($dItem) ? ($dItem['domain'] ?? '') : $dItem; @endphp
                                    <option value="{{ $domainVal }}">{{ $domainVal }}</option>
                                @endforeach
                            </select>
                            <span class="text-[11px] text-gray-400 mt-1 block">العميل سيرى ويتعامل فقط مع هذا الدومين ولن يرى أي دومينات أخرى.</span>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-[#374A3C]">
                            <button type="button" @click="isCreateClientOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md transition">حفظ وإنشاء العميل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ==================== Modal: Edit Client ==================== -->
        <div x-show="isEditClientOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div @click="isEditClientOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-right bg-white dark:bg-[#1F2A24] rounded-2xl shadow-2xl border border-gray-200 dark:border-[#374A3C] relative z-10 transform transition-all">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-[#374A3C]">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">تعديل بيانات العميل</h4>
                        <button @click="isEditClientOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xl">&times;</button>
                    </div>

                    <form method="POST" :action="'/admin/clients/' + editUser.id" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">اسم العميل</label>
                            <input type="text" name="name" x-model="editUser.name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" x-model="editUser.email" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">كلمة المرور الجديدة (اختياري)</label>
                            <input type="password" name="password" placeholder="اتركها فارغة إذا لم ترغب بتغييرها" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">الدومين المخصص</label>
                            <select name="assigned_domain" x-model="editUser.assigned_domain" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono">
                                @foreach ($domains as $dItem)
                                    @php $domainVal = is_array($dItem) ? ($dItem['domain'] ?? '') : $dItem; @endphp
                                    <option value="{{ $domainVal }}">{{ $domainVal }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-[#374A3C]">
                            <button type="button" @click="isEditClientOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md transition">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ==================== Modal: Create Domain ==================== -->
        <div x-show="isCreateDomainOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center">
                <div @click="isCreateDomainOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

                <div class="inline-block w-full max-w-lg p-6 my-8 text-right bg-white dark:bg-[#1F2A24] rounded-2xl shadow-2xl border border-gray-200 dark:border-[#374A3C] relative z-10 transform transition-all">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-[#374A3C]">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">إضافة دومين جديد إلى خادم البريد</h4>
                        <button @click="isCreateDomainOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xl">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('admin.domains.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">اسم الدومين</label>
                            <input type="text" name="domain" required placeholder="example.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono">
                            <span class="text-[11px] text-gray-400 mt-1 block">أدخل اسم النطاق فقط بدون http:// أو www (مثال: newclient.com).</span>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-[#374A3C]">
                            <button type="button" @click="isCreateDomainOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md transition">إضافة الدومين الآن</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
