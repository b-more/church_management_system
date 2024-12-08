<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0.5cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #1a202c;
            margin: 0;
            padding: 10px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #2b6cb0;
            padding-bottom: 10px;
        }

        .logo-container {
            display: table-cell;
            width: 150px;
            vertical-align: middle;
        }

        .church-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }

        .church-name {
            font-size: 20pt;
            font-weight: bold;
            color: #2b6cb0;
            margin-bottom: 4px;
        }

        .church-contact {
            font-size: 8pt;
            line-height: 1.3;
            color: #4a5568;
        }

        .report-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            color: #2d3748;
            margin: 15px 0 2px;
        }

        .report-date {
            text-align: center;
            font-size: 10pt;
            color: #4a5568;
            margin-bottom: 15px;
        }

        .section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #2b6cb0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }

        .info-row {
            margin-bottom: 4px;
        }

        .label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            color: #4a5568;
        }

        .value {
            color: #2d3748;
        }

        .attendance-grid {
            display: table;
            width: 100%;
            margin-top: 6px;
            border-collapse: separate;
            border-spacing: 4px;
        }

        .stat-box {
            display: table-cell;
            background: #f7fafc;
            padding: 6px;
            border-radius: 4px;
            text-align: center;
            width: 20%;
        }

        .stat-label {
            font-size: 8pt;
            color: #4a5568;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 12pt;
            font-weight: bold;
            color: #2b6cb0;
        }

        .financial-summary {
            background: #f8fafc;
            padding: 8px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .amount-row {
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dotted #e2e8f0;
            padding-bottom: 2px;
        }

        .amount-row:last-child {
            border-bottom: none;
            font-weight: bold;
        }

        .notes {
            background: #f8fafc;
            padding: 8px;
            border-radius: 4px;
            font-style: italic;
            color: #4a5568;
            font-size: 8pt;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            padding-left: 15px;
            padding-right: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/black_logo.png') }}" width="140" height="140">
        </div>
        <div class="church-info">
            <div class="church-name">His Kingdom Church</div>
            <div class="church-contact">
                Meanwood Kwamwena Valley, Phase 1<br>
                Along Police Road, Lusaka Zambia<br>
                Contact: 0978124541 | 0978353364<br>
                Email: info@hkc.co.zm
            </div>
        </div>
    </div>

    <div class="report-title">Service Report</div>
    <div class="report-date">{{ $service->date->format('l, F j, Y') }}</div>

    <div class="section">
        <div class="section-title">Service Details</div>
        <div class="info-row">
            <span class="label">Service Type:</span>
            <span class="value">{{ $service->service_type }}</span>
        </div>
        <div class="info-row">
            <span class="label">Time:</span>
            <span class="value">{{ $service->start_time->format('g:i A') }} - {{ $service->end_time->format('g:i A') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Service Leaders</div>
        <div class="info-row">
            <span class="label">Service Host:</span>
            <span class="value">{{ optional($service->host)->title }} {{ optional($service->host)->first_name }} {{ optional($service->host)->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Preacher:</span>
            <span class="value">
                @if($service->preacher_type === 'visiting')
                    {{ $service->visiting_preacher_name }} ({{ $service->visiting_preacher_church }})
                @else
                    {{ optional($service->preacher)->title }} {{ optional($service->preacher)->first_name }} {{ optional($service->preacher)->last_name }}
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="label">Worship Leader:</span>
            <span class="value">{{ optional($service->worshipLeader)->first_name }} {{ optional($service->worshipLeader)->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Intercession:</span>
            <span class="value">{{ optional($service->intercessionLeader)->first_name }} {{ optional($service->intercessionLeader)->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Offering Exhort.:</span>
            <span class="value">{{ optional($service->offeringExhortationLeader)->first_name }} {{ optional($service->offeringExhortationLeader)->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Sunday School:</span>
            <span class="value">{{ optional($service->sundaySchoolTeacher)->first_name }} {{ optional($service->sundaySchoolTeacher)->last_name }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Message Details</div>
        <div class="info-row">
            <span class="label">Title:</span>
            <span class="value">{{ $service->message_title }}</span>
        </div>
        <div class="info-row">
            <span class="label">Bible Reading:</span>
            <span class="value">{{ $service->bible_reading }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Attendance & Financial Summary</div>
        <div class="attendance-grid">
            <div class="stat-box">
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ number_format($service->total_attendance) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Members</div>
                <div class="stat-value">{{ number_format($service->total_members) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Children</div>
                <div class="stat-value">{{ number_format($service->total_children) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Visitors</div>
                <div class="stat-value">{{ number_format($service->total_visitors) }}</div>
            </div>
        </div>

        <div class="financial-summary">
            <div class="amount-row">
                <span>Offering Amount:</span>
                <span>ZMW{{ number_format($service->offering_amount, 2) }}</span>
            </div>
            <div class="amount-row">
                <span>Tithe Amount:</span>
                <span>ZMW{{ number_format($service->tithe_amount, 2) }}</span>
            </div>
            <div class="amount-row">
                <span>Total Collection:</span>
                <span>ZMW{{ number_format($service->offering_amount + $service->tithe_amount, 2) }}</span>
            </div>
        </div>
    </div>

    @if($service->notes)
    <div class="section">
        <div class="section-title">Additional Notes</div>
        <div class="notes">{{ $service->notes }}</div>
    </div>
    @endif

    <div class="footer">
        Report Generated: {{ now()->format('F j, Y \a\t g:i A') }} | Report ID: {{ str_pad($service->id, 6, '0', STR_PAD_LEFT) }} | Generated by: {{ auth()->user()->name }}
    </div>
</body>
</html>