<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Simple Member Cards</title>
    <style>
        @page {
            margin: 0.3in;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .member-card {
            width: 100%;
            height: 280px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .card-header {
            background: #011EB7;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .church-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .church-tagline {
            font-size: 9px;
            margin: 2px 0 0 0;
            opacity: 0.9;
        }

        .card-body {
            padding: 20px;
        }

        .member-name {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 5px 0;
            text-align: center;
        }

        .member-title {
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            margin: 0 0 20px 0;
            text-transform: uppercase;
            font-weight: 500;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 11px;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
        }

        .info-value {
            color: #374151;
            font-weight: 600;
            text-align: right;
        }

        .status-badge {
            background: #011EB7;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f9fafb;
            padding: 8px 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    @foreach($members as $pageIndex => $pageMembers)
        @if($pageIndex > 0)
            <div class="page-break"></div>
        @endif

        <div class="cards-grid">
            @foreach($pageMembers as $member)
                <div class="member-card">
                    <div class="card-header">
                        <div class="church-name">{{ $church_name }}</div>
                        <div class="church-tagline">Member ID Card</div>
                    </div>

                    <div class="card-body">
                        <div class="member-name">{{ $member->title }} {{ $member->full_name }}</div>
                        <div class="member-title">Church Member</div>

                        <ul class="info-list">
                            <li class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value">
                                    <span class="status-badge">{{ $member->membership_status }}</span>
                                </span>
                            </li>
                            <li class="info-item">
                                <span class="info-label">Branch:</span>
                                <span class="info-value">{{ $member->branch->name ?? 'Main Branch' }}</span>
                            </li>
                            @if($member->phone)
                            <li class="info-item">
                                <span class="info-label">Phone:</span>
                                <span class="info-value">+260{{ $member->phone }}</span>
                            </li>
                            @endif
                            @if($member->cellGroup)
                            <li class="info-item">
                                <span class="info-label">Cell Group:</span>
                                <span class="info-value">{{ $member->cellGroup->name }}</span>
                            </li>
                            @endif
                            @if($member->membership_date)
                            <li class="info-item">
                                <span class="info-label">Member Since:</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($member->membership_date)->format('M Y') }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="card-footer">
                        {{ $generated_at->format('Y') }} • {{ $church_name }}
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
