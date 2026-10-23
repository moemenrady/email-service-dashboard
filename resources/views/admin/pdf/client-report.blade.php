<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب العميل والخدمات السحابية — {{ $clientUser->name }} — Moemen Team</title>
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
            min-width: 220px;
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
            font-size: 20px;
            font-weight: 800;
            color: #111827;
        }

        .stat-val.highlight {
            color: #047857;
        }

        /* Table */
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

        .total-row {
            background-color: #F9FAFB;
            font-weight: 800;
            border-top: 2px solid #E5E7EB;
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
                <span style="font-size: 12px;">كشف حساب العميل والخدمات السحابية ({{ $clientUser->name }})</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" onclick="window.print()" class="toolbar-btn btn-print">
                    <span>🖨️ طباعة أو حفظ كـ PDF</span>
                </button>
                <a href="{{ request()->fullUrlWithQuery(['download' => 1]) }}" class="toolbar-btn btn-download">
                    <span>📥 تحميل مباشر كملف PDF</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="toolbar-btn btn-back">
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
                <div class="doc-badge-title">كشف حساب العميل والخدمات السحابية</div>
                <div class="doc-badge-ref">رقم المرجع: REF-CLI-{{ $clientUser->id }}-2026</div>
                <div class="doc-badge-date">تاريخ الإصدار: {{ $generatedAtFormatted ?? now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Section 1: Client & Contract Details -->
        <div class="section">
            <div class="section-title">
                <span>👤 بيانات العميل والنطاق المخصص</span>
            </div>
            <table class="kv-table">
                <tr>
                    <td class="kv-label">اسم العميل / الشركة:</td>
                    <td class="kv-val">{{ $clientUser->name }}</td>
                    <td class="kv-label">البريد الإلكتروني للوحة:</td>
                    <td class="kv-val" dir="ltr" style="text-align: right;">{{ $clientUser->email }}</td>
                </tr>
                <tr>
                    <td class="kv-label">النطاق السحابي المخصص:</td>
                    <td class="kv-val" dir="ltr" style="text-align: right;">{{ $clientUser->assigned_domain }}</td>
                    <td class="kv-label">تاريخ التسجيل:</td>
                    <td class="kv-val">{{ $clientUser->created_at?->format('Y-m-d') ?? '2026-10-23' }}</td>
                </tr>
                <tr>
                    <td class="kv-label">حالة الحساب:</td>
                    <td class="kv-val">
                        <span class="status-badge">
                            <span>●</span>
                            <span>نشط ومحمي (Active)</span>
                        </span>
                    </td>
                    <td class="kv-label">رابط بريد الويب (Webmail):</td>
                    <td class="kv-val">
                        <a href="{{ $webmailUrl }}" target="_blank" dir="ltr" style="color: #059669; text-decoration: underline;">https://mail.albrolosy.com/</a>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 3 Highlight / Stat Cards (Direct Visual Access) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">سقف الحسابات البريدية</div>
                <div class="stat-val">{{ $clientUser->max_emails ? $clientUser->max_emails . ' حساب' : 'غير محدود' }}</div>
            </div>
            <div class="stat-card primary">
                <div class="stat-label">الحسابات المنشأة حالياً</div>
                <div class="stat-val highlight">{{ count($emails) }} حسابات</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">إجمالي الحصة التخزينية المخصصة</div>
                <div class="stat-val">{{ $clientUser->storage_limit_mb ? $clientUser->storage_limit_mb . ' MB' : 'غير محدود' }}</div>
            </div>
        </div>

        <!-- Section 2: Detailed Mailboxes List -->
        <div class="section">
            <div class="section-title">
                <span>📑 سجل الحسابات البريدية التابعة للعميل</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">عنوان البريد الإلكتروني</th>
                        <th style="width: 15%;">اسم المستخدم</th>
                        <th style="width: 15%;">المساحة المستهلكة</th>
                        <th style="width: 15%;">الحصة المحددة</th>
                        <th style="width: 15%;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($emails as $idx => $em)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td dir="ltr" style="font-weight: bold; font-family: monospace; text-align: right;">
                                {{ $em['email'] ?? ($em['username'] . '@' . $clientUser->assigned_domain) }}
                            </td>
                            <td dir="ltr" style="text-align: right;">{{ $em['username'] }}</td>
                            <td dir="ltr" style="text-align: right;">{{ $em['usage'] ?? 0 }} MB</td>
                            <td dir="ltr" style="text-align: right;">{{ ($em['quota'] ?? 0) === 0 ? 'غير محدود' : ($em['quota'] . ' MB') }}</td>
                            <td>
                                <span class="status-badge">
                                    <span>●</span>
                                    <span>{{ !empty($em['suspended']) ? 'معلق' : 'نشط ومحمي' }}</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #6B7280; padding: 20px;">لا توجد حسابات مسجلة حالياً على هذا النطاق.</td>
                        </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3" style="text-align: left;">إجمالي المساحة المستهلكة المسجلة:</td>
                        <td colspan="3" dir="ltr" style="text-align: right; font-weight: bold; color: #047857;">
                            {{ $totalUsageMb }} MB (من أصل {{ $totalQuotaMb }} MB)
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Section 3: Technical Server Settings -->
        <div class="section">
            <div class="section-title">
                <span>⚙️ إعدادات الربط السحابي المعتمدة للنطاق</span>
            </div>
            <table class="kv-table">
                <tr>
                    <td class="kv-label">خادم البريد الوارد (IMAP):</td>
                    <td class="kv-val" dir="ltr" style="text-align: right;">{{ $serverHost }} (Port: 993, SSL/TLS)</td>
                    <td class="kv-label">خادم البريد الصادر (SMTP):</td>
                    <td class="kv-val" dir="ltr" style="text-align: right;">{{ $serverHost }} (Port: 465, SSL/TLS)</td>
                </tr>
            </table>
        </div>

        <!-- Verification & Official Seal Section (Exact match to Moemen Team Statement) -->
        <div class="verification-footer">
            <div class="verification-text-block">
                <div class="verification-title">إقرار صحة البيانات:</div>
                <div class="verification-desc">
                    تم إصدار هذا الكشف آلياً استناداً إلى سجلات منظومة Moemen Team وتعتبر هذه الوثيقة كشفاً رسمياً معتمداً للتعاملات والخدمات السحابية والحصص الخاصة بالعميل.
                </div>
            </div>
            <div class="seal-block">
                <div class="seal-title">إدارة الخدمات السحابية والدعم الفني</div>
                <div class="seal-sub">Moemen Team Cloud & Technical Office</div>
                <div class="seal-badge">
                    VERIFIED SEAL<br>
                    معتمد إلكترونياً
                </div>
            </div>
        </div>

        <!-- Bottom Document Bar -->
        <div class="doc-bottom-bar">
            <div>Moemen Team — Client Dashboard • https://moemen-team.tech</div>
            <div>الصفحة 1 من 1</div>
        </div>
    </div>

</body>
</html>
