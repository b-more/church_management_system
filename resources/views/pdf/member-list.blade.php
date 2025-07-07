<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0.5in;
            @top-center {
                content: "{{ $church_name }} - {{ $title }}";
            }
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
            }
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: 60px;
        }

        .church-info {
            margin-top: 10px;
        }

        .church-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .church-details {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.3;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin: 15px 0 5px 0;
        }

        .report-meta {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .members-table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px 4px;
            font-weight: bold;
            font-size: 9px;
            text-align: left;
        }

        .members-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 4px;
            font-size: 8px;
            vertical-align: top;
        }

        .members-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .member-name {
            font-weight: bold;
            color: #374151;
        }

        .member-reg {
            font-size: 7px;
            color: #6b7280;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }

        .status-first-timer { background-color: #f3f4f6; color: #374151; }
        .status-new-convert { background-color: #dbeafe; color: #1e40af; }
        .status-regular { background-color: #d1fae5; color: #065f46; }
        .status-leader { background-color: #fecaca; color: #991b1b; }
        .status-pastor { background-color: #d1fae5; color: #065f46; }
        .status-kingdom-worker { background-color: #fef3c7; color: #92400e; }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }

        .summary-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .summary-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .summary-stats {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-number {
            font-weight: bold;
            font-size: 12px;
            color: #1e40af;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header with Church Info -->
    <div class="header">
        @if(file_exists($logo_path))
            <img src="{{ $logo_path }}" alt="Church Logo" class="logo">
        @endif

        <div class="church-info">
            <div class="church-name">{{ $church_name }}</div>
            <div class="church-details">
                {{ $church_address }}<br>
                Phone: {{ $church_phone }} | Email: {{ $church_email }}
            </div>
        </div>

        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">
            Generated on {{ $generated_at->format('F j, Y \a\t g:i A') }} |
            Total Members: {{ $total_count }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-box">
        <div class="summary-title">Summary Statistics</div>
        <div class="summary-stats">
            <div class="summary-item">
                <div class="summary-number">{{ $members->where('is_active', true)->count() }}</div>
                <div>Active</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $members->where('gender', 'Male')->count() }}</div>
                <div>Male</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $members->where('gender', 'Female')->count() }}</div>
                <div>Female</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $members->whereNotNull('baptism_date')->count() }}</div>
                <div>Baptized</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $members->whereIn('membership_status', ['Leader', 'Pastor'])->count() }}</div>
                <div>Leaders</div>
            </div>
        </div>
    </div>

    <!-- Members Table -->
    <table class="members-table">
        <thead>
            <tr>
                <th style="width: 15%">Registration</th>
                <th style="width: 20%">Name</th>
                <th style="width: 8%">Gender</th>
                <th style="width: 8%">Age</th>
                @if($include_contact)
                <th style="width: 15%">Phone</th>
                @endif
                <th style="width: 12%">Status</th>
                <th style="width: 12%">Branch</th>
                <th style="width: 10%">Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
            <tr>
                <td>
                    <div class="member-reg">{{ $member->registration_number }}</div>
                </td>
                <td>
                    <div class="member-name">{{ $member->title }} {{ $member->full_name }}</div>
                    @if($include_contact && $member->email)
                        <div style="font-size: 7px; color: #6b7280;">{{ $member->email }}</div>
                    @endif
                </td>
                <td>{{ $member->gender }}</td>
                <td>
                    @if($member->date_of_birth)
                        {{ \Carbon\Carbon::parse($member->date_of_birth)->age }}
                    @endif
                </td>
                @if($include_contact)
                <td>
                    @if($member->phone)
                        +260{{ $member->phone }}
                    @endif
                </td>
                @endif
                <td>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $member->membership_status)) }}">
                        {{ $member->membership_status }}
                    </span>
                </td>
                <td>{{ $member->branch->name ?? 'N/A' }}</td>
                <td>
                    @if($member->membership_date)
                        {{ \Carbon\Carbon::parse($member->membership_date)->format('M Y') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This document is confidential and intended for church administration purposes only.</p>
        <p>{{ $church_name }} | Generated {{ $generated_at->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
