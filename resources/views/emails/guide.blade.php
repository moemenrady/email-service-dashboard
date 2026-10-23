<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-xl shadow-inner">
                    📖
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight font-heading">
                        دليل استخدام وإعداد البريد الإلكتروني — User Setup Guide
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        شرح كامل لطريقة فتح الحسابات على الهواتف والكمبيوتر، ودليل إنشاء بريد للموظف وإرسال البيانات له
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $webmailUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#1F2A24] text-white hover:bg-[#374A3C] border border-[#506055]/40 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>فتح بريد الويب (Webmail)</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{
        copyText(text, msg = 'تم النسخ بنجاح!') {
            navigator.clipboard.writeText(text).then(() => {
                alert(msg);
            });
        }
    }">

        <!-- Quick Access Banner -->
        <div class="p-6 rounded-2xl bg-gradient-to-r from-[#1F2A24] to-[#2B3B32] border border-[#374A3C] text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">الوصول السريع والفوري</span>
                <h3 class="text-lg font-black text-white">رابط بريد الويب المباشر (Webmail)</h3>
                <p class="text-xs text-gray-300">
                    يمكنك وأي موظف فتح البريد من أي متصفح في العالم دون الحاجة لبرامج إضافية عبر الرابط الرسمي المعتمد:
                </p>
                <div class="mt-2 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-black/40 border border-emerald-500/30 text-emerald-400 font-mono text-sm font-bold" dir="ltr">
                    <span>{{ $webmailUrl }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="button" @click="copyText('{{ $webmailUrl }}', 'تم نسخ رابط بريد الويب بنجاح!')" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-[#374A3C] hover:bg-[#506055] text-white border border-[#506055]/50 transition">
                    نسخ الرابط
                </button>
                <a href="{{ $webmailUrl }}" target="_blank" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md transition">
                    الدخول الآن ↗
                </a>
            </div>
        </div>

        <!-- Section 1: Employee Onboarding & Credential Dispatch (ميزة خاصة ومطلوبة) -->
        <div class="bg-white dark:bg-[#1F2A24] rounded-2xl shadow-sm border-2 border-emerald-500/30 overflow-hidden">
            <div class="p-6 bg-emerald-500/5 border-b border-gray-100 dark:border-[#374A3C] flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 mb-2">
                        ⭐ ميزة حصرية في لوحتك
                    </span>
                    <h3 class="text-lg font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>دليل إنشاء بريد للموظف وإرسال الصلاحيات له مباشرة (1-Click Dispatch)</span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        كيفية إنشاء حساب بريدي جديد لأي موظف في شركتك وإرسال بيانات الدخول المجهزة له فوراً بنقرة واحدة
                    </p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="p-5 rounded-2xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/70 dark:border-[#374A3C] space-y-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-black text-sm">
                        1
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">إنشاء الحساب وتوليد كلمة المرور</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        توجه إلى صفحة <strong class="text-emerald-500">حسابات البريد</strong> واضغط على <strong>إنشاء بريد جديد</strong>، أدخل اسم الموظف أو القسم (مثل <code class="text-emerald-400 font-mono">ahmed</code> أو <code class="text-emerald-400 font-mono">sales</code>) ثم اضغط على <strong>⚡ توليد كلمة مرور قوية</strong>.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-5 rounded-2xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/70 dark:border-[#374A3C] space-y-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-black text-sm">
                        2
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">نسخ البيانات أو تصدير PDF</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        بمجرد الضغط على زر الحفظ، ستظهر نافذة منبثقة تحتوي على زر <strong>📋 نسخ بيانات البريد للموظف</strong> وكذلك زر <strong>📄 تحميل بطاقة الحساب PDF</strong>. كما يمكنك النقر على زر النسخ أو التصدير بجانب أي حساب في الجدول في أي وقت.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-5 rounded-2xl bg-gray-50 dark:bg-[#141B17] border border-gray-200/70 dark:border-[#374A3C] space-y-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-black text-sm">
                        3
                    </div>
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">الإرسال الفوري للموظف</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        افتح محادثة الواتساب أو التلجرام أو أرسل رسالة للموظف، واعمل <strong>لصق (Paste)</strong>؛ سيصل للموظف قالب رسالة احترافي ومرتب باسم شركتك يحتوي على بريده، كلمة المرور، ورابط بريد الويب المباشر <span class="font-mono text-emerald-400">https://mail.albrolosy.com/</span> لفتح حسابه فوراً!
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 2: Mobile Devices Setup (iOS & Android) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- iOS / iPhone -->
            <div class="bg-white dark:bg-[#1F2A24] rounded-2xl shadow-sm border border-gray-200 dark:border-[#374A3C] overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-[#374A3C] flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-xl">
                        🍎
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">طريقة فتح البريد على الآيفون والآيباد (Apple iOS)</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">تطبيق البريد الرسمي في هواتف iPhone و iPad</p>
                    </div>
                </div>

                <div class="p-6 space-y-4 text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center font-bold shrink-0">1</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">افتح الإعدادات:</strong>
                            توجه إلى تطبيق <strong>الإعدادات (Settings)</strong> في هاتفك ثم اضغط على <strong>تطبيق البريد (Mail)</strong>.
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center font-bold shrink-0">2</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">إضافة الحساب:</strong>
                            اضغط على <strong>الحسابات (Accounts)</strong> > <strong>إضافة حساب (Add Account)</strong> > ثم اختر <strong>أخرى (Other)</strong> > <strong>إضافة حساب بريد (Add Mail Account)</strong>.
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center font-bold shrink-0">3</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">كتابة البيانات الأساسية:</strong>
                            أدخل اسمك، وعنوان البريد الإلكتروني كاملاً (مثل: <code class="text-blue-400 font-mono">info@{{ $activeDomain }}</code>)، وكلمة المرور، والوصف.
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center font-bold shrink-0">4</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">إعدادات خادم البريد (IMAP):</strong>
                            تأكد من اختيار <strong>IMAP</strong>، ثم املأ الحقول التالية للوارد والصادر:
                            <div class="mt-2 p-3 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200 dark:border-[#374A3C] font-mono space-y-1">
                                <div><strong>خادم الوارد (Incoming):</strong> <span class="text-emerald-400">{{ $serverHost }}</span></div>
                                <div><strong>اسم المستخدم:</strong> بريدك الإلكتروني كاملاً</div>
                                <div><strong>كلمة المرور:</strong> كلمة مرور بريدك</div>
                                <div class="pt-2 border-t border-gray-200 dark:border-[#374A3C]"><strong>خادم الصادر (Outgoing SMTP):</strong> <span class="text-emerald-400">{{ $serverHost }}</span></div>
                                <div><strong>اسم المستخدم:</strong> بريدك الإلكتروني كاملاً</div>
                                <div><strong>كلمة المرور:</strong> كلمة مرور بريدك</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center font-bold shrink-0">5</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">الحفظ والمزامنة:</strong>
                            اضغط على <strong>التالي (Next)</strong> ثم <strong>حفظ (Save)</strong>. سيبدأ تطبيق البريد فوراً في جلب وتلقي الرسائل وتفعيل الإشعارات.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Android / Gmail -->
            <div class="bg-white dark:bg-[#1F2A24] rounded-2xl shadow-sm border border-gray-200 dark:border-[#374A3C] overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-[#374A3C] flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xl">
                        🤖
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">طريقة فتح البريد على هواتف أندرويد (Gmail App & Samsung)</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">تطبيق Gmail المعتمد في هواتف سامسونج، شاومي، هواوي وغيرها</p>
                    </div>
                </div>

                <div class="p-6 space-y-4 text-xs text-gray-600 dark:text-gray-300">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-bold shrink-0">1</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">افتح تطبيق Gmail:</strong>
                            اضغط على <strong>صورة الحساب</strong> الشخصي في أعلى يمين الشاشة.
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-bold shrink-0">2</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">إضافة الحساب:</strong>
                            اختر <strong>إضافة حساب آخر (Add another account)</strong> > ثم اضغط على <strong>أخرى (Other)</strong>.
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-bold shrink-0">3</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">إدخال البريد ونوع الحساب:</strong>
                            أدخل عنوان بريدك كاملاً، واضغط على <strong>إعداد يدوي (Manual setup)</strong> ثم اختر <strong>شخصي (IMAP)</strong> وأدخل كلمة المرور.
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-bold shrink-0">4</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">إعدادات الخوادم:</strong>
                            <div class="mt-2 p-3 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200 dark:border-[#374A3C] font-mono space-y-1">
                                <div><strong>خادم البريد الوارد:</strong> <span class="text-emerald-400">{{ $serverHost }}</span></div>
                                <div><strong>المنفذ:</strong> 993 • <strong>الأمان:</strong> SSL/TLS</div>
                                <div class="pt-2 border-t border-gray-200 dark:border-[#374A3C]"><strong>خادم البريد الصادر:</strong> <span class="text-emerald-400">{{ $serverHost }}</span></div>
                                <div><strong>المنفذ:</strong> 465 • <strong>الأمان:</strong> SSL/TLS</div>
                                <div><strong>طلب تسجيل الدخول:</strong> مفعّل (نعم)</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center font-bold shrink-0">5</span>
                        <div>
                            <strong class="text-gray-900 dark:text-white block text-sm">تم بنجاح:</strong>
                            اضغط على <strong>التالي</strong> وحدد معدل المزامنة، واكتب اسمك الذي سيظهر للمستلمين عند إرسال الرسائل.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Connection Cheat-Sheet for Desktop (Outlook & Mac) -->
        <div class="bg-white dark:bg-[#1F2A24] rounded-2xl p-6 border border-gray-200 dark:border-[#374A3C] shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                <span>💻 إعدادات برامج الكمبيوتر (Microsoft Outlook, Apple Mail, Thunderbird)</span>
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">
                جدول البيانات التقنية المباشرة للربط على أي برنامج أو نظام طرف ثالث:
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-mono">
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200 dark:border-[#374A3C] space-y-2">
                    <span class="font-sans font-bold text-gray-700 dark:text-gray-200 block text-xs">خادم البريد الوارد (IMAP)</span>
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-500 font-bold text-sm">{{ $serverHost }}</span>
                        <button type="button" @click="copyText('{{ $serverHost }}')" class="text-gray-400 hover:text-white">نسخ</button>
                    </div>
                    <div class="text-[11px] text-gray-400">Port: 993 • SSL/TLS</div>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200 dark:border-[#374A3C] space-y-2">
                    <span class="font-sans font-bold text-gray-700 dark:text-gray-200 block text-xs">خادم البريد الصادر (SMTP)</span>
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-500 font-bold text-sm">{{ $serverHost }}</span>
                        <button type="button" @click="copyText('{{ $serverHost }}')" class="text-gray-400 hover:text-white">نسخ</button>
                    </div>
                    <div class="text-[11px] text-gray-400">Port: 465 • SSL/TLS</div>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#141B17] border border-gray-200 dark:border-[#374A3C] space-y-2">
                    <span class="font-sans font-bold text-gray-700 dark:text-gray-200 block text-xs">رابط بريد الويب (Webmail)</span>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-400 font-bold text-xs truncate">{{ $webmailUrl }}</span>
                        <button type="button" @click="copyText('{{ $webmailUrl }}')" class="text-gray-400 hover:text-white">نسخ</button>
                    </div>
                    <div class="text-[11px] text-gray-400">متوافق مع كل المتصفحات</div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
