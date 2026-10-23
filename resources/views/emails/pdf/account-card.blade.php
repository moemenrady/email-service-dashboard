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
            margin: 12mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 24px;
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
            padding-bottom: 14px;
            margin-bottom: 24px;
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
            margin-bottom: 22px;
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

        /* 3 Summary Stat Cards */
        .stats-grid {
            display: flex;
            gap: 12px;
            margin-bottom: 22px;
        }

        .stat-card {
            flex: 1;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            background-color: #FAFAFA;
            padding: 14px 12px;
            text-align: center;
        }

        .stat-card.primary {
            border-color: #6EE7B7;
            background-color: #F0FDF4;
        }

        .stat-label {
            font-size: 11px;
            color: #6B7280;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .stat-val {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .stat-val.highlight {
            color: #047857;
            font-family: monospace;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        /* Highlight box for credentials */
        .cred-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
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
            padding: 9px 12px;
            font-weight: 700;
            border-bottom: 1px solid #E5E7EB;
            text-align: right;
        }

        .data-table td {
            padding: 9px 12px;
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
            padding: 12px 16px;
            font-size: 11px;
            color: #334155;
            margin-bottom: 20px;
        }

        .steps-box ol {
            margin: 4px 0 0 0;
            padding-right: 18px;
            line-height: 1.7;
        }

        /* Verification & Stamp Footer (Page bottom) */
        .verification-footer {
            margin-top: 30px;
            border-top: 1px solid #E5E7EB;
            padding-top: 16px;
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
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9CA3AF;
            border-top: 1px dashed #E5E7EB;
            padding-top: 8px;
        }

        @media print {
            .toolbar-no-print {
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
                margin: 12mm 15mm 12mm 15mm;
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
                <a href="{{ route('emails.index') }}" class="toolbar-btn btn-back">
                    <span>✕ إغلاق</span>
                </a>
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
                    <td class="kv-val" dir="ltr" style="text-align: right;">{{ $username }}</td>
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

        <!-- 3 Highlight / Stat Cards (Direct Visual Access) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">عنوان البريد الإلكتروني</div>
                <div class="stat-val" dir="ltr" style="font-size: 14px; font-family: monospace;">{{ $emailAddress }}</div>
            </div>
            <div class="stat-card primary">
                <div class="stat-label">كلمة المرور المعتمدة للدخول</div>
                <div class="stat-val highlight" dir="ltr">{{ $plainPassword }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">رابط بريد الويب (Webmail)</div>
                <div class="stat-val" style="font-size: 13px;">
                    <a href="{{ $webmailUrl }}" target="_blank" dir="ltr" style="color: #059669; text-decoration: underline;">mail.albrolosy.com</a>
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
                            اسم المستخدم: <strong dir="ltr">{{ $emailAddress }}</strong> • كلمة المرور: <strong dir="ltr">{{ $plainPassword }}</strong> (مطلوب تفعيل المصادقة)
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

</body>
</html>
