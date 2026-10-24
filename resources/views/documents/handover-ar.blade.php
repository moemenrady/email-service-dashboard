<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وثيقة تسليم منظومة البريد السحابي ودليل التشغيل — شركة البرلسي — Moemen Team</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 14mm 12mm 14mm;
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
            padding: 20px;
            background-color: #FFFFFF;
            color: #111827;
            font-size: 11.5px;
            line-height: 1.55;
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
            background: #1F2A24;
            color: #FFFFFF;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(0,0,0,0.2);
            z-index: 9999;
        }

        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 8px;
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
            padding-top: 60px;
        }

        /* Page Breaks */
        .page-break {
            page-break-before: always;
            break-before: page;
            padding-top: 15px;
        }

        /* Official Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2.5px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .brand-block {
            text-align: right;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-badge {
            color: #10B981;
            font-weight: 900;
        }

        .brand-sub {
            font-size: 12px;
            color: #374151;
            font-weight: 700;
            margin-top: 3px;
        }

        .brand-meta {
            font-size: 10px;
            color: #6B7280;
            margin-top: 1px;
        }

        .doc-badge-card {
            border: 1.5px solid #10B981;
            background-color: #ECFDF5;
            border-radius: 10px;
            padding: 8px 14px;
            text-align: right;
            min-width: 230px;
        }

        .doc-badge-title {
            color: #047857;
            font-weight: 800;
            font-size: 12px;
        }

        .doc-badge-ref {
            font-size: 10.5px;
            color: #1F2937;
            margin-top: 3px;
            font-weight: 700;
        }

        .doc-badge-date {
            font-size: 9.5px;
            color: #4B5563;
            margin-top: 2px;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #064E3B 0%, #1F2A24 100%);
            border-radius: 12px;
            padding: 16px 20px;
            color: #FFFFFF;
            margin-bottom: 18px;
            border: 1px solid #10B981;
            position: relative;
            overflow: hidden;
        }

        .hero-banner h1 {
            margin: 0;
            font-size: 17px;
            font-weight: 900;
            color: #FFFFFF;
        }

        .hero-banner p {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #D1FAE5;
            line-height: 1.5;
        }

        /* Sections */
        .section {
            margin-bottom: 16px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #E5E7EB;
        }

        .section-icon {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background-color: #ECFDF5;
            color: #047857;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 12px;
        }

        .section-title {
            font-size: 12.5px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        /* Credentials Card */
        .credentials-card {
            border: 1.5px solid #10B981;
            background-color: #F8FAFC;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .credentials-card-header {
            background-color: #047857;
            color: #FFFFFF;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11.5px;
            font-weight: 800;
        }

        .credentials-table {
            width: 100%;
            border-collapse: collapse;
        }

        .credentials-table tr {
            border-bottom: 1px solid #E2E8F0;
        }

        .credentials-table tr:last-child {
            border-bottom: none;
        }

        .credentials-table td {
            padding: 7px 14px;
            font-size: 11px;
            vertical-align: middle;
        }

        .cred-label {
            width: 26%;
            color: #475569;
            font-weight: 700;
            background-color: #F1F5F9;
        }

        .cred-value {
            width: 74%;
            color: #0F172A;
            font-weight: 700;
        }

        .cred-box {
            background-color: #FFFFFF;
            border: 1px solid #CBD5E1;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
            font-size: 11px;
        }

        .cred-box.highlight {
            border-color: #10B981;
            background-color: #ECFDF5;
            color: #065F46;
            font-weight: 800;
        }

        .cred-box.secret {
            border-color: #F59E0B;
            background-color: #FEF3C7;
            color: #92400E;
            font-weight: 800;
        }

        /* Metric Grid */
        .stats-grid {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }

        .stat-card {
            flex: 1;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            background-color: #FFFFFF;
            padding: 10px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .stat-card.primary {
            border-color: #6EE7B7;
            background-color: #F0FDF4;
        }

        .stat-label {
            font-size: 10px;
            color: #64748B;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .stat-val {
            font-size: 16px;
            font-weight: 900;
            color: #0F172A;
        }

        .stat-val.highlight {
            color: #047857;
        }

        .stat-desc {
            font-size: 9px;
            color: #64748B;
            margin-top: 1px;
        }

        /* Feature Blocks */
        .features-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 16px;
        }

        .feature-item {
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 10px 14px;
            background-color: #FFFFFF;
        }

        .feature-item.accent {
            border-right: 4px solid #10B981;
            background-color: #F8FAFC;
        }

        .feature-head {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .feature-title {
            font-weight: 800;
            font-size: 11.5px;
            color: #0F172A;
        }

        .feature-desc {
            font-size: 10.5px;
            color: #475569;
            line-height: 1.5;
            margin: 0;
        }

        .feature-steps {
            margin: 4px 0 0 0;
            padding-right: 18px;
            font-size: 10px;
            color: #334155;
            line-height: 1.5;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 12px;
        }

        .data-table th {
            background-color: #1E293B;
            color: #FFFFFF;
            padding: 7px 10px;
            font-weight: 700;
            text-align: right;
            border: 1px solid #1E293B;
        }

        .data-table td {
            padding: 6px 10px;
            border: 1px solid #E2E8F0;
            color: #1E293B;
        }

        .data-table tr:nth-child(even) {
            background-color: #F8FAFC;
        }

        /* Quick Tips Card */
        .tips-card {
            background-color: #F0FDF4;
            border: 1px dashed #059669;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        .tips-title {
            font-weight: 800;
            font-size: 11px;
            color: #065F46;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tips-list {
            margin: 0;
            padding-right: 16px;
            font-size: 10px;
            color: #064E3B;
            line-height: 1.5;
        }

        /* Footer & Official Seal */
        .footer-seal {
            margin-top: 20px;
            padding-top: 14px;
            border-top: 2px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .seal-signature-box {
            text-align: center;
            width: 200px;
        }

        .seal-stamp {
            border: 2px dashed #047857;
            background-color: #ECFDF5;
            color: #047857;
            font-weight: 900;
            font-size: 10.5px;
            padding: 8px 12px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .seal-signer {
            font-weight: 800;
            font-size: 11px;
            color: #0F172A;
        }

        .seal-title {
            font-size: 9.5px;
            color: #64748B;
        }

        .footer-notice {
            font-size: 9px;
            color: #64748B;
            line-height: 1.4;
            max-width: 480px;
        }

        @media print {
            .toolbar-no-print {
                display: none !important;
            }
            body {
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="{{ empty($isPdfMode) ? 'has-toolbar' : '' }}">

@if (empty($isPdfMode))
    <div class="toolbar-no-print">
        <div style="font-weight:800; font-size:12px; display:flex; align-items:center; gap:8px;">
            <span style="color:#10B981;">📄 Moemen Team</span>
            <span>وثيقة تسليم خدمة البريد السحابي — شركة البرلسي (نسخة قابلة للطباعة)</span>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('emails.index') }}" class="toolbar-btn btn-back">← العودة للوحة</a>
            <button onclick="window.print()" class="toolbar-btn btn-print">🖨️ طباعة / حفظ كـ PDF</button>
        </div>
    </div>
@endif

<div class="content-wrap">

    <!-- ==================== PAGE 1: SERVICE HANDOVER & CREDENTIALS ==================== -->
    <div class="header">
        <div class="brand-block">
            <div class="brand-name">
                MOEMEN<span class="brand-badge">TEAM</span>
            </div>
            <div class="brand-sub">فريق مؤمن للتطوير البرمجي • الشريك التقني لإدارة وتأمين الأعمال</div>
            <div class="brand-meta">قسم البنية التحتية السحابية وإدارة خوادم البريد الإلكتروني الرسمي</div>
        </div>

        <div class="doc-badge-card">
            <div class="doc-badge-title">وثيقة تسليم المنظومة السحابية الرسمية</div>
            <div class="doc-badge-ref">كود الاعتماد: <span class="mono">MT-DELIV-ALBROLOSY-2026</span></div>
            <div class="doc-badge-date">تاريخ الإصدار: 24 أكتوبر 2026 • الحالة: مفعل ومعتمد</div>
        </div>
    </div>

    <div class="hero-banner">
        <h1>إشعار إتمام تسليم منظومة البريد السحابي (Cloud Email Infrastructure)</h1>
        <p>
            تتشرف إدارة <strong>فريق مؤمن للتطوير البرمجي (Moemen Team)</strong> بتسليم شركة البرلسي المحترمة كامل منظومة وصلاحيات البريد الإلكتروني السحابي المدار، المخصص حصرياً للنطاق <strong>albrolosy.com</strong>، بكافة معايير الحماية والتشفير والربط السحابي فائق السرعة.
        </p>
    </div>

    <!-- Official Credentials Card -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">🔑</div>
            <h2 class="section-title">بيانات الدخول والاعتماد الرسمية للمنظومة (Master Credentials)</h2>
        </div>

        <div class="credentials-card">
            <div class="credentials-card-header">
                <span>بيانات الحساب الإداري المخصص لشركة البرلسي</span>
                <span class="mono" style="font-size:10px; background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:4px;">CONFIDENTIAL / سرّي ومعتمد</span>
            </div>
            <table class="credentials-table">
                <tr>
                    <td class="cred-label">اسم العميل / الجهة:</td>
                    <td class="cred-value">شركة البرلسي (Albrolosy Trading & Contracting)</td>
                </tr>
                <tr>
                    <td class="cred-label">النطاق السحابي المخصص:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">albrolosy.com</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">رابط لوحة التحكم السحابية:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">https://email-service.moemen-team.tech/login</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">البريد الإلكتروني الإداري:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">albrolosymanagement@albrolosy.com</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">كلمة المرور الآمنة (Master Password):</td>
                    <td class="cred-value">
                        <span class="cred-box secret mono" style="font-size:13px; letter-spacing:0.5px;">Xzd@7Xy5ZqU6#Clrr3!</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">حد الحسابات البريدية:</td>
                    <td class="cred-value">
                        <strong>200 حساب بريد رسمي</strong> (قابلة للإنشاء والإدارة الفورية)
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">المساحة التخزينية المخصصة:</td>
                    <td class="cred-value">
                        <strong>512,000 MB</strong> (ما يعادل <strong>500 جيجابايت سحابية نقية</strong> قابلة للتوزيع على الصناديق)
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">رابط بريد الويب المباشر (Webmail):</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">https://mail.albrolosy.com/</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">خادم الاتصال المركزي الموحد:</td>
                    <td class="cred-value">
                        <span class="cred-box mono">mail.albrolosy.com</span> (مشفر ببروتوكولات SSL/TLS)
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 3 Key Metric Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-label">سعة الحسابات البريدية</div>
            <div class="stat-val highlight">200</div>
            <div class="stat-desc">صندوق بريدي كامل الصلاحيات</div>
        </div>
        <div class="stat-card primary">
            <div class="stat-label">المساحة السحابية الإجمالية</div>
            <div class="stat-val highlight">500 GB</div>
            <div class="stat-desc">512,000 ميجابايت مخصصة</div>
        </div>
        <div class="stat-card primary">
            <div class="stat-label">معدل الإرسال اليومي</div>
            <div class="stat-val highlight">9,600</div>
            <div class="stat-desc">رسالة / يومياً لكل صندوق بريد</div>
        </div>
    </div>

    <!-- Quick Security Note -->
    <div class="tips-card">
        <div class="tips-title">🔒 إرشادات هامة للحفاظ على أمان المنظومة:</div>
        <ul class="tips-list">
            <li>يُرجى حفظ بيانات الدخول أعلاه في مكان آمن وعدم مشاركة الحساب الإداري إلا مع المخولين بإدارة تقنية المعلومات بشركتكم.</li>
            <li>يمكن للموظفين استخدام بريد الويب مباشرة عبر <strong class="mono" dir="ltr">https://mail.albrolosy.com/</strong> باستخدام بريدهم وكلمة مرورهم الخاصة دون الحاجة للحساب الإداري.</li>
        </ul>
    </div>

    <!-- ==================== PAGE 2: CLOUD MANAGEMENT GUIDE ==================== -->
    <div class="page-break"></div>

    <div class="header">
        <div class="brand-block">
            <div class="brand-name">
                MOEMEN<span class="brand-badge">TEAM</span>
            </div>
            <div class="brand-sub">دليل تشغيل وإدارة المنظومة السحابية — شركة البرلسي</div>
        </div>
        <div class="doc-badge-card">
            <div class="doc-badge-title">دليل الإمكانيات والتحكم السحابي</div>
            <div class="doc-badge-ref">النطاق المدار: <span class="mono">albrolosy.com</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <div class="section-icon">🎛️</div>
            <h2 class="section-title">ما الذي يمكنك إدارته والتحكم به من خلال النظام السحابي؟</h2>
        </div>

        <div class="features-grid">
            <!-- Feature 1: Accounts -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">✉️</span>
                    <span class="feature-title">1. إدارة صناديق البريد الإلكتروني للموظفين (Email Accounts Management)</span>
                </div>
                <p class="feature-desc">
                    تمنحك لوحة التحكم إمكانية السيطرة الكاملة على كافة حسابات الموظفين دون الحاجة لأي تدخل فني:
                </p>
                <ul class="feature-steps">
                    <li><strong>إنشاء حساب بريد جديد:</strong> عبر الضغط على زر <em>"إنشاء بريد جديد"</em>، وكتابة اسم المستخدم (مثل: <code class="mono" dir="ltr">info</code>، <code class="mono" dir="ltr">sales</code>، <code class="mono" dir="ltr">ahmed</code>) وتخصيص المساحة المناسبة بالميجابايت.</li>
                    <li><strong>توليد كلمات مرور معقدة:</strong> بضغطة زر واحدة يقوم النظام بتوليد كلمة مرور قوية مكونة من رموز وأحرف وأرقام عشوائية مستحيلة التخمين.</li>
                    <li><strong>نسخ بيانات الاعتماد للموظف بنقرة واحدة (One-Click Share):</strong> بمجرد إنشاء الحساب يظهر لك زر <em>"نسخ بيانات البريد للموظف"</em> لينسخ رسالة نصية جاهزة ومنسقة يمكنك إرسالها للموظف فوراً عبر الواتساب أو الرسائل القصيرة تتضمن بريده، وكلمة مروره، ورابط الويب ميل، وإعدادات الموبايل.</li>
                    <li><strong>تصدير بطاقة الموظف الرسمية (PDF):</strong> يتيح لك النظام تحميل وثيقة PDF رسمية لكل موظف على حدة تشمل هويته، إعدادات خوادم الربط، وكلمة المرور الخاصة به.</li>
                    <li><strong>تعديل الحصص وكلمات المرور:</strong> إمكانية تغيير كلمة مرور أي موظف فوراً في حال نسيانها، أو زيادة مساحته التخزينية بنقرة واحدة.</li>
                    <li><strong>حذف أو تعطيل الصناديق:</strong> إمكانية إيقاف أو حذف أي صندوق عند مغادرة الموظف للشركة لتفريغ المساحة وإعادة استخدامها.</li>
                </ul>
            </div>

            <!-- Feature 2: Forwarders -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">🔄</span>
                    <span class="feature-title">2. قواعد إعادة توجيه البريد الذكية (Email Forwarders)</span>
                </div>
                <p class="feature-desc">
                    خدمة تحويل الرسائل التلقائي دون الحاجة لحجز مساحة تخزينية إضافية:
                </p>
                <ul class="feature-steps">
                    <li><strong>إنشاء عناوين وظيفية عامة:</strong> مثل <code class="mono" dir="ltr">contact@albrolosy.com</code> أو <code class="mono" dir="ltr">careers@albrolosy.com</code> وتوجيه كل ما يصل إليها تلقائياً إلى بريدك الشخصي على Gmail أو لعدة موظفين معاً في نفس اللحظة.</li>
                    <li><strong>التوجيه المتعدد:</strong> يمكنك وضع أكثر من بريد مفصولين بفاصلة لتقوم المنظومة بتوزيع النسخ فوراً لفريق العمل.</li>
                </ul>
            </div>

            <!-- Feature 3: Live Monitoring -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">📊</span>
                    <span class="feature-title">3. مراقبة المساحات والحصص السحابية المتبقية (Live Quota &amp; Storage Tracking)</span>
                </div>
                <p class="feature-desc">
                    مؤشرات ورسوم بيانية حية توضح بدقة:
                </p>
                <ul class="feature-steps">
                    <li><strong>عدد الصناديق المستهلكة:</strong> معرفة كم حساب تم تفعيله من أصل الـ 200 حساب المخصصة لك.</li>
                    <li><strong>الحصص الموزعة:</strong> متابعة إجمالي المساحات التي قمت بتوزيعها على الموظفين مقارنة بالـ 500 جيجابايت.</li>
                    <li><strong>الاستهلاك الفعلي للملفات:</strong> معرفة حجم الملفات والرسائل الفعلية المخزنة على الخادم بالـ Megabytes في الوقت الفعلي.</li>
                </ul>
            </div>

            <!-- Feature 4: Comprehensive PDF Export -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">📑</span>
                    <span class="feature-title">4. تصدير التقارير الإجمالية الشاملة (All Accounts Export)</span>
                </div>
                <p class="feature-desc">
                    إمكانية تصدير كشف كامل بجميع إيميلات الشركة في ملف PDF رسمي صادر من "مؤمن تيم" بضغطة زر واحدة، للرجوع إليه في عمليات الجرد والتدقيق الإداري والأرشفة الدورية.
                </p>
            </div>
        </div>
    </div>

    <!-- ==================== PAGE 3: DEVICE SETUP & INTEGRATION ==================== -->
    <div class="page-break"></div>

    <div class="header">
        <div class="brand-block">
            <div class="brand-name">
                MOEMEN<span class="brand-badge">TEAM</span>
            </div>
            <div class="brand-sub">إعدادات ربط البريد على الموبايل والبرامج — شركة البرلسي</div>
        </div>
        <div class="doc-badge-card">
            <div class="doc-badge-title">دليل الربط التقني المعتمد</div>
            <div class="doc-badge-ref">الخادم: <span class="mono">mail.albrolosy.com</span></div>
        </div>
    </div>

    <!-- Server Connection Table -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">🌐</div>
            <h2 class="section-title">إعدادات الخوادم الموحدة لربط الهواتف والبرامج (Outlook / iPhone / Android)</h2>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:25%;">الخدمة / البروتوكول</th>
                    <th style="width:35%;">اسم الخادم (Server Host)</th>
                    <th style="width:20%;">رقم المنفذ (Port)</th>
                    <th style="width:20%;">نوع التشفير والأمان</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>خادم البريد الوارد (IMAP)</strong> — مستحسن</td>
                    <td class="mono font-bold">mail.albrolosy.com</td>
                    <td class="mono font-bold" style="color:#047857;">993</td>
                    <td><strong>SSL / TLS</strong> (مشفر بالكامل)</td>
                </tr>
                <tr>
                    <td><strong>خادم البريد الصادر (SMTP)</strong></td>
                    <td class="mono font-bold">mail.albrolosy.com</td>
                    <td class="mono font-bold" style="color:#047857;">465</td>
                    <td><strong>SSL / TLS</strong> (مشفر بالكامل)</td>
                </tr>
                <tr>
                    <td><strong>خادم البريد الوارد البديل (POP3)</strong></td>
                    <td class="mono font-bold">mail.albrolosy.com</td>
                    <td class="mono font-bold" style="color:#047857;">995</td>
                    <td><strong>SSL / TLS</strong> (مشفر بالكامل)</td>
                </tr>
                <tr>
                    <td><strong>واجهة بريد الويب (Webmail)</strong></td>
                    <td class="mono font-bold">https://mail.albrolosy.com/</td>
                    <td class="mono font-bold">443 (HTTPS)</td>
                    <td>شهادة Let's Encrypt Enterprise</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Steps for Mobile Setup -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">📱</div>
            <h2 class="section-title">خطوات تشغيل البريد على هواتف الموظفين</h2>
        </div>

        <div style="display:flex; gap:10px;">
            <!-- iPhone -->
            <div style="flex:1; border:1px solid #E2E8F0; border-radius:10px; padding:10px; background:#FAFAFA;">
                <div style="font-weight:800; font-size:11px; color:#1E293B; margin-bottom:4px; display:flex; align-items:center; gap:5px;">
                    <span>🍎</span> هواتف آيفون (Apple Mail)
                </div>
                <ol style="margin:0; padding-right:16px; font-size:10px; color:#475569; line-height:1.5;">
                    <li>ادخل إلى <em>الإعدادات (Settings)</em> &gt; <em>تطبيق البريد (Mail)</em> &gt; <em>الحسابات (Accounts)</em>.</li>
                    <li>اضغط <em>إضافة حساب (Add Account)</em> &gt; اختر <strong>أخرى (Other)</strong> &gt; <strong>إضافة حساب بريد</strong>.</li>
                    <li>اكتب اسمك وبريدك الإلكتروني كاملاً وكلمة المرور.</li>
                    <li>اضبط الخادم الوارد والصادر على <strong class="mono" dir="ltr">mail.albrolosy.com</strong> واسم المستخدم هو بريدك كاملاً.</li>
                    <li>احفظ الحساب، وسيبدأ البريد بالعمل فوراً بشكل متزامن.</li>
                </ol>
            </div>

            <!-- Android -->
            <div style="flex:1; border:1px solid #E2E8F0; border-radius:10px; padding:10px; background:#FAFAFA;">
                <div style="font-weight:800; font-size:11px; color:#1E293B; margin-bottom:4px; display:flex; align-items:center; gap:5px;">
                    <span>🤖</span> هواتف أندرويد (Gmail App)
                </div>
                <ol style="margin:0; padding-right:16px; font-size:10px; color:#475569; line-height:1.5;">
                    <li>افتح تطبيق <em>Gmail</em> واضغط على صورة حسابك بالأعلى.</li>
                    <li>اختر <strong>إضافة حساب آخر (Add another account)</strong> &gt; اختر <strong>أخرى (Other)</strong>.</li>
                    <li>أدخل عنوان بريدك كاملاً، ثم اختر نوع الحساب <strong>IMAP</strong>.</li>
                    <li>أدخل كلمة المرور وخادم الوارد والصادر <strong class="mono" dir="ltr">mail.albrolosy.com</strong> مع تفعيل أمان SSL/TLS.</li>
                    <li>أكد الحفظ وسيعمل التطبيق بإشعارات فورية للرسائل.</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Security & SLA -->
    <div class="section" style="margin-top:14px;">
        <div class="section-header">
            <div class="section-icon">🛡️</div>
            <h2 class="section-title">معايير الأمان وموثوقية الإرسال (Enterprise SLA)</h2>
        </div>

        <div style="background-color:#F8FAFC; border:1px solid #CBD5E1; border-radius:10px; padding:10px 14px; font-size:10.5px; color:#334155; line-height:1.55;">
            <p style="margin:0 0 6px 0;">
                تم تجهيز منظومة بريد شركة البرلسي بأعلى البروتوكولات المعتمدة عالمياً لمنع الاحتيال وضمان وصول رسائلكم إلى صندوق الوارد (Inbox) لعملائكم مباشرة دون الوقوع في مجلد الرسائل غير المرغوب فيها (Spam):
            </p>
            <div style="display:flex; gap:12px; margin-top:6px;">
                <div style="flex:1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:6px 10px;">
                    <strong style="color:#047857; display:block; font-size:10.5px;">✓ توثيق SPF &amp; DKIM:</strong>
                    سجلات رقمية تثبت خروج الرسائل من خوادم البرلسي المعتمدة وتمنع تزوير هويتكم.
                </div>
                <div style="flex:1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:6px 10px;">
                    <strong style="color:#047857; display:block; font-size:10.5px;">✓ حماية DMARC:</strong>
                    سياسة تحقق عالمية تمنع انتحال النطاق albrolosy.com في أي مراسلات مشبوهة.
                </div>
                <div style="flex:1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:6px 10px;">
                    <strong style="color:#047857; display:block; font-size:10.5px;">✓ تشفير SSL/TLS 256-bit:</strong>
                    تشفير كامل لكافة الاتصالات والرسائل الصادرة والواردة بين أجهزتكم والخوادم.
                </div>
            </div>
        </div>
    </div>

    <!-- Official Seal & Sign-off -->
    <div class="footer-seal">
        <div class="footer-notice">
            <strong>إشعار قانوني وتقني:</strong> هذه الوثيقة تعتبر مستند تسليم رسمي صادر من إدارة فريق مؤمن للتطوير البرمجي (Moemen Team) لشركة البرلسي. تم ضبط كافة إعدادات DNS والحماية لتكون جاهزة للتشغيل البروداكشن بنسبة 100%. في حال وجود أي استفسار أو رغبة في الدعم التقني، يُرجى التواصل مباشرة مع فريق الدعم الفني لمؤمن تيم.
        </div>

        <div class="seal-signature-box">
            <div class="seal-stamp">
                ★ MOEMEN TEAM VERIFIED ★<br>
                معتمد ومسلّم رسمياً
            </div>
            <div class="seal-signer">فريق مؤمن للتطوير البرمجي</div>
            <div class="seal-title">Moemen Team for Software Development</div>
        </div>
    </div>

</div>

</body>
</html>
