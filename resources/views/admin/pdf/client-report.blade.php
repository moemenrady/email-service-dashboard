<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير حساب العميل - {{ $client->name }}</title>
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
        .report-meta {
            text-align: left;
            font-size: 10px;
            color: #4B5563;
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
            margin-top: 5px;
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
                <div class="brand-title">MOEMEN TEAM • منظومة إدارة البريد</div>
                <div class="brand-sub">email-service-dashboard • تقرير بيانات العميل وصناديق البريد</div>
            </td>
            <td style="text-align: left; vertical-align: middle;" class="report-meta">
                <div><strong>تاريخ التقرير:</strong> {{ $generatedAt }}</div>
                <div><strong>الحالة:</strong> نشط ومحمي (Active)</div>
            </td>
        </tr>
    </table>

    <!-- Client Profile & Quotas -->
    <div class="card">
        <div class="card-title">بيانات العميل والباقة المخصصة (Client Profile & Quotas)</div>
        <table class="grid">
            <tr>
                <td class="label">اسم العميل / الشركة:</td>
                <td class="value"><strong>{{ $client->name }}</strong></td>
                <td class="label">البريد الإلكتروني للوحة:</td>
                <td class="value" dir="ltr">{{ $client->email }}</td>
            </tr>
            <tr>
                <td class="label">النطاق المخصص (Domain):</td>
                <td class="value highlight" dir="ltr">{{ $domain }}</td>
                <td class="label">تاريخ الانضمام:</td>
                <td class="value">{{ $client->created_at->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td class="label">حد الحسابات البريدية:</td>
                <td class="value">
                    {{ $client->max_emails ? $client->max_emails . ' حسابات' : 'غير محدود (Unlimited)' }}
                    (المستخدم حالياً: <strong>{{ $mailboxesCount }}</strong> حساب)
                </td>
                <td class="label">المساحة المخصصة الإجمالية:</td>
                <td class="value">
                    {{ $client->storage_limit_mb ? $client->storage_limit_mb . ' MB' : 'غير محدود (Unlimited)' }}
                    (المستهلك حالياً: <strong>{{ $totalUsageMb }} MB</strong>)
                </td>
            </tr>
        </table>
    </div>

    <!-- Mailboxes Table -->
    <div class="card">
        <div class="card-title">قائمة صناديق البريد الإلكتروني النشطة ({{ $mailboxesCount }} صندوق)</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35%;">عنوان البريد الإلكتروني</th>
                    <th style="width: 20%;">المساحة المستهلكة</th>
                    <th style="width: 20%;">الحصة (Quota)</th>
                    <th style="width: 15%;">حد الإرسال اليومي</th>
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
                            لا توجد صناديق بريد منشأة بعد على هذا الدومين.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Connection & Server Settings -->
    <div class="card">
        <div class="card-title">بيانات وإعدادات الربط السحابي (Connection & Access Details)</div>
        <table class="grid">
            <tr>
                <td class="label">رابط بريد الويب (Webmail):</td>
                <td class="value" colspan="3"><a href="{{ $webmailUrl }}" style="color: #059669; font-weight: bold;" dir="ltr">{{ $webmailUrl }}</a></td>
            </tr>
            <tr>
                <td class="label">خادم البريد الوارد (IMAP / SSL):</td>
                <td class="value" dir="ltr"><strong>{{ $serverHost }}</strong> (Port: 993, SSL/TLS)</td>
                <td class="label">خادم البريد الصادر (SMTP / SSL):</td>
                <td class="value" dir="ltr"><strong>{{ $serverHost }}</strong> (Port: 465, SSL/TLS)</td>
            </tr>
            <tr>
                <td class="label">اسم المستخدم للدخول:</td>
                <td class="value" colspan="3">عنوان البريد الإلكتروني كاملاً (مثل: info@{{ $domain }})</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        تم استخراج هذا التقرير آلياً عبر لوحة <strong>email-service-dashboard</strong> التابعة لـ <strong>مؤمن تيم (Moemen Team)</strong> • جميع الحقوق محفوظة © {{ date('Y') }}
    </div>

</body>
</html>
