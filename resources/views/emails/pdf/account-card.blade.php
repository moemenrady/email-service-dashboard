<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $emailAddress = $emailAddress ?? ($email ?? ($username ?? 'user') . '@' . ($domain ?? 'albrolosy.com'));
        $plainPassword = $plainPassword ?? ($password ?? '********');
    @endphp
    <title>بطاقة اعتماد حساب بريدي رسمي — {{ $emailAddress }} — Moemen Team</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 14mm 10mm 14mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Cairo', 'Noto Sans Arabic', 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 8px 14px;
            background-color: #FFFFFF;
            color: #111827;
            font-size: 12px;
            line-height: 1.6;
        }

        /* Top interactive bar (hidden when printed) */
        .toolbar-no-print {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1F2A24;
            color: #FFFFFF;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
        }

        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background-color: #10B981;
            color: #FFFFFF;
        }
        .btn-print:hover {
            background-color: #059669;
        }

        .btn-download {
            background-color: #374A3C;
            color: #FFFFFF;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-download:hover {
            background-color: #506055;
        }

        .btn-back {
            background-color: transparent;
            color: #D1D5DB;
        }
        .btn-back:hover {
            color: #FFFFFF;
        }

        .content-wrap {
            max-width: 820px;
            margin: 0 auto;
        }

        body.has-toolbar {
            padding-top: 70px;
        }

        /* Official Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #111827;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .brand-block {
            text-align: right;
        }

        .brand-name {
            font-size: 24px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-badge {
            display: inline-block;
            color: #9CA3AF;
            font-weight: 700;
            font-size: 18px;
            margin-right: 6px;
        }

        .brand-sub {
            font-size: 13px;
            color: #4B5563;
            font-weight: 600;
            margin-top: 4px;
        }

        .brand-meta {
            font-size: 11px;
            color: #4B5563;
            margin-top: 2px;
        }

        .doc-badge-card {
            border: 1px solid #6EE7B7;
            background-color: #ECFDF5;
            border-radius: 8px;
            padding: 10px 16px;
            text-align: right;
            min-width: 200px;
        }

        .doc-badge-title {
            color: #047857;
            font-weight: 800;
            font-size: 13px;
        }

        .doc-badge-ref {
            font-size: 11px;
            color: #374151;
            margin-top: 4px;
            font-weight: 600;
        }

        .doc-badge-date {
            font-size: 10px;
            color: #6B7280;
            margin-top: 2px;
        }

        /* Sections */
        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Key-Value Details Grid */
        .kv-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .kv-table td {
            padding: 6px 4px;
            font-size: 12px;
            vertical-align: middle;
        }

        .kv-label {
            color: #4B5563;
            width: 25%;
            font-weight: 600;
        }

        .kv-val {
            color: #111827;
            font-weight: 700;
        }

        /* Selectable Credentials Styling - Engineered for seamless copy-paste */
        .selectable-credential {
            user-select: all !important;
            -webkit-user-select: all !important;
            -moz-user-select: all !important;
            direction: ltr !important;
            text-align: left !important;
            unicode-bidi: isolate !important;
            letter-spacing: normal !important;
            word-spacing: normal !important;
            font-family: 'Consolas', 'Courier New', 'Liberation Mono', 'Menlo', monospace !important;
            font-feature-settings: "liga" 0 !important;
            cursor: text;
        }

        .inline-code {
            font-family: 'Consolas', 'Courier New', 'Liberation Mono', monospace !important;
            background-color: #F3F4F6;
            border: 1px solid #CBD5E1;
            border-radius: 4px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 700;
            color: #111827;
            direction: ltr !important;
            text-align: left !important;
            unicode-bidi: isolate !important;
            user-select: all !important;
            -webkit-user-select: all !important;
            letter-spacing: normal !important;
            display: inline-block;
        }

        /* Dedicated Official Credentials Box */
        .cred-box {
            border: 1.5px solid #10B981;
            border-radius: 10px;
            background-color: #F0FDF4;
            padding: 8px 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .cred-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #A7F3D0;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .cred-box-title {
            font-size: 12.5px;
            font-weight: 800;
            color: #065F46;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cred-box-badge {
            font-size: 10px;
            font-weight: 700;
            color: #047857;
            background-color: #D1FAE5;
            border: 1px solid #6EE7B7;
            padding: 2px 8px;
            border-radius: 9999px;
        }

        .cred-row {
            background-color: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 5px 10px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .cred-row.cred-row-pass {
            border-color: #6EE7B7;
            background-color: #FAFEFD;
            box-shadow: 0 1px 2px rgba(16, 185, 129, 0.08);
        }

        .cred-info {
            flex: 1;
            text-align: right;
        }

        .cred-title {
            font-size: 11.5px;
            font-weight: 800;
            color: #111827;
        }

        .cred-desc {
            font-size: 9.5px;
            color: #6B7280;
            margin-top: 1px;
        }

        .cred-action-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cred-field {
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 6px 12px;
            min-width: 280px;
            text-align: left;
        }

        .cred-row-pass .cred-field {
            background-color: #ECFDF5;
            border-color: #A7F3D0;
        }

        .cred-val {
            font-size: 13px;
            font-weight: 700;
            color: #0F172A;
            display: block;
        }

        .cred-val-pass {
            color: #047857;
            font-size: 14.5px;
            font-weight: 800;
        }

        .cred-val-link {
            color: #059669;
            text-decoration: underline;
        }

        .cred-copy-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            background-color: #10B981;
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .cred-copy-btn:hover {
            background-color: #059669;
        }

        /* Settings Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 6px;
        }

        .data-table th {
            background-color: #F9FAFB;
            color: #374151;
            padding: 5px 10px;
            font-weight: 700;
            border-bottom: 1px solid #E5E7EB;
            text-align: right;
        }

        .data-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #F3F4F6;
            color: #1F2937;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background-color: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #047857;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Instructions list */
        .steps-box {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 11px;
            color: #334155;
            margin-bottom: 10px;
        }

        .steps-box ol {
            margin: 2px 0 0 0;
            padding-right: 18px;
            line-height: 1.5;
        }

        /* Verification & Stamp Footer (Page bottom) */
        .verification-footer {
            margin-top: 10px;
            border-top: 1px solid #E5E7EB;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .verification-text-block {
            max-width: 58%;
        }

        .verification-title {
            font-size: 12px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }

        .verification-desc {
            font-size: 10px;
            color: #4B5563;
            line-height: 1.6;
        }

        .seal-block {
            text-align: left;
        }

        .seal-title {
            font-size: 11px;
            font-weight: 800;
            color: #111827;
        }

        .seal-sub {
            font-size: 9px;
            color: #6B7280;
            margin-top: 1px;
        }

        .seal-badge {
            display: inline-block;
            border: 2px solid #10B981;
            border-radius: 6px;
            padding: 5px 12px;
            color: #047857;
            font-weight: 800;
            font-size: 10px;
            text-align: center;
            margin-top: 6px;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .doc-bottom-bar {
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9CA3AF;
            border-top: 1px dashed #E5E7EB;
            padding-top: 6px;
        }

        @media print {
            .toolbar-no-print,
            .interactive-only {
                display: none !important;
            }
            body.has-toolbar {
                padding-top: 0 !important;
            }
            body {
                padding: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm 14mm 10mm 14mm;
            }
        }
    </style>
</head>
<body class="{{ empty($isPdfMode) ? 'has-toolbar' : '' }}">

    @if (empty($isPdfMode))
        <!-- Floating Interactive Toolbar -->
        <div class="toolbar-no-print">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-weight: 800; color: #10B981; font-size: 13px;">MOEMEN TEAM</span>
                <span style="color: #6B7280;">•</span>
                <span style="font-size: 12px;">بطاقة اعتماد حساب بريدي رسمي ({{ $emailAddress }})</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" onclick="window.print()" class="toolbar-btn btn-print">
                    <span>🖨️ طباعة أو حفظ كـ PDF</span>
                </button>
                <a href="{{ request()->fullUrlWithQuery(['download' => 1]) }}" class="toolbar-btn btn-download">
                    <span>📥 تحميل مباشر كملف PDF</span>
                </a>
                <button type="button" onclick="window.close()" class="toolbar-btn btn-back">
                    <span>✕ إغلاق</span>
                </button>
            </div>
        </div>
    @endif

    <div class="content-wrap">
        <!-- Document Header (Moemen Team Official Format) -->
        <div class="header">
            <div class="brand-block">
                <div class="brand-name">MOEMEN TEAM <span class="brand-badge">MT</span></div>
                <div class="brand-sub">للحلول البرمجية والتحول الرقمي المتكامل</div>
                <div class="brand-meta">moemen-team.tech • info@moemen-team.tech</div>
            </div>
            <div class="doc-badge-card">
                <div class="doc-badge-title">بطاقة اعتماد حساب بريدي رسمي</div>
                <div class="doc-badge-ref">رقم المرجع: REF-MBX-{{ strtoupper(substr(md5($emailAddress), 0, 7)) }}-2026</div>
                <div class="doc-badge-date">تاريخ الإصدار: {{ $generatedAtFormatted ?? now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Section 1: Project & Account Details -->
        <div class="section">
            <div class="section-title">
                <span>📋 بيانات الحساب البريدي والمشروع</span>
            </div>
            <table class="kv-table">
                <tr>
                    <td class="kv-label">اسم المشروع / النطاق:</td>
                    <td class="kv-val" dir="ltr" style="text-align: right;">{{ $domain }}</td>
                    <td class="kv-label">اسم المستخدم للدخول:</td>
                    <td class="kv-val" dir="ltr" style="text-align: right;"><code class="inline-code" dir="ltr">{{ $emailAddress }}</code></td>
                </tr>
                <tr>
                    <td class="kv-label">الجهة / الشركة التابع لها:</td>
                    <td class="kv-val">{{ $clientUser?->name ?? 'شركة البرلسي' }}</td>
                    <td class="kv-label">حصة المساحة المخصصة:</td>
                    <td class="kv-val">{{ $quota }} MB</td>
                </tr>
                <tr>
                    <td class="kv-label">حالة الحساب:</td>
                    <td class="kv-val">
                        <span class="status-badge">
                            <span>●</span>
                            <span>نشط ومحمي (Active)</span>
                        </span>
                    </td>
                    <td class="kv-label">حد الإرسال اليومي:</td>
                    <td class="kv-val">{{ $limit ?? 9600 }} رسالة / اليوم</td>
                </tr>
            </table>
        </div>

        <!-- Section: Dedicated Official Login Credentials (Engineered for effortless copy-pasting) -->
        <div class="cred-box">
            <div class="cred-box-header">
                <div class="cred-box-title">
                    <span>🔑</span>
                    <span>بيانات الاعتماد وتسجيل الدخول الرسمية (Official Credentials)</span>
                </div>
                <div class="cred-box-badge">
                    مخصصة للاستخدام والنسخ المباشر
                </div>
            </div>

            <!-- Email Address & Login Username -->
            <div class="cred-row">
                <div class="cred-info">
                    <div class="cred-title">عنوان البريد الإلكتروني / اسم الدخول (Email Address & Login Username):</div>
                    <div class="cred-desc">يُستخدم هذا البريد كاملاً كاسم مستخدم (Username) عند تسجيل الدخول أو إعداد البرامج والتطبيقات.</div>
                </div>
                <div class="cred-action-wrap">
                    <div class="cred-field">
                        <span class="cred-val selectable-credential" dir="ltr">{{ $emailAddress }}</span>
                    </div>
                    <button type="button" class="cred-copy-btn interactive-only" onclick="copyCred('{{ $emailAddress }}', this)" title="نسخ البريد الإلكتروني">
                        <span>📋 نسخ البريد</span>
                    </button>
                </div>
            </div>

            <!-- Official Password -->
            <div class="cred-row cred-row-pass">
                <div class="cred-info">
                    <div class="cred-title">كلمة المرور الرسمية المعتمدة للدخول (Account Password):</div>
                    <div class="cred-desc">كلمة المرور مشفرة ومطابقة لسجلات الخادم. حساسة للأحرف الكبيرة والصغيرة (Case-Sensitive).</div>
                </div>
                <div class="cred-action-wrap">
                    <div class="cred-field">
                        <span class="cred-val cred-val-pass selectable-credential" dir="ltr">{{ $plainPassword }}</span>
                    </div>
                    <button type="button" class="cred-copy-btn interactive-only" onclick="copyCred('{{ $plainPassword }}', this)" title="نسخ كلمة المرور">
                        <span>📋 نسخ كلمة المرور</span>
                    </button>
                </div>
            </div>

            <!-- Webmail URL -->
            <div class="cred-row">
                <div class="cred-info">
                    <div class="cred-title">رابط بريد الويب المباشر (Direct Webmail Access):</div>
                    <div class="cred-desc">لتسجيل الدخول السريع عبر أي متصفح إنترنت (Google Chrome, Safari, Edge) دون الحاجة لبرامج.</div>
                </div>
                <div class="cred-action-wrap">
                    <div class="cred-field">
                        <a href="{{ $webmailUrl }}" target="_blank" class="cred-val cred-val-link selectable-credential" dir="ltr">{{ $webmailUrl }}</a>
                    </div>
                    <button type="button" class="cred-copy-btn interactive-only" onclick="copyCred('{{ $webmailUrl }}', this)" title="نسخ الرابط">
                        <span>📋 نسخ الرابط</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Section 2: Technical Connection Settings -->
        <div class="section">
            <div class="section-title">
                <span>⚙️ إعدادات الربط على برامج الكمبيوتر والهواتف الذكية (IMAP / SMTP)</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">نوع الخادم (Protocol)</th>
                        <th style="width: 35%;">عنوان الخادم (Host)</th>
                        <th style="width: 20%;">المنفذ (Port)</th>
                        <th style="width: 20%;">نوع التشفير (Security)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>البريد الوارد (Incoming / IMAP)</strong></td>
                        <td dir="ltr" style="font-family: monospace; font-weight: bold; color: #047857;">{{ $serverHost }}</td>
                        <td dir="ltr" style="font-weight: bold;">993</td>
                        <td>SSL / TLS (مشفر)</td>
                    </tr>
                    <tr>
                        <td><strong>البريد الصادر (Outgoing / SMTP)</strong></td>
                        <td dir="ltr" style="font-family: monospace; font-weight: bold; color: #047857;">{{ $serverHost }}</td>
                        <td dir="ltr" style="font-weight: bold;">465</td>
                        <td>SSL / TLS (مشفر)</td>
                    </tr>
                    <tr>
                        <td><strong>بيانات تسجيل الدخول للربط</strong></td>
                        <td colspan="3">
                            اسم المستخدم: <code class="inline-code" dir="ltr">{{ $emailAddress }}</code> &bull; كلمة المرور: <code class="inline-code" dir="ltr">{{ $plainPassword }}</code> <span style="color: #6B7280; font-size: 10px; margin-right: 6px;">(مطلوب تفعيل المصادقة / Authentication Required)</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section 3: Quick Setup Guide for Employee -->
        <div class="section">
            <div class="section-title">
                <span>📱 إرشادات تشغيل وفتح البريد للموظف</span>
            </div>
            <div class="steps-box">
                <ol>
                    <li><strong>بريد الويب المباشر (Webmail):</strong> يمكنك فتح البريد من أي متصفح عبر الرابط <strong dir="ltr">https://mail.albrolosy.com/</strong> وتسجيل الدخول ببريدك وكلمة المرور.</li>
                    <li><strong>هواتف آيفون (iPhone / Mail):</strong> اذهب إلى <em>الإعدادات > تطبيق البريد > الحسابات > إضافة حساب > أخرى > إضافة حساب بريد</em>، ثم أدخل بياناتك والخادم <strong dir="ltr">{{ $serverHost }}</strong> للوارد والصادر مع تفعيل SSL.</li>
                    <li><strong>هواتف أندرويد (Android / Gmail):</strong> افتح تطبيق Gmail > <em>إضافة حساب آخر > أخرى</em> > اكتب بريدك ثم اختر <strong>IMAP</strong>، واضبط الخوادم على <strong dir="ltr">{{ $serverHost }}</strong>.</li>
                    <li><strong>برامج سطح المكتب (Outlook & Apple Mail):</strong> اختر إعداد يدوي (Manual Setup)، وحدد نوع الحساب <strong>IMAP</strong> بالمنافذ 993 و 465.</li>
                </ol>
            </div>
        </div>

        <!-- Verification & Official Seal Section (Exact match to Moemen Team Statement) -->
        <div class="verification-footer">
            <div class="verification-text-block">
                <div class="verification-title">إقرار صحة البيانات:</div>
                <div class="verification-desc">
                    تم إصدار هذه الوثيقة آلياً استناداً إلى سجلات منظومة Moemen Team وتعتبر هذه الوثيقة بطاقة اعتماد رسمية لحساب البريد الإلكتروني وإعدادات الربط السحابي الخاصة بالموظف.
                </div>
            </div>
            <div class="seal-block">
                <div class="seal-title">إدارة الخدمات السحابية والدعم الفني</div>
                <div class="seal-sub">Moemen Team Cloud & Infrastructure Office</div>
                <div class="seal-badge">
                    VERIFIED SEAL<br>
                    معتمد إلكترونياً
                </div>
            </div>
        </div>

        <!-- Bottom Document Bar -->
        <div class="doc-bottom-bar">
            <div>Moemen Team — Cloud Services Dashboard • https://moemen-team.tech</div>
            <div>الصفحة 1 من 1</div>
        </div>
    </div>

    <script>
        function copyCred(text, btn) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    var orig = btn.innerHTML;
                    btn.innerHTML = '<span>✓ تم النسخ!</span>';
                    btn.style.backgroundColor = '#059669';
                    setTimeout(function() {
                        btn.innerHTML = orig;
                        btn.style.backgroundColor = '';
                    }, 2000);
                }).catch(function() {
                    fallbackCopy(text, btn);
                });
            } else {
                fallbackCopy(text, btn);
            }
        }

        function fallbackCopy(text, btn) {
            var input = document.createElement('textarea');
            input.value = text;
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            try {
                document.execCommand('copy');
                var orig = btn.innerHTML;
                btn.innerHTML = '<span>✓ تم النسخ!</span>';
                btn.style.backgroundColor = '#059669';
                setTimeout(function() {
                    btn.innerHTML = orig;
                    btn.style.backgroundColor = '';
                }, 2000);
            } catch (err) {
                prompt('انسخ البيانات يدوياً:', text);
            }
            document.body.removeChild(input);
        }
    </script>
</body>
</html>
