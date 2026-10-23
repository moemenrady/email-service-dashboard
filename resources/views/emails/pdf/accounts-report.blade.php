<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>كشف حسابات البريد الإلكتروني - {{ $domain }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1F2A24;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #10B981;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #064E3B;
            margin: 0;
        }
        .brand-sub {
            font-size: 10px;
            color: #059669;
            margin-top: 4px;
        }
        .card {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 12px;
            font-weight: bold;
            color: #064E3B;
            margin-bottom: 8px;
            border-bottom: 1px dashed #D1D5DB;
            padding-bottom: 4px;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
        }
        table.grid td {
            padding: 5px 8px;
            font-size: 10px;
        }
        .label {
            font-weight: bold;
            color: #374151;
            width: 25%;
        }
        .value {
            color: #111827;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #064E3B;
            color: #FFFFFF;
            font-size: 10px;
            padding: 7px;
            text-align: right;
        }
        table.data-table td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            font-size: 10px;
            text-align: right;
        }
        table.data-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            font-size: 9px;
            color: #6B7280;
            text-align: center;
        }
        .highlight {
            color: #059669;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="brand-title">MOEMEN TEAM • منظومة إدارة البريد السحابي</div>
                <div class="brand-sub">email-service-dashboard • كشف الحسابات وصناديق البريد للدومين</div>
            </td>
            <td style="text-align: left; vertical-align: middle;">
                <div style="font-size: 10px; color: #4B5563;"><strong>تاريخ الاستخراج:</strong> {{ $generatedAt }}</div>
                <div style="font-size: 10px; color: #059669; font-weight: bold;">النطاق: {{ $domain }}</div>
            </td>
        </tr>
    </table>

    <!-- Domain & Quota Summary -->
    <div class="card">
        <div class="card-title">ملخص الحصص والمساحات التخزينية (Quota & Storage Summary)</div>
        <table class="grid">
            <tr>
                <td class="label">اسم النطاق (Domain):</td>
                <td class="value highlight" dir="ltr"><strong>{{ $domain }}</strong></td>
                <td class="label">إجمالي صناديق البريد:</td>
                <td class="value"><strong>{{ $mailboxesCount }}</strong> حساب</td>
            </tr>
            <tr>
                <td class="label">المساحة المستهلكة الإجمالية:</td>
                <td class="value"><strong>{{ $totalUsageMb }} MB</strong></td>
                <td class="label">إجمالي الحصص المخصصة:</td>
                <td class="value">{{ $totalQuotaMb === 0 ? 'غير محدود' : $totalQuotaMb . ' MB' }}</td>
            </tr>
            @if ($clientUser)
            <tr>
                <td class="label">حد الحسابات للعميل:</td>
                <td class="value">{{ $clientUser->max_emails ? $clientUser->max_emails . ' حسابات' : 'غير محدود' }}</td>
                <td class="label">سقف المساحة المخصصة:</td>
                <td class="value">{{ $clientUser->storage_limit_mb ? $clientUser->storage_limit_mb . ' MB' : 'غير محدود' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Accounts Table -->
    <div class="card">
        <div class="card-title">قائمة حسابات البريد الإلكتروني ({{ $mailboxesCount }} حساب)</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35%;">عنوان البريد الإلكتروني</th>
                    <th style="width: 20%;">المساحة المستهلكة</th>
                    <th style="width: 20%;">سعة الصندوق (Quota)</th>
                    <th style="width: 15%;">الحد اليومي</th>
                    <th style="width: 10%;">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($emails as $email)
                    @php
                        $quotaMb = (int) ($email['quota'] ?? 1024);
                        $usageMb = (float) ($email['usage'] ?? 0);
                    @endphp
                    <tr>
                        <td dir="ltr" style="font-weight: bold; text-align: left;">{{ $email['username'] }}@<span class="highlight">{{ $domain }}</span></td>
                        <td>{{ $usageMb }} MB</td>
                        <td>{{ $quotaMb === 0 ? 'غير محدود' : $quotaMb . ' MB' }}</td>
                        <td>{{ $email['limit'] ?? 9600 }} رسالة/يوم</td>
                        <td>
                            @if (!empty($email['suspended']))
                                <span class="badge badge-warning">معلق</span>
                            @else
                                <span class="badge badge-success">نشط</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #6B7280; padding: 12px;">
                            لا توجد صناديق بريد منشأة بعد.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Connection Info -->
    <div class="card">
        <div class="card-title">إعدادات الربط والدخول المباشر</div>
        <table class="grid">
            <tr>
                <td class="label">رابط بريد الويب (Webmail):</td>
                <td class="value" colspan="3"><a href="{{ $webmailUrl }}" style="color: #059669; font-weight: bold;" dir="ltr">{{ $webmailUrl }}</a></td>
            </tr>
            <tr>
                <td class="label">خادم البريد (IMAP / SMTP):</td>
                <td class="value" dir="ltr"><strong>{{ $serverHost }}</strong></td>
                <td class="label">المنافذ المعتمدة:</td>
                <td class="value">IMAP: 993 (SSL) • SMTP: 465 (SSL)</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        تم استخراج هذا التقرير آلياً عبر لوحة <strong>email-service-dashboard</strong> التابعة لـ <strong>مؤمن تيم (Moemen Team)</strong> • جميع الحقوق محفوظة © {{ date('Y') }}
    </div>

</body>
</html>
