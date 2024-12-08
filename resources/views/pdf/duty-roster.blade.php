<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #1a202c;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #2b6cb0;
            padding-bottom: 10px;
        }

        .logo-container {
            display: table-cell;
            width: 100px;
            vertical-align: top;
        }

        .church-info {
            display: table-cell;
            vertical-align: top;
            padding-left: 20px;
        }

        .church-name {
            font-size: 24pt;
            font-weight: bold;
            color: #2b6cb0;
            margin-bottom: 5px;
        }

        .church-contact {
            font-size: 9pt;
            line-height: 1.3;
            color: #4a5568;
        }

        .roster-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #2d3748;
            margin: 20px 0;
        }

        .service-info {
            background: #f7fafc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .info-row {
            margin-bottom: 5px;
            display: table;
            width: 100%;
        }

        .label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            color: #4a5568;
        }

        .value {
            display: table-cell;
            color: #2d3748;
        }

        .roster-section {
            margin-bottom: 20px;
        }

        .roster-section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2b6cb0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background-color: #f7fafc;
            font-weight: bold;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/logo.png') }}" width="80" height="80">
        </div>
        <div class="church-info">
            <div class="church-name">{{ $roster->branch->name }}</div>
            <div class="church-contact">
                {{ $roster->branch->address }}<br>
                Contact: {{ $roster->branch->phone }}<br>
                Email: {{ $roster->branch->email }}
            </div>
        </div>
    </div>

    <div class="roster-title">Service Duty Roster</div>

    <div class="service-info">
        <div class="info-row">
            <span class="label">Service Date:</span>
            <span class="value">{{ $roster->service_date->format('l, F j, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Service Type:</span>
            <span class="value">{{ $roster->service_type }}</span>
        </div>
        <div class="info-row">
            <span class="label">Service Time:</span>
            <span class="value">{{ $roster->service_time->format('g:i A') }}</span>
        </div>
    </div>

    <div class="roster-section">
        <div class="roster-section-title">Service Leadership</div>
        <table>
            <tr>
                <th width="40%">Role</th>
                <th width="60%">Assigned Person</th>
            </tr>
            <tr>
                <td>Service Host</td>
                <td>{{ optional($roster->serviceHost)->title }} {{ optional($roster->serviceHost)->first_name }} {{ optional($roster->serviceHost)->last_name }}</td>
            </tr>
            <tr>
                <td>Worship Leader</td>
                <td>{{ optional($roster->worshipLeader)->title }} {{ optional($roster->worshipLeader)->first_name }} {{ optional($roster->worshipLeader)->last_name }}</td>
            </tr>
            <tr>
                <td>Intercession Leader</td>
                <td>{{ optional($roster->intercessionLeader)->title }} {{ optional($roster->intercessionLeader)->first_name }} {{ optional($roster->intercessionLeader)->last_name }}</td>
            </tr>
            <tr>
                <td>Exhortation Leader</td>
                <td>{{ optional($roster->exhortationLeader)->title }} {{ optional($roster->exhortationLeader)->first_name }} {{ optional($roster->exhortationLeader)->last_name }}</td>
            </tr>
            <tr>
                <td>Sunday School Teacher</td>
                <td>{{ optional($roster->sundaySchoolTeacher)->title }} {{ optional($roster->sundaySchoolTeacher)->first_name }} {{ optional($roster->sundaySchoolTeacher)->last_name }}</td>
            </tr>
            <tr>
                <td>Announcer</td>
                <td>{{ optional($roster->announcer)->title }} {{ optional($roster->announcer)->first_name }} {{ optional($roster->announcer)->last_name }}</td>
            </tr>
            <tr>
                <td>Special Song Singer</td>
                <td>{{ optional($roster->specialSongSinger)->title }} {{ optional($roster->specialSongSinger)->first_name }} {{ optional($roster->specialSongSinger)->last_name }}</td>
            </tr>
        </table>
    </div>

    <div class="roster-section">
        <div class="roster-section-title">Preacher Information</div>
        <div class="info-row">
            <span class="label">Preacher Type:</span>
            <span class="value">{{ ucfirst($roster->preacher_type) }} Preacher</span>
        </div>
        @if($roster->preacher_type === 'local')
            <div class="info-row">
                <span class="label">Preacher:</span>
                <span class="value">{{ optional($roster->preacher)->title }} {{ optional($roster->preacher)->first_name }} {{ optional($roster->preacher)->last_name }}</span>
            </div>
        @else
            <div class="info-row">
                <span class="label">Visiting Preacher:</span>
                <span class="value">{{ $roster->visiting_preacher_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Church:</span>
                <span class="value">{{ $roster->visiting_preacher_church }}</span>
            </div>
        @endif
    </div>

    @if($roster->notes)
    <div class="roster-section">
        <div class="roster-section-title">Additional Notes</div>
        <div style="padding: 10px; background: #f7fafc; border-radius: 5px;">
            {{ $roster->notes }}
        </div>
    </div>
    @endif

    <div class="footer">
        Roster Status: {{ ucfirst($roster->status) }} | Generated: {{ now()->format('F j, Y \a\t g:i A') }} 
        | ID: {{ str_pad($roster->id, 6, '0', STR_PAD_LEFT) }}
    </div>
</body>
</html>