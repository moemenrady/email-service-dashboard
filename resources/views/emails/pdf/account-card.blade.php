<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>بطاقة بيانات الحساب البريدي - {{ $emailAddress }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1F2A24;
            margin: 0;
            padding: 24px;
            direction: rtl;
        }
        .header {
            border-bottom: 2px solid #10B981;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .brand {
            font-size: 16px;
            font-weight: bold;
            color: #064E3B;
        }
        .sub {
            font-size: 10px;
            color: #059669;
            margin-top: 3px;
        }
        .card {
            background-color: #F0FDF4;
            border: 2px solid #10B981;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 18px;
        }
        .card-header {
            font-size: 13px;
            font-weight: bold;
            color: #064E3B;
            border-bottom: 1px solid #A7F3D0;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        table.creds-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.creds-table td {
            padding: 7px 10px;
            font-size: 11px;
        }
        .creds-label {
            font-weight: bold;
            color: #374151;
            width: 30%;
        }
        .creds-val {
            font-weight: bold;
            color: #111827;
        }
        .highlight-box {
            background-color: #FFFFFF;
            border: 1px solid #D1D5DB;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            font-family: 'DejaVu Sans', monospace;
        }
        .info-section {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 14px;
        }
        .info-title {
            font-size: 11px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 6px;
        }
        ol {
            margin: 4px 0;
            padding-right: 20px;
            font-size: 10px;
            color: #4B5563;
            line-height: 1.6;
        }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #E5E7EB;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
        }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="brand">MOEMEN TEAM • منظومة البريد السحابي</div>
                    <div class="sub">email-service-dashboard • بطاقة بيانات الحساب البريدي للموظف</div>
                </td>
                <td style="text-align: left; font-size: 9px; color: #6B7280;">
                    تاريخ الإصدار: {{ $generatedAt }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Main Credentials Card -->
    <div class="card">
        <div class="card-header">🔐 بيانات الاعتماد والدخول (Account Credentials)</div>
        <table class="creds-table">
            <tr>
                <td class="creds-label">عنوان البريد الإلكتروني:</td>
                <td class="creds-val" dir="ltr"><span class="highlight-box" style="color: #065F46;">{{ $emailAddress }}</span></td>
            </tr>
            <tr>
                <td class="creds-label">اسم المستخدم للدخول:</td>
                <td class="creds-val" dir="ltr"><span class="highlight-box">{{ $username }}</span> (أو البريد كاملاً)</td>
            </tr>
            <tr>
                <td class="creds-label">كلمة المرور (Password):</td>
                <td class="creds-val" dir="ltr"><span class="highlight-box" style="color: #B91C1C; font-size: 12px;">{{ $password }}</span></td>
            </tr>
            <tr>
                <td class="creds-label">رابط بريد الويب (Webmail):</td>
                <td class="creds-val" dir="ltr">
                    <a href="{{ $webmailUrl }}" style="color: #059669; font-weight: bold; text-decoration: underline;">{{ $webmailUrl }}</a>
                </td>
            </tr>
        </table>
    </div>

    <!-- Server Settings -->
    <div class="info-section">
        <div class="info-title">⚙️ إعدادات الربط على الهواتف والبرامج (IMAP / SMTP Settings):</div>
        <table style="width: 100%; font-size: 10px; margin-top: 4px;">
            <tr>
                <td style="width: 50%; padding: 4px;">
                    <strong>البريد الوارد (Incoming / IMAP):</strong><br>
                    • الخادم: <span dir="ltr"><strong>{{ $serverHost }}</strong></span><br>
                    • المنفذ (Port): 993 (SSL/TLS)<br>
                    • اسم المستخدم: {{ $emailAddress }}
                </td>
                <td style="width: 50%; padding: 4px;">
                    <strong>البريد الصادر (Outgoing / SMTP):</strong><br>
                    • الخادم: <span dir="ltr"><strong>{{ $serverHost }}</strong></span><br>
                    • المنفذ (Port): 465 (SSL/TLS)<br>
                    • التحقق (Auth): مطلوب بنفس كلمة المرور
                </td>
            </tr>
        </table>
    </div>

    <!-- Step by Step Setup Instructions -->
    <div class="info-section">
        <div class="info-title">📱 كيفية فتح الحساب على هاتفك المحمول (iPhone & Android):</div>
        <ol>
            <li><strong>بريد الويب المباشر:</strong> افتح المتصفح وتوجه إلى <span dir="ltr">{{ $webmailUrl }}</span> وأدخل بريدك كاملاً وكلمة المرور.</li>
            <li><strong>آيفون (iPhone / Mail):</strong> ادخل إلى الإعدادات > تطبيق البريد > الحسابات > إضافة حساب > أخرى > إضافة حساب بريد، وأدخل بريدك وكلمة المرور، واستخدم الخادم <strong>{{ $serverHost }}</strong> للوارد والصادر مع تفعيل SSL.</li>
            <li><strong>أندرويد (Gmail App):</strong> افتح تطبيق Gmail > اضغط صورة الحساب > إضافة حساب آخر > أخرى > اكتب بريدك ثم اختر IMAP، واضبط خادم الوارد والصادر على <strong>{{ $serverHost }}</strong>.</li>
        </ol>
    </div>

    <!-- Security Notice -->
    <div style="background-color: #FEF3C7; border: 1px solid #FCD34D; border-radius: 6px; padding: 8px 12px; font-size: 9px; color: #92400E;">
        ⚠️ <strong>تنبيه أمني للموظف:</strong> يرجى الحفاظ على سرية هذه البطاقة وكلمة المرور، وعدم مشاركتها مع أي جهة خارجية.
    </div>

    <div class="footer">
        تم استخراج هذه البطاقة آلياً عبر لوحة <strong>email-service-dashboard</strong> التابعة لـ <strong>مؤمن تيم (Moemen Team)</strong> • شريكك التقني الموثوق
    </div>

</body>
</html>
