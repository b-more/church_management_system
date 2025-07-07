<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Name Badges</title>
    <style>
        @page {
            margin: 0.2in;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .name-badge {
            width: 100%;
            height: 200px;
            border: 3px solid #011EB7;
            border-radius: 12px;
            background: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .badge-header {
            background: linear-gradient(135deg, #011EB7 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 12px 8px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .badge-body {
            padding: 15px 10px;
            text-align: center;
            height: calc(100% - 50px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .member-name-large {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .member-role {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .member-branch {
            font-size: 10px;
            color: #374151;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 8px;
            display: inline-block;
        }

        .corner-decoration {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 20px;
            height: 20px;
            background: #011EB7;
            opacity: 0.1;
            border-radius: 50%;
        }

        .page-break {
            page-break-before: always;
        }

        /* Lanyard hole */
        .badge-hole {
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            border: 2px solid #011EB7;
        }

        /* VIP Badge Style for Leaders and Overseers */
        .badge-vip {
            border-color: #dc2626;
        }

        .badge-vip .badge-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .badge-vip .badge-hole {
            border-color: #dc2626;
        }

        .badge-vip .corner-decoration {
            background: #dc2626;
        }
    </style>
</head>
<body>
    @foreach($members as $pageIndex => $pageMembers)
        @if($pageIndex > 0)
            <div class="page-break"></div>
        @endif

        <div class="badges-grid">
            @foreach($pageMembers as $member)
                <div class="name-badge {{ in_array($member->membership_status, ['Pastor', 'Leader', 'Overseer']) ? 'badge-vip' : '' }}">
                    <div class="badge-hole"></div>

                    <div class="badge-header">
                        {{ $church_name }}
                    </div>

                    <div class="badge-body">
                        <div class="member-name-large">{{ $member->title }} {{ $member->full_name }}</div>
                        <div class="member-role">{{ $member->membership_status }}</div>
                        <div class="member-branch">{{ $member->branch->name ?? 'Main Branch' }}</div>
                        <div class="corner-decoration"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
