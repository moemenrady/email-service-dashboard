<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عقد استلام تقني واعتماد منظومة البريد السحابي — شركة البرلسي — Moemen Team</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 6mm 8mm 6mm 8mm;
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
            padding: 8px 10px;
            background-color: #FFFFFF;
            color: #0F172A;
            font-size: 10px;
            line-height: 1.4;
        }

        .mono {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            direction: ltr;
            display: inline-block;
        }

        /* Top interactive bar (hidden when printed) */
        .toolbar-no-print {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #0F172A;
            color: #FFFFFF;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 9999;
        }

        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 6px;
            font-family: 'Cairo', sans-serif;
            font-size: 11px;
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
            background-color: #1E293B;
            color: #FFFFFF;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .btn-download:hover {
            background-color: #334155;
        }

        .btn-back {
            background-color: transparent;
            color: #94A3B8;
        }
        .btn-back:hover {
            color: #FFFFFF;
        }

        .content-wrap {
            max-width: 780px;
            margin: 0 auto;
        }

        body.has-toolbar {
            padding-top: 50px;
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
        }

        /* Certificate Container Frame */
        .cert-card {
            border: 1.5px solid #047857;
            border-radius: 10px;
            padding: 10px 12px 8px 12px;
            background: #FFFFFF;
            position: relative;
        }

        /* Header */
        .cert-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0F172A;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .brand-box {
            text-align: right;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 900;
            color: #0F172A;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1.1;
        }

        .brand-tag {
            color: #10B981;
        }

        .brand-subtitle {
            font-size: 9.5px;
            color: #475569;
            font-weight: 700;
            margin-top: 2px;
        }

        .cert-badge-box {
            background: linear-gradient(135deg, #064E3B 0%, #0F172A 100%);
            border: 1px solid #10B981;
            border-radius: 8px;
            padding: 5px 12px;
            text-align: left;
            color: #FFFFFF;
            min-width: 210px;
        }

        .cert-badge-title {
            font-size: 11px;
            font-weight: 900;
            color: #34D399;
            text-align: center;
            letter-spacing: 0.3px;
        }

        .cert-badge-meta {
            font-size: 9px;
            color: #E2E8F0;
            margin-top: 2px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            font-weight: 600;
        }

        /* Parties Info Strip */
        .parties-strip {
            display: flex;
            gap: 6px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 5px 8px;
            margin-bottom: 6px;
        }

        .party-col {
            flex: 1;
            border-left: 1px solid #E2E8F0;
            padding-left: 6px;
        }

        .party-col:last-child {
            border-left: none;
            padding-left: 0;
        }

        .party-label {
            font-size: 8px;
            color: #64748B;
            font-weight: 700;
            display: block;
        }

        .party-val {
            font-size: 9.5px;
            color: #0F172A;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-verified {
            display: inline-block;
            background: #D1FAE5;
            color: #047857;
            font-size: 8px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 4px;
            border: 1px solid #A7F3D0;
        }

        /* Section Block */
        .sec-block {
            margin-bottom: 6px;
        }

        .sec-head {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .sec-num {
            background: #047857;
            color: #FFFFFF;
            width: 16px;
            height: 16px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 900;
        }

        .sec-title {
            font-size: 10.5px;
            font-weight: 800;
            color: #0F172A;
            margin: 0;
        }

        /* Technical Summary Banner */
        .tech-summary-box {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-right: 3.5px solid #059669;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 9.5px;
            color: #064E3B;
            line-height: 1.45;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tech-summary-box strong {
            color: #047857;
            font-weight: 800;
        }

        /* Credentials Box */
        .creds-container {
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            overflow: hidden;
            background: #FFFFFF;
        }

        .creds-table {
            width: 100%;
            border-collapse: collapse;
        }

        .creds-table td {
            padding: 4px 8px;
            font-size: 9px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
        }

        .creds-table tr:last-child td {
            border-bottom: none;
        }

        .cr-lbl {
            width: 25%;
            color: #475569;
            font-weight: 700;
            background: #F8FAFC;
            border-left: 1px solid #E2E8F0;
        }

        .cr-val {
            color: #0F172A;
            font-weight: 700;
        }

        .code-pill {
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 700;
            color: #0F172A;
        }

        .code-pill.secret {
            background: #FEF3C7;
            border-color: #FCD34D;
            color: #92400E;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .code-pill.highlight {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1D4ED8;
        }

        .sec-warning {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 5px;
            padding: 4px 8px;
            margin-top: 3px;
            font-size: 8.5px;
            color: #92400E;
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1.35;
        }

        /* 3. Getting Started Section */
        .start-guide-wrap {
            display: flex;
            gap: 8px;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-right: 3.5px solid #047857;
            border-radius: 6px;
            padding: 5px 8px;
            align-items: stretch;
        }

        .start-steps-col {
            flex: 1.25;
            display: flex;
            flex-direction: column;
            gap: 4px;
            border-left: 1px solid #E2E8F0;
            padding-left: 8px;
            justify-content: center;
        }

        .start-step-item {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            font-size: 8.8px;
            color: #1E293B;
            line-height: 1.35;
        }

        .step-badge {
            background: #047857;
            color: #FFFFFF;
            font-size: 7.5px;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 3px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .start-videos-col {
            flex: 1.1;
            display: flex;
            flex-direction: column;
            gap: 3.5px;
            justify-content: center;
        }

        .video-link-item {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
            padding: 2px 6px;
        }

        .video-link-item.official-pdf-row {
            background: #ECFDF5;
            border-color: #A7F3D0;
        }

        .vid-icon {
            font-size: 10px;
            flex-shrink: 0;
        }

        .vid-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 6px;
        }

        .vid-title {
            color: #1E293B;
            font-weight: 700;
            font-size: 8.5px;
        }

        .vid-btn {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            color: #1D4ED8;
            font-weight: 800;
            text-decoration: none;
            padding: 1.5px 6px;
            border-radius: 3px;
            font-size: 8px;
            white-space: nowrap;
        }

        .vid-btn.pdf-btn {
            background: #D1FAE5;
            border-color: #6EE7B7;
            color: #047857;
        }

        /* Server Settings Table */
        .settings-grid-wrap {
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .settings-table-box {
            flex: 1.4;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            overflow: hidden;
        }

        .settings-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            text-align: center;
        }

        .settings-table th {
            background: #0F172A;
            color: #FFFFFF;
            padding: 3px 5px;
            font-weight: 800;
            font-size: 8.5px;
        }

        .settings-table td {
            padding: 3px 5px;
            border-bottom: 1px solid #E2E8F0;
            color: #1E293B;
            font-weight: 600;
        }

        .settings-table tr:nth-child(even) td {
            background: #F8FAFC;
        }

        .settings-table tr:last-child td {
            border-bottom: none;
        }

        .it-guide-box {
            flex: 1;
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 8.5px;
            color: #166534;
            line-height: 1.4;
        }

        .it-guide-title {
            font-weight: 800;
            color: #047857;
            font-size: 9px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Support Section */
        .support-grid {
            display: flex;
            gap: 8px;
        }

        .support-card {
            direction: rtl;
            flex-direction: row;
            flex: 1;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .support-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .support-info {
            flex: 1;
        }

        .support-lbl {
            font-size: 8px;
            color: #64748B;
            font-weight: 700;
            display: block;
        }

        .support-link {
            font-size: 9px;
            color: #0F172A;
            font-weight: 800;
            text-decoration: none;
        }

        /* Signatures & Seal Box */
        .sign-seal-section {
            border-top: 1.5px dashed #CBD5E1;
            padding-top: 5px;
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sign-col {
            flex: 1;
            text-align: center;
        }

        .sign-title {
            font-size: 8.5px;
            font-weight: 800;
            color: #475569;
            margin-bottom: 2px;
        }

        .sign-name {
            font-size: 9.5px;
            font-weight: 900;
            color: #0F172A;
        }

        .sign-status {
            font-size: 7.5px;
            color: #047857;
            font-weight: 700;
            margin-top: 1px;
        }

        /* Official Moemen Team Digital Seal */
        .official-seal-wrap {
            flex: 0.9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .seal-badge {
            border: 1.5px solid #059669;
            background: #ECFDF5;
            color: #064E3B;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 8px;
            font-weight: 900;
            letter-spacing: 0.3px;
            box-shadow: 0 1px 3px rgba(5,150,105,0.15);
            text-align: center;
        }

        .seal-subtext {
            font-size: 7px;
            color: #64748B;
            margin-top: 2px;
            font-weight: 600;
            text-align: center;
        }

        .cert-footer-line {
            text-align: center;
            font-size: 7px;
            color: #94A3B8;
            margin-top: 4px;
            border-top: 1px solid #F1F5F9;
            padding-top: 2px;
        }
    </style>
</head>
<body class="{{ !($isPdfMode ?? false) ? 'has-toolbar' : '' }}">

@if(!($isPdfMode ?? false))
    <div class="toolbar-no-print">
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="{{ route('emails.index') }}" class="toolbar-btn btn-back">
                &larr; العودة للوحة التحكم
            </a>
            <span style="font-size:12px; font-weight:700; color:#E2E8F0;">
                عقد استلام تقني — Moemen Team
            </span>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <button onclick="window.print()" class="toolbar-btn btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                طباعة الوثيقة
            </button>
            <a href="{{ route('documents.technical-handover', ['download' => 1]) }}" class="toolbar-btn btn-download">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                تحميل PDF رسمي
            </a>
        </div>
    </div>
@endif

<div class="content-wrap">
    <div class="cert-card">

        <!-- Header -->
        <div class="cert-header">
            <div class="brand-box">
                <div class="brand-title">
                    <span>MOEMEN TEAM</span>
                    <span class="brand-tag">✦</span>
                </div>
                <div class="brand-subtitle">
                    فريق مؤمن للتطوير البرمجي وهندسة الأنظمة السحابية
                </div>
            </div>

            <div class="cert-badge-box">
                <div class="cert-badge-title">عقد استلام تقني واعتماد نهائي</div>
                <div class="cert-badge-meta">
                    <span>المرجع: <strong class="mono" dir="ltr">MT-THC-2026-ALB01</strong></span>
                    <span>التاريخ: <strong>{{ date('Y-m-d') }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Parties Meta Strip -->
        <div class="parties-strip">
            <div class="party-col">
                <span class="party-label">الجهة المنفذة:</span>
                <span class="party-val">Moemen Team (فريق مؤمن)</span>
            </div>
            <div class="party-col">
                <span class="party-label">الجهة المستلمة (العميل):</span>
                <span class="party-val">شركة البرلسي</span>
            </div>
            <div class="party-col">
                <span class="party-label">النطاق السحابي المعتمد:</span>
                <span class="party-val mono" dir="ltr">albrolosy.com</span>
            </div>
            <div class="party-col">
                <span class="party-label">حالة المنظومة الفنية:</span>
                <span class="badge-verified">✓ جاهز للعمل بروداكشن 100%</span>
            </div>
        </div>

        <!-- 1. Technical Achievement -->
        <div class="sec-block">
            <div class="sec-head">
                <div class="sec-num">1</div>
                <h3 class="sec-title">ملخص ما تم إنجازه فنياً</h3>
            </div>
            <div class="tech-summary-box">
                <span style="font-size:13px;">🛡️</span>
                <div>
                    تم بنجاح وبأعلى المعايير الهندسية ربط نطاق شركة البرلسي (<span class="mono" dir="ltr" style="font-weight:700;">albrolosy.com</span>) وتوثيقه بالكامل بأعلى معايير الأمان العالمية (سجلات <strong>SPF</strong> و <strong>DKIM</strong> و <strong>DMARC</strong> و <strong>MX</strong>) وتشفير <strong>SSL/TLS</strong>، لضمان وصول كافة الإيميلات الصادرة والواردة إلى صندوق الوارد (<strong>Inbox</strong>) مباشرة بأعلى موثوقية وبمنع كامل لتصنيفها كرسائل غير مرغوب فيها (Spam).
                </div>
            </div>
        </div>

        <!-- 2. Master Credentials -->
        <div class="sec-block">
            <div class="sec-head">
                <div class="sec-num">2</div>
                <h3 class="sec-title">بيانات الدخول والاعتماد الإداري (Master Credentials)</h3>
            </div>
            <div class="creds-container">
                <table class="creds-table">
                    <tr>
                        <td class="cr-lbl">رابط لوحة التحكم الإدارية:</td>
                        <td class="cr-val">
                            <a href="https://email-service.moemen-team.tech/login" target="_blank" class="code-pill highlight mono" style="text-decoration:none;">https://email-service.moemen-team.tech/login</a>
                            <span style="color:#64748B; font-size:8px; margin-right:4px;">(لإدارة وإنشاء وتوزيع الحسابات والحصص)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cr-lbl">اسم المستخدم (البريد الإداري):</td>
                        <td class="cr-val">
                            <span class="code-pill highlight mono" style="font-weight:800;">albrolosymanagement@albrolosy.com</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cr-lbl">كلمة المرور الرئيسية:</td>
                        <td class="cr-val">
                            <span class="code-pill secret mono">Xzd@7Xy5ZqU6#Clrr3!</span>
                            <span style="color:#059669; font-size:8px; font-weight:700; margin-right:6px;">✓ موحدة ومعتمدة للوحة التحكم والبريد المباشر</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cr-lbl">رابط بريد الويب المباشر (Webmail):</td>
                        <td class="cr-val">
                            <a href="https://mail.albrolosy.com/" target="_blank" class="code-pill mono" style="text-decoration:none;">https://mail.albrolosy.com/</a>
                            <span style="color:#64748B; font-size:8px; margin-right:4px;">(لقراءة وإرسال البريد إلكترونياً من أي متصفح)</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="cr-lbl">الحصة التشغيلية المخصصة:</td>
                        <td class="cr-val">
                            <strong style="color:#047857;">200 حساب بريد رسمي</strong> متاح للإنشاء الفوري &bull; 
                            <strong style="color:#047857;">512,000 MB (500 جيجابايت)</strong> مساحة سحابية كاملة
                        </td>
                    </tr>
                </table>
            </div>

            <div class="sec-warning">
                <span style="font-size:10px;">⚠️</span>
                <div>
                    <strong>إجراء أمني واجب (Security Notice):</strong> يُرجى من إدارة شركة البرلسي تغيير كلمة المرور فور تسجيل الدخول الأول من خلال إعدادات الحساب (Profile Settings) في لوحة التحكم، أو طلب إعادة تعيين كلمة المرور عبر النظام حفاظاً على سرية بيانات المنشأة.
                </div>
            </div>
        </div>

        <!-- 3. How to Start & Video Tours -->
        <div class="sec-block">
            <div class="sec-head">
                <div class="sec-num">3</div>
                <h3 class="sec-title">كيف تبدأ؟ (دليل الانطلاق والجولات المرئية السريعة)</h3>
            </div>
            <div class="start-guide-wrap">
                <!-- Right: Steps -->
                <div class="start-steps-col">
                    <div class="start-step-item">
                        <span class="step-badge">خطوة 1</span>
                        <div>
                            <strong>تسجيل الدخول والبدء الفوري:</strong> قم بتسجيل الدخول عبر الرابط الموضح أعلاه لإنشاء حسابات الموظفين وتعيين التحويلات البريدية (<span class="mono" dir="ltr" style="font-weight:700;">Forwarding</span>) بضغطة زر.
                        </div>
                    </div>
                    <div class="start-step-item">
                        <span class="step-badge">خطوة 2</span>
                        <div>
                            <strong>دليل الاستخدام المباشر داخل اللوحة:</strong> أعددنا لكم قسم «دليل الاستخدام» داخل لوحة التحكم مباشرة؛ يحتوي على شرح مفصل لكيفية استخدام النظام وربط الحسابات بتطبيقات الهواتف والكمبيوتر (<span class="mono" dir="ltr">Outlook / Apple Mail / Gmail</span>).
                        </div>
                    </div>
                </div>

                <!-- Left: Video links & Document -->
                <div class="start-videos-col">
                    <div class="video-link-item">
                        <span class="vid-icon">🎬</span>
                        <div class="vid-info">
                            <span class="vid-title">جولة سريعة داخل الداشبورد (دقيقتين):</span>
                            <a href="https://drive.google.com/file/d/1AGCHnkrAhOdKsTS8NmkSptCN_jpEGwJO/view?usp=drivesdk" target="_blank" class="vid-btn">
                                مشاهدة فيديو التجربة &larr;
                            </a>
                        </div>
                    </div>
                    <div class="video-link-item">
                        <span class="vid-icon">💻</span>
                        <div class="vid-info">
                            <span class="vid-title">شرح الاستخدام والربط على Outlook:</span>
                            <a href="https://drive.google.com/file/d/1gddBAA2Del_YsvwGEVt4njAJrvzmS5Xl/view?usp=drivesdk" target="_blank" class="vid-btn">
                                مشاهدة فيديو الشرح &larr;
                            </a>
                        </div>
                    </div>
                    <div class="video-link-item official-pdf-row">
                        <span class="vid-icon">📄</span>
                        <div class="vid-info">
                            <span class="vid-title">وثيقة التسليم الرسمية الشاملة:</span>
                            <a href="https://email-service.moemen-team.tech/documents/Moemen-Team-Cloud-Email-Handover-AR.pdf" target="_blank" class="vid-btn pdf-btn">
                                تحميل الوثيقة (PDF) &larr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Server Settings & Quick Setup -->
        <div class="sec-block">
            <div class="sec-head">
                <div class="sec-num">4</div>
                <h3 class="sec-title">بيانات الربط السريع لأجهزة وبرامج البريد (Server Settings)</h3>
            </div>
            <div class="settings-grid-wrap">
                <div class="settings-table-box">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th>نوع الخدمة</th>
                                <th>اسم الخادم (Server Host)</th>
                                <th>المنفذ (Port)</th>
                                <th>نوع التشفير والأمان</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:800; color:#047857;">البريد الوارد (IMAP)</td>
                                <td class="mono" dir="ltr" style="font-weight:700;">mail.albrolosy.com</td>
                                <td class="mono" dir="ltr"><strong style="color:#0F172A;">993</strong></td>
                                <td>SSL / TLS (مشفر بالكامل)</td>
                            </tr>
                            <tr>
                                <td style="font-weight:800; color:#2563EB;">البريد الصادر (SMTP)</td>
                                <td class="mono" dir="ltr" style="font-weight:700;">mail.albrolosy.com</td>
                                <td class="mono" dir="ltr"><strong style="color:#0F172A;">465</strong></td>
                                <td>SSL / TLS (التوثيق مطلوب)</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700; color:#475569;">البريد الوارد (POP3 بديل)</td>
                                <td class="mono" dir="ltr">mail.albrolosy.com</td>
                                <td class="mono" dir="ltr"><strong>995</strong></td>
                                <td>SSL / TLS (اختياري)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="it-guide-box">
                    <div class="it-guide-title">
                        <span>📱</span> إرشادات مسؤول الـ IT والربط بالهواتف
                    </div>
                    <div>
                        &bull; <strong>اسم المستخدم:</strong> هو عنوان الإيميل كاملاً (مثل: info@albrolosy.com).<br>
                        &bull; <strong>كلمة المرور:</strong> هي الباسورد الخاص بنفس الإيميل.<br>
                        &bull; <strong>دليل التثبيت:</strong> خطوات الربط ببرامج Outlook وهواتف iPhone و Android مشروحة بالكامل وبالصور داخل قسم <strong>«دليل الاستخدام»</strong> في اللوحة عبر الرابط:
                        <div style="margin-top:2px;">
                            <a href="https://email-service.moemen-team.tech/emails/guide" target="_blank" class="mono" dir="ltr" style="color:#047857; font-weight:800; text-decoration:none; font-size:8px;">email-service.moemen-team.tech/emails/guide</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Support SLA -->
        <div class="sec-block">
            <div class="sec-head">
                <div class="sec-num">5</div>
                <h3 class="sec-title">آلية الدعم الفني وضمان استمرارية الخدمة</h3>
            </div>
            <div class="support-grid">
                <div class="support-card">
                    <div class="support-icon">✉️</div>
                    <div class="support-info">
                        <span class="support-lbl">البريد الإلكتروني الرسمي للدعم الفني:</span>
                        <a href="mailto:support@moemen-team.tech" class="support-link mono" dir="ltr">support@moemen-team.tech</a>
                        <div style="font-size:7.5px; color:#64748B;">متاح لاستقبال البلاغات والاستفسارات التقنية</div>
                    </div>
                </div>

                <div class="support-card">
                    <div class="support-icon">⚡</div>
                    <div class="support-info">
                        <span class="support-lbl">بوابة المعالجة السريعة (Fast Fixing):</span>
                        <a href="https://moemen-team.tech" target="_blank" class="support-link mono" dir="ltr">https://moemen-team.tech</a>
                        <div style="font-size:7.5px; color:#059669; font-weight:700;">طلب تدخل هندسي فوري عبر نظام Moemen Team</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Sign-off & Seal -->
        <div class="sign-seal-section">
            <div class="sign-col">
                <div class="sign-title">الطرف الأول (الجهة المنفذة والضامنة):</div>
                <div class="sign-name">فريق مؤمن للتطوير البرمجي</div>
                <div class="sign-status">Moemen Team &bull; Cloud Engineering</div>
            </div>

            <div class="official-seal-wrap">
                <div class="seal-badge">
                    ★ MOEMEN TEAM CERTIFIED ★
                </div>
                <div class="seal-subtext">
                    مستند استلام رسمي وموثق رقمياً بنسبة 100%
                </div>
            </div>

            <div class="sign-col">
                <div class="sign-title">الطرف الثاني (الجهة المستلمة):</div>
                <div class="sign-name">شركة البرلسي (Albrolosy)</div>
                <div class="sign-status">إقرار بالاستلام وصلاحية العمل والتشغيل</div>
            </div>
        </div>

        <div class="cert-footer-line">
            وثيقة تسليم رسمية صادرة ومعتمدة سحابياً &bull; جميع الحقوق محفوظة لـ Moemen Team &copy; {{ date('Y') }} &bull; https://moemen-team.tech
        </div>

    </div>
</div>

</body>
</html>
