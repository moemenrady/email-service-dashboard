<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Email Infrastructure Handover & Technical Administration Dossier — Albrolosy Company — Moemen Team</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            direction: ltr;
            text-align: left;
            margin: 0;
            padding: 20px;
            background-color: #FFFFFF;
            color: #111827;
            font-size: 11.5px;
            line-height: 1.55;
        }

        .mono {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
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
            font-family: 'Inter', sans-serif;
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
            text-align: left;
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
            text-align: left;
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
            width: 28%;
            color: #475569;
            font-weight: 700;
            background-color: #F1F5F9;
        }

        .cred-value {
            width: 72%;
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
            border-left: 4px solid #10B981;
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
            padding-left: 18px;
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
            text-align: left;
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
            padding-left: 16px;
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
            width: 220px;
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
            <span>Cloud Email Infrastructure Handover Certificate — Albrolosy Company</span>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('emails.index') }}" class="toolbar-btn btn-back">← Back to Portal</a>
            <button onclick="window.print()" class="toolbar-btn btn-print">🖨️ Print / Save as PDF</button>
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
            <div class="brand-sub">Moemen Team for Software Development • Cloud Infrastructure &amp; Security</div>
            <div class="brand-meta">Enterprise Cloud Email Management Division</div>
        </div>

        <div class="doc-badge-card">
            <div class="doc-badge-title">Official Service Delivery Certificate</div>
            <div class="doc-badge-ref">Certificate Ref: <span class="mono">MT-DELIV-ALBROLOSY-2026-EN</span></div>
            <div class="doc-badge-date">Issue Date: October 24, 2026 • Status: Fully Operational</div>
        </div>
    </div>

    <div class="hero-banner">
        <h1>Cloud Email Infrastructure Handover &amp; Activation Notice</h1>
        <p>
            The Management of <strong>Moemen Team for Software Development</strong> hereby certifies the official handover of the managed Enterprise Cloud Email Infrastructure to <strong>Albrolosy Company</strong>, exclusively provisioned for the domain <strong>albrolosy.com</strong>, configured with enterprise-grade encryption, high deliverability, and full white-label cloud administration.
        </p>
    </div>

    <!-- Official Credentials Card -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">🔑</div>
            <h2 class="section-title">Master Access &amp; Administrative Credentials</h2>
        </div>

        <div class="credentials-card">
            <div class="credentials-card-header">
                <span>Dedicated Administrative Account — Albrolosy Company</span>
                <span class="mono" style="font-size:10px; background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:4px;">CONFIDENTIAL / PRODUCTION CERTIFIED</span>
            </div>
            <table class="credentials-table">
                <tr>
                    <td class="cred-label">Client / Organization:</td>
                    <td class="cred-value">Albrolosy Company (Albrolosy Trading &amp; Contracting)</td>
                </tr>
                <tr>
                    <td class="cred-label">Assigned Custom Domain:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">albrolosy.com</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Cloud Portal Login URL:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">https://email-service.moemen-team.tech/login</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Administrative Email:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">albrolosymanagement@albrolosy.com</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Master Secure Password:</td>
                    <td class="cred-value">
                        <span class="cred-box secret mono" style="font-size:13px; letter-spacing:0.5px;">Xzd@7Xy5ZqU6#Clrr3!</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Email Accounts Limit:</td>
                    <td class="cred-value">
                        <strong>200 Dedicated Business Accounts</strong> (Instant creation &amp; allocation)
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Allocated Cloud Storage:</td>
                    <td class="cred-value">
                        <strong>512,000 MB</strong> (Equivalent to <strong>500 GB High-Performance Cloud Storage</strong>)
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Direct Webmail Interface:</td>
                    <td class="cred-value">
                        <span class="cred-box highlight mono">https://mail.albrolosy.com/</span>
                    </td>
                </tr>
                <tr>
                    <td class="cred-label">Centralized Mail Host:</td>
                    <td class="cred-value">
                        <span class="cred-box mono">mail.albrolosy.com</span> (SSL/TLS Encrypted)
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 3 Key Metric Cards -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-label">Total Mailbox Quota</div>
            <div class="stat-val highlight">200</div>
            <div class="stat-desc">Full Business Mailboxes</div>
        </div>
        <div class="stat-card primary">
            <div class="stat-label">Total Cloud Storage</div>
            <div class="stat-val highlight">500 GB</div>
            <div class="stat-desc">512,000 MB Dedicated Space</div>
        </div>
        <div class="stat-card primary">
            <div class="stat-label">Daily Sending Throughput</div>
            <div class="stat-val highlight">9,600</div>
            <div class="stat-desc">Emails / Day per Account</div>
        </div>
    </div>

    <!-- Quick Security Note -->
    <div class="tips-card">
        <div class="tips-title">🔒 Essential Security &amp; Operational Instructions:</div>
        <ul class="tips-list">
            <li>Keep the administrative credentials above strictly confidential. They provide complete control over user provisioning, quota distribution, and password resets.</li>
            <li>Employees do not require portal access to check their email. They can log in directly at <strong class="mono">https://mail.albrolosy.com/</strong> or connect their smartphones via standard IMAP/SMTP protocols.</li>
        </ul>
    </div>

    <!-- ==================== PAGE 2: CLOUD MANAGEMENT GUIDE ==================== -->
    <div class="page-break"></div>

    <div class="header">
        <div class="brand-block">
            <div class="brand-name">
                MOEMEN<span class="brand-badge">TEAM</span>
            </div>
            <div class="brand-sub">Cloud Administration &amp; Feature Walkthrough — Albrolosy Company</div>
        </div>
        <div class="doc-badge-card">
            <div class="doc-badge-title">Administrative Capabilities Guide</div>
            <div class="doc-badge-ref">Domain: <span class="mono">albrolosy.com</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <div class="section-icon">🎛️</div>
            <h2 class="section-title">What Can You Manage &amp; Control Through the Cloud Portal?</h2>
        </div>

        <div class="features-grid">
            <!-- Feature 1: Accounts -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">✉️</span>
                    <span class="feature-title">1. Employee Email Accounts Management (Full Self-Service CRUD)</span>
                </div>
                <p class="feature-desc">
                    The portal provides complete independence to manage company email accounts without technical dependencies:
                </p>
                <ul class="feature-steps">
                    <li><strong>Instant Account Provisioning:</strong> Click <em>"Create New Email"</em> to instantly provision a new mailbox (e.g. <code class="mono">info</code>, <code class="mono">sales</code>, <code class="mono">ahmed</code>) and allocate individual storage quotas.</li>
                    <li><strong>Auto-Generated Strong Passwords:</strong> A 1-click password generator instantly produces secure 16-character passwords containing symbols, letters, and numbers.</li>
                    <li><strong>1-Click Employee Credential Share:</strong> Upon account creation, click <em>"Copy Employee Share Message"</em> to copy a formatted text message ready to send via WhatsApp or SMS containing the employee's username, password, Webmail URL, and smartphone connection guides.</li>
                    <li><strong>Individual Account PDF Cards:</strong> Export an official individual PDF certificate for any specific employee detailing their login credentials and device setup parameters.</li>
                    <li><strong>Instant Quota &amp; Password Modification:</strong> Reset passwords in real-time if an employee forgets theirs, or expand their individual storage limit on demand.</li>
                    <li><strong>Mailbox Suspension &amp; Deletion:</strong> Suspend or remove inactive mailboxes upon employee departure to reclaim storage quotas immediately.</li>
                </ul>
            </div>

            <!-- Feature 2: Forwarders -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">🔄</span>
                    <span class="feature-title">2. Smart Email Forwarders &amp; Aliases</span>
                </div>
                <p class="feature-desc">
                    Route incoming emails efficiently without allocating mailbox storage:
                </p>
                <ul class="feature-steps">
                    <li><strong>Departmental &amp; Role-Based Aliases:</strong> Create public-facing aliases such as <code class="mono">contact@albrolosy.com</code> or <code class="mono">careers@albrolosy.com</code> that automatically forward all incoming messages directly to personal Gmail or management inboxes.</li>
                    <li><strong>Multi-Destination Forwarding:</strong> Forward incoming messages to multiple destination emails simultaneously with comma-separated routing.</li>
                </ul>
            </div>

            <!-- Feature 3: Live Monitoring -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">📊</span>
                    <span class="feature-title">3. Live Quota &amp; Cloud Storage Monitoring</span>
                </div>
                <p class="feature-desc">
                    Real-time visual telemetry to track resources and prevent disruptions:
                </p>
                <ul class="feature-steps">
                    <li><strong>Mailbox Consumption Gauge:</strong> View active accounts created versus your 200-mailbox capacity.</li>
                    <li><strong>Distributed Quota Monitoring:</strong> Track cumulative storage allocated across all active mailboxes against your 500 GB total pool.</li>
                    <li><strong>Actual File Disk Usage:</strong> Real-time tracking of true disk utilization (in MB) reflecting stored emails and attachments on the server.</li>
                </ul>
            </div>

            <!-- Feature 4: Comprehensive PDF Export -->
            <div class="feature-item accent">
                <div class="feature-head">
                    <span style="font-size:14px;">📑</span>
                    <span class="feature-title">4. Comprehensive Enterprise PDF Reports</span>
                </div>
                <p class="feature-desc">
                    Export a full census report of all company mailboxes in an official Moemen Team branded PDF document with a single click, ideal for management reviews, auditing, and corporate archival.
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
            <div class="brand-sub">Device &amp; App Integration Guide — Albrolosy Company</div>
        </div>
        <div class="doc-badge-card">
            <div class="doc-badge-title">Standardized Connection Parameters</div>
            <div class="doc-badge-ref">Host: <span class="mono">mail.albrolosy.com</span></div>
        </div>
    </div>

    <!-- Server Connection Table -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">🌐</div>
            <h2 class="section-title">Universal Server Connection Parameters (Outlook, iPhone, Android, Thunderbird)</h2>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:25%;">Protocol / Service</th>
                    <th style="width:35%;">Server Hostname</th>
                    <th style="width:20%;">Port</th>
                    <th style="width:20%;">Encryption Type</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Incoming Mail (IMAP)</strong> — Recommended</td>
                    <td class="mono font-bold">mail.albrolosy.com</td>
                    <td class="mono font-bold" style="color:#047857;">993</td>
                    <td><strong>SSL / TLS</strong> (Mandatory)</td>
                </tr>
                <tr>
                    <td><strong>Outgoing Mail (SMTP)</strong></td>
                    <td class="mono font-bold">mail.albrolosy.com</td>
                    <td class="mono font-bold" style="color:#047857;">465</td>
                    <td><strong>SSL / TLS</strong> (Mandatory)</td>
                </tr>
                <tr>
                    <td><strong>Incoming Mail (POP3)</strong> — Optional</td>
                    <td class="mono font-bold">mail.albrolosy.com</td>
                    <td class="mono font-bold" style="color:#047857;">995</td>
                    <td><strong>SSL / TLS</strong> (Mandatory)</td>
                </tr>
                <tr>
                    <td><strong>Webmail Direct Portal</strong></td>
                    <td class="mono font-bold">https://mail.albrolosy.com/</td>
                    <td class="mono font-bold">443 (HTTPS)</td>
                    <td>Let's Encrypt Enterprise SSL</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Steps for Mobile Setup -->
    <div class="section">
        <div class="section-header">
            <div class="section-icon">📱</div>
            <h2 class="section-title">Quick Smartphone Setup Walkthrough</h2>
        </div>

        <div style="display:flex; gap:10px;">
            <!-- iPhone -->
            <div style="flex:1; border:1px solid #E2E8F0; border-radius:10px; padding:10px; background:#FAFAFA;">
                <div style="font-weight:800; font-size:11px; color:#1E293B; margin-bottom:4px; display:flex; align-items:center; gap:5px;">
                    <span>🍎</span> Apple iPhone (Default Mail App)
                </div>
                <ol style="margin:0; padding-left:16px; font-size:10px; color:#475569; line-height:1.5;">
                    <li>Navigate to <em>Settings</em> &gt; <em>Mail</em> &gt; <em>Accounts</em>.</li>
                    <li>Tap <em>Add Account</em> &gt; select <strong>Other</strong> &gt; <strong>Add Mail Account</strong>.</li>
                    <li>Enter your Name, Full Email Address, and Password.</li>
                    <li>Set Incoming and Outgoing Hostname to <strong class="mono">mail.albrolosy.com</strong>. Username is your full email address.</li>
                    <li>Save to initiate immediate bidirectional synchronization.</li>
                </ol>
            </div>

            <!-- Android -->
            <div style="flex:1; border:1px solid #E2E8F0; border-radius:10px; padding:10px; background:#FAFAFA;">
                <div style="font-weight:800; font-size:11px; color:#1E293B; margin-bottom:4px; display:flex; align-items:center; gap:5px;">
                    <span>🤖</span> Android Smartphones (Gmail App)
                </div>
                <ol style="margin:0; padding-left:16px; font-size:10px; color:#475569; line-height:1.5;">
                    <li>Open <em>Gmail</em> and tap your profile avatar in the upper right.</li>
                    <li>Select <strong>Add another account</strong> &gt; choose <strong>Other</strong>.</li>
                    <li>Enter your full email address, then select <strong>Personal (IMAP)</strong>.</li>
                    <li>Enter your password and configure Server to <strong class="mono">mail.albrolosy.com</strong> with SSL/TLS security.</li>
                    <li>Confirm permissions to enable instant push notifications.</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Security & SLA -->
    <div class="section" style="margin-top:14px;">
        <div class="section-header">
            <div class="section-icon">🛡️</div>
            <h2 class="section-title">Enterprise Security Protocols &amp; SLA Commitment</h2>
        </div>

        <div style="background-color:#F8FAFC; border:1px solid #CBD5E1; border-radius:10px; padding:10px 14px; font-size:10.5px; color:#334155; line-height:1.55;">
            <p style="margin:0 0 6px 0;">
                The email infrastructure provisioned for Albrolosy Company adheres to globally recognized deliverability standards to maximize inbox placement and prevent spoofing:
            </p>
            <div style="display:flex; gap:12px; margin-top:6px;">
                <div style="flex:1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:6px 10px;">
                    <strong style="color:#047857; display:block; font-size:10.5px;">✓ SPF &amp; DKIM Hardened:</strong>
                    Cryptographic digital signatures prove message authenticity and shield your domain from fraudulent impersonation.
                </div>
                <div style="flex:1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:6px 10px;">
                    <strong style="color:#047857; display:block; font-size:10.5px;">✓ DMARC Policy Protection:</strong>
                    Prevents phishing and guarantees recipient mail servers recognize legitimate Albrolosy communications.
                </div>
                <div style="flex:1; background:#FFFFFF; border:1px solid #E2E8F0; border-radius:8px; padding:6px 10px;">
                    <strong style="color:#047857; display:block; font-size:10.5px;">✓ 256-bit SSL/TLS Transport:</strong>
                    Complete end-to-end transport encryption safeguarding messages in transit from interception.
                </div>
            </div>
        </div>
    </div>

    <!-- Official Seal & Sign-off -->
    <div class="footer-seal">
        <div class="footer-notice">
            <strong>Legal &amp; Technical Warranty:</strong> This document constitutes an official service delivery certificate executed by Moemen Team for Software Development for Albrolosy Company. All DNS routing and infrastructure records are production-verified. For technical inquiries or dedicated support, contact Moemen Team support directly.
        </div>

        <div class="seal-signature-box">
            <div class="seal-stamp">
                ★ MOEMEN TEAM VERIFIED ★<br>
                OFFICIALLY HANDED OVER &amp; ACTIVE
            </div>
            <div class="seal-signer">Moemen Team for Software Development</div>
            <div class="seal-title">Cloud Infrastructure &amp; Engineering</div>
        </div>
    </div>

</div>

</body>
</html>
