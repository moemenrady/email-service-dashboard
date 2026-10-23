<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-xl shadow-inner">
                    ✉️
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight font-heading">
                        إدارة حسابات البريد الإلكتروني — Email Accounts
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        صناديق البريد النشطة والمساحات التخزينية للدومين: <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $activeDomain }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <!-- User Setup Guide Link -->
                <a href="{{ route('emails.guide') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-[#1F2A24] text-gray-200 hover:text-white hover:bg-[#374A3C] border border-[#506055]/40 shadow-sm transition">
                    <span>📖 دليل الاستخدام والربط</span>
                </a>

                <!-- Export All to PDF -->
                <a href="{{ route('emails.export-all-pdf') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-[#1F2A24] text-emerald-300 hover:text-white hover:bg-[#374A3C] border border-emerald-500/30 shadow-sm transition" title="تصدير كشف كامل بجميع الحسابات PDF">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>تصدير الحسابات (PDF)</span>
                </a>

                <!-- Direct Webmail Button -->
                <a href="https://mail.albrolosy.com/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#1F2A24] text-white hover:bg-[#374A3C] border border-[#506055]/40 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>بريد الويب (Webmail)</span>
                </a>

                <!-- Create Email Button -->
                <button @click="$dispatch('open-modal', 'create-email-modal')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:from-emerald-500 hover:to-emerald-600 shadow-md shadow-emerald-900/20 transition transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>إنشاء بريد جديد</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{
        editUser: null,
        editUsername: '',
        editQuota: 1024,
        editLimit: 9600,
        deleteUser: null,
        copyToClipboard(text, msg = 'تم النسخ بنجاح!') {
            navigator.clipboard.writeText(text).then(() => {
                alert(msg);
            });
        },
        copyEmployeeShare(username, password) {
            const domain = '{{ $activeDomain }}';
            const email = username + '@' + domain;
            const passStr = password ? password : '(كلمة المرور المحددة عند الإنشاء)';
            const text = `✨ بيانات الحساب البريدي للموظف - {{ config('app.name', 'email-service-dashboard') }} ✨\n\n` +
                `مرحباً بك، إليك بيانات حسابك البريدي الرسمي:\n` +
                `📧 البريد الإلكتروني: ${email}\n` +
                `👤 اسم المستخدم للدخول: ${email}\n` +
                `🔑 كلمة المرور: ${passStr}\n\n` +
                `🌐 رابط بريد الويب المباشر (Webmail):\nhttps://mail.albrolosy.com/\n\n` +
                `📱 إعدادات الربط على الهواتف (iPhone & Android):\n` +
                `• خادم البريد الوارد (IMAP): mail.albrolosy.com (Port: 993, SSL)\n` +
                `• خادم البريد الصادر (SMTP): mail.albrolosy.com (Port: 465, SSL)\n\n` +
                `تحياتنا،\nفريق مؤمن للتطوير البرمجي • Moemen Team`;
            this.copyToClipboard(text, 'تم نسخ رسالة بيانات البريد للموظف بنجاح! يمكنك إرسالها له الآن.');
        },
        openEdit(email) {
            this.editUser = email.username;
            this.editUsername = email.username;
            this.editQuota = email.quota;
            this.editLimit = email.limit;
            $dispatch('open-modal', 'edit-email-modal');
        },
        openDelete(username) {
            this.deleteUser = username;
            $dispatch('open-modal', 'delete-email-modal');
        }
    }">

        @if ($apiError)
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span><strong>تنبيه النظام:</strong> {{ $apiError }}</span>
            </div>
        @endif

        <!-- Instant New Mailbox Created Alert Modal/Banner -->
        @if (session('new_mailbox_created'))
            @php $newBox = session('new_mailbox_created'); @endphp
            <div x-data="{ showBox: true }" x-show="showBox" class="p-6 rounded-2xl bg-emerald-600/15 border-2 border-emerald-500/50 text-white shadow-xl relative animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🎉</span>
                            <h3 class="text-lg font-black text-emerald-400">تم إنشاء صندوق البريد بنجاح!</h3>
                        </div>
                        <p class="text-xs text-gray-300">
                            تم تجهيز الصندوق البريدي <strong class="text-white font-mono" dir="ltr">{{ $newBox['email'] }}</strong>، يمكنك نسخ البيانات فوراً للموظف أو تحميل بطاقته PDF.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" @click="copyEmployeeShare('{{ $newBox['username'] }}', '{{ $newBox['password'] }}')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-900/30 transition transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            <span>نسخ بيانات البريد للموظف</span>
                        </button>

                        <a href="{{ route('emails.export-single-pdf', ['user' => $newBox['username'], 'password' => $newBox['password']]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#374A3C] hover:bg-[#506055] text-white border border-[#506055]/50 transition">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>تحميل بطاقة الحساب (PDF)</span>
                        </a>

                        <button type="button" @click="showBox = false" class="px-3 py-2 rounded-xl text-xs font-semibold text-gray-400 hover:text-white bg-[#141B17] border border-[#374A3C]">
                            إغلاق
                        </button>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-emerald-500/20 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs font-mono">
                    <div class="p-2.5 rounded-xl bg-[#141B17]/80 border border-[#374A3C]">
                        <span class="block text-[10px] text-gray-400 font-sans">عنوان البريد:</span>
                        <span class="font-bold text-white text-xs truncate block" dir="ltr">{{ $newBox['email'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-[#141B17]/80 border border-[#374A3C]">
                        <span class="block text-[10px] text-gray-400 font-sans">كلمة المرور المحددة:</span>
                        <span class="font-bold text-emerald-400 text-xs" dir="ltr">{{ $newBox['password'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-[#141B17]/80 border border-[#374A3C]">
                        <span class="block text-[10px] text-gray-400 font-sans">رابط بريد الويب:</span>
                        <a href="https://mail.albrolosy.com/" target="_blank" class="font-bold text-blue-400 text-xs underline" dir="ltr">https://mail.albrolosy.com/</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Client Quotas & Usage Summary -->
        @if ($clientUser && ($clientUser->max_emails || $clientUser->storage_limit_mb))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Mailboxes Quota -->
                <div class="p-4 rounded-2xl bg-white dark:bg-[#1F2A24] border border-gray-200 dark:border-[#374A3C] shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold block">حد الحسابات البريدية:</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-2xl font-black text-gray-900 dark:text-white font-mono">{{ $mailboxesCount }}</span>
                            <span class="text-xs text-gray-400 font-mono">/ {{ $clientUser->max_emails ?: 'غير محدود' }} حساب</span>
                        </div>
                    </div>
                    <div class="w-24">
                        @php
                            $emailPct = $clientUser->max_emails ? min(100, round(($mailboxesCount / $clientUser->max_emails) * 100)) : 0;
                        @endphp
                        <div class="text-[11px] font-bold text-right text-gray-400 mb-1">{{ $emailPct }}%</div>
                        <div class="w-full bg-gray-100 dark:bg-[#141B17] h-2 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $emailPct }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Storage Quota -->
                <div class="p-4 rounded-2xl bg-white dark:bg-[#1F2A24] border border-gray-200 dark:border-[#374A3C] shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold block">المساحة التخزينية المخصصة:</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-2xl font-black text-gray-900 dark:text-white font-mono">{{ $totalUsageMb }} MB</span>
                            <span class="text-xs text-gray-400 font-mono">/ {{ $clientUser->storage_limit_mb ? $clientUser->storage_limit_mb . ' MB' : 'غير محدود' }}</span>
                        </div>
                    </div>
                    <div class="w-24">
                        @php
                            $storagePct = $clientUser->storage_limit_mb ? min(100, round(($totalUsageMb / $clientUser->storage_limit_mb) * 100)) : 0;
                        @endphp
                        <div class="text-[11px] font-bold text-right text-gray-400 mb-1">{{ $storagePct }}%</div>
                        <div class="w-full bg-gray-100 dark:bg-[#141B17] h-2 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $storagePct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Email Accounts Table Card -->
        <div class="bg-white dark:bg-[#1F2A24] shadow-sm rounded-2xl overflow-hidden border border-gray-200 dark:border-[#374A3C]">
            <div class="p-6 border-b border-gray-100 dark:border-[#374A3C] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>صناديق البريد المفعلة</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 font-semibold font-mono">
                            {{ count($emails) }} حساب
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        يمكنك إنشاء، نسخ بيانات البريد للموظف فوراً، تصدير بطاقة الحساب PDF، وتعديل كلمات المرور
                    </p>
                </div>

                <a href="{{ route('emails.export-all-pdf') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-[#374A3C] text-white hover:bg-[#506055] transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>تصدير تقرير شامل لكل الصناديق (PDF)</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/70 dark:bg-[#141B17] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-[#A7B7A7] border-b border-gray-100 dark:border-[#374A3C]">
                        <tr>
                            <th scope="col" class="px-6 py-4">عنوان البريد الإلكتروني</th>
                            <th scope="col" class="px-6 py-4">المساحة التخزينية (Quota)</th>
                            <th scope="col" class="px-6 py-4">حد الإرسال اليومي</th>
                            <th scope="col" class="px-6 py-4">الحالة</th>
                            <th scope="col" class="px-6 py-4 text-left">الإجراءات والخيارات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-[#374A3C]">
                        @forelse ($emails as $email)
                            @php
                                $quotaMb = (int) ($email['quota'] ?? 1024);
                                $usageMb = (float) ($email['usage'] ?? 0);
                                $pct = $quotaMb > 0 ? min(100, round(($usageMb / $quotaMb) * 100)) : 0;
                                $hasSavedPass = isset($savedCredentials[$email['username']]) && !empty($savedCredentials[$email['username']]->plain_password);
                                $savedPass = $hasSavedPass ? $savedCredentials[$email['username']]->plain_password : '';
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xs uppercase border border-emerald-500/20">
                                            {{ substr($email['username'], 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white block font-mono text-sm" dir="ltr">
                                                {{ $email['username'] . '@' . $activeDomain }}
                                            </span>
                                            <span class="text-[11px] text-gray-400">اسم الدخول: {{ $email['username'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-48">
                                        <div class="flex justify-between text-xs mb-1.5 font-medium">
                                            <span class="text-gray-700 dark:text-gray-200">{{ $usageMb }} MB مستخدم</span>
                                            <span class="text-gray-400">{{ $quotaMb === 0 ? 'غير محدود' : $quotaMb . ' MB' }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 dark:bg-[#141B17] h-2 rounded-full overflow-hidden border border-gray-200/50 dark:border-[#374A3C]">
                                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 font-mono text-xs">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $email['sent'] ?? 0 }}</span>
                                        <span class="text-gray-400">/ {{ $email['limit'] ?? 9600 }} رسالة/يوم</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ !empty($email['suspended']) ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ !empty($email['suspended']) ? 'bg-rose-500' : 'bg-emerald-500' }}"></span>
                                        {{ !empty($email['suspended']) ? 'معلق (Suspended)' : 'نشط (Active)' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Export Single Account Card PDF (with User & Password) -->
                                        <a href="{{ route('emails.export-single-pdf', $email['username']) }}" class="p-2 text-gray-400 hover:text-emerald-500 hover:bg-emerald-500/10 rounded-lg transition" title="تصدير بطاقة الحساب وكلمة المرور (PDF)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>

                                        <!-- 1-Click Copy Employee Credentials Message -->
                                        <button type="button" @click="copyEmployeeShare('{{ $email['username'] }}', '{{ $savedPass }}')" class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="نسخ بيانات الدخول للموظف (واتساب / إيميل)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        </button>

                                        <!-- Edit -->
                                        <button @click="openEdit({{ json_encode($email) }})" class="p-2 text-gray-400 hover:text-emerald-500 hover:bg-emerald-500/10 rounded-lg transition" title="تعديل الحساب (كلمة المرور / الحصص)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <!-- Delete -->
                                        <button @click="openDelete('{{ $email['username'] }}')" class="p-2 text-gray-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="حذف الصندوق البريدي">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-[#141B17] text-gray-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                        ✉️
                                    </div>
                                    <p class="font-bold text-gray-700 dark:text-gray-300">لا توجد حسابات بريد إلكتروني منشأة بعد على الدومين [{{ $activeDomain }}].</p>
                                    <button @click="$dispatch('open-modal', 'create-email-modal')" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 transition shadow-sm">
                                        + اضغط هنا لإنشاء أول بريد إلكتروني
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal: Edit Email -->
            <x-modal name="edit-email-modal" focusable>
                <form :action="'{{ url('emails') }}/' + editUser" method="POST" class="p-6 text-right">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        تعديل الحساب البريدي: <span class="text-emerald-500 font-mono" x-text="editUsername + '@' + '{{ $activeDomain }}'"></span>
                    </h2>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        اترك حقل كلمة المرور فارغاً إذا كنت لا ترغب في تغييرها.
                    </p>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">كلمة المرور الجديدة (8–72 خانة)</label>
                            <input name="password" type="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500" placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">المساحة التخزينية (بالميجابايت MB، 0 = غير محدود)</label>
                            <input name="quota" type="number" min="0" x-model="editQuota" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" />
                            <x-input-error :messages="$errors->get('quota')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">الحد اليومي للإرسال (الحد الأقصى 9600 رسالة/يوم)</label>
                            <input name="limit" type="number" min="1" max="9600" x-model="editLimit" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" />
                            <x-input-error :messages="$errors->get('limit')" class="mt-1" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-[#374A3C]">
                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">
                            إلغاء
                        </button>

                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md transition">
                            تحديث الحساب الآن
                        </button>
                    </div>
                </form>
            </x-modal>

            <!-- Modal: Delete Email Confirmation -->
            <x-modal name="delete-email-modal" focusable>
                <form :action="'{{ url('emails') }}/' + deleteUser" method="POST" class="p-6 text-right">
                    @csrf
                    @method('DELETE')

                    <h2 class="text-lg font-bold text-rose-600">
                        تأكيد حذف الحساب البريدي
                    </h2>

                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        هل أنت متأكد من رغبتك في حذف الحساب البريدي <strong class="text-rose-600 font-mono" x-text="deleteUser + '@' + '{{ $activeDomain }}'"></strong> بشكل نهائي؟
                        <br>
                        <span class="text-xs text-rose-500 mt-2 block">تحذير: سيتم مسح جميع الرسائل والبيانات المخزنة في هذا الحساب فوراً.</span>
                    </p>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-[#374A3C]">
                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">
                            تراجع
                        </button>

                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-rose-600 text-white hover:bg-rose-500 shadow-md transition">
                            نعم، احذف الحساب
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>

        <!-- Connection Settings Info Card (White-Labeled) -->
        <div class="bg-white dark:bg-[#1F2A24] rounded-2xl p-6 border border-gray-200 dark:border-[#374A3C] shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>📱 إعدادات الربط على الهواتف والبرامج (Outlook, iPhone, Android)</span>
                </h4>
                <a href="{{ route('emails.guide') }}" class="text-xs font-bold text-emerald-500 hover:underline flex items-center gap-1">
                    <span>عرض دليل الاستخدام والشرح المصور ↗</span>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono text-gray-600 dark:text-gray-300">
                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/60 dark:border-[#374A3C]">
                    <span class="block text-[11px] text-gray-400 font-sans font-bold mb-1">البريد الوارد (IMAP / SSL):</span>
                    <span class="font-bold text-emerald-500">mail.albrolosy.com</span>
                    <span class="block text-gray-400 mt-0.5">Port: 993 (SSL/TLS)</span>
                </div>
                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/60 dark:border-[#374A3C]">
                    <span class="block text-[11px] text-gray-400 font-sans font-bold mb-1">البريد الصادر (SMTP / SSL):</span>
                    <span class="font-bold text-emerald-500">mail.albrolosy.com</span>
                    <span class="block text-gray-400 mt-0.5">Port: 465 (SSL/TLS)</span>
                </div>
                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/60 dark:border-[#374A3C]">
                    <span class="block text-[11px] text-gray-400 font-sans font-bold mb-1">واجهة بريد الويب (Webmail):</span>
                    <a href="https://mail.albrolosy.com/" target="_blank" class="font-bold text-blue-400 underline" dir="ltr">https://mail.albrolosy.com/</a>
                    <span class="block text-gray-400 mt-0.5">تسجيل الدخول بالبريد وكلمة المرور</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Create Email Account -->
    <x-modal name="create-email-modal" :show="$errors->has('username') || $errors->has('password')" focusable>
        <form action="{{ route('emails.store') }}" method="POST" class="p-6 text-right" x-data="{
            pass: '',
            generatePassword() {
                const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%&*';
                let p = '';
                for (let i = 0; i < 16; i++) {
                    p += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                this.pass = p;
                $refs.passInput.value = p;
            }
        }">
            @csrf

            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                إنشاء صندوق بريد إلكتروني جديد
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                إنشاء حساب بريدي آمن تحت الدومين: <span class="font-bold text-emerald-500 font-mono">{{ $activeDomain }}</span>
            </p>

            <div class="mt-5 space-y-4">
                <!-- Username -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">اسم المستخدم (المعرف المحلي للبريد)</label>
                    <div class="flex rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-[#374A3C]" dir="ltr">
                        <span class="inline-flex items-center px-4 bg-gray-100 dark:bg-[#141B17] text-gray-500 dark:text-gray-400 text-xs font-mono border-r border-gray-200 dark:border-[#374A3C]">
                            @ {{ $activeDomain }}
                        </span>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 border-0 font-mono" placeholder="sales, info, user.name" />
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>

                <!-- Password with generator -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200">كلمة المرور (8–72 خانة)</label>
                        <button type="button" @click="generatePassword()" class="text-xs font-bold text-emerald-500 hover:text-emerald-400 hover:underline">
                            ⚡ توليد كلمة مرور قوية
                        </button>
                    </div>
                    <input id="password" x-ref="passInput" name="password" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" placeholder="أدخل كلمة مرور قوية أو اضغط توليد" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Storage Quota -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">المساحة التخزينية بالميجابايت (0 = غير محدود، الافتراضي = 1024 MB)</label>
                    <input id="quota" name="quota" type="number" min="0" value="{{ old('quota', 1024) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" />
                    <x-input-error :messages="$errors->get('quota')" class="mt-1" />
                </div>

                <!-- Daily Limit -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">الحد اليومي للرسائل الصادرة (الافتراضي والأقصى = 9600)</label>
                    <input id="limit" name="limit" type="number" min="1" max="9600" value="{{ old('limit', 9600) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" />
                    <x-input-error :messages="$errors->get('limit')" class="mt-1" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-[#374A3C]">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">
                    إلغاء
                </button>

                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md transition">
                    إنشاء الصندوق الآن
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
