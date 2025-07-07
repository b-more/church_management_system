<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Certificates</title>
    <style>
        @page {
            margin: 0.5in;
            size: A4 landscape;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }

        .certificate {
            width: 100%;
            min-height: 500px;
            border: 5px solid #011EB7;
            background: white;
            position: relative;
            page-break-after: always;
            margin-bottom: 20px;
            padding: 30px;
        }

        .certificate:last-child {
            page-break-after: avoid;
        }

        .certificate-inner {
            border: 2px solid #E0B041;
            padding: 40px;
            height: 100%;
            text-align: center;
        }

        .church-name {
            font-size: 24px;
            font-weight: bold;
            color: #011EB7;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .church-address {
            font-size: 12px;
            color: #666;
            margin-bottom: 30px;
        }

        .certificate-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 30px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #E0B041;
            padding-bottom: 10px;
        }

        .certificate-text {
            font-size: 14px;
            color: #333;
            margin: 20px 0;
            line-height: 1.6;
        }

        .member-name {
            font-size: 24px;
            font-weight: bold;
            color: #011EB7;
            margin: 20px 0;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            display: inline-block;
        }

        .certificate-details {
            font-size: 12px;
            color: #666;
            margin: 30px 0;
        }

        .signatures {
            margin-top: 60px;
            overflow: hidden;
        }

        .signature-left {
            float: left;
            width: 200px;
            text-align: center;
        }

        .signature-right {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 30px;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 11px;
            color: #666;
            font-weight: bold;
        }

        .verse {
            font-style: italic;
            font-size: 11px;
            color: #888;
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }

        .seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 80px;
            height: 80px;
            border: 2px solid #E0B041;
            border-radius: 50%;
            text-align: center;
            padding: 20px 10px;
            font-size: 9px;
            font-weight: bold;
            color: #E0B041;
        }

        /* Certificate type specific colors */
        .baptism .certificate {
            border-color: #3b82f6;
        }

        .baptism .certificate-title,
        .baptism .member-name {
            color: #3b82f6;
        }

        .dedication .certificate {
            border-color: #10b981;
        }

        .dedication .certificate-title,
        .dedication .member-name {
            color: #10b981;
        }
    </style>
</head>
<body>
    @foreach($members as $member)
        <div class="{{ $certificate_type }}">
            <div class="certificate">
                <div class="certificate-inner">
                    <div class="church-name">{{ $church_name }}</div>
                    <div class="church-address">{{ $church_address }}</div>

                    <div class="certificate-title">
                        @if($certificate_type === 'baptism')
                            Certificate of Baptism
                        @elseif($certificate_type === 'dedication')
                            Certificate of Dedication
                        @else
                            Certificate of Membership
                        @endif
                    </div>

                    <div class="certificate-text">
                        This is to certify that
                    </div>

                    <div class="member-name">{{ $member->title }} {{ $member->first_name }} {{ $member->last_name }}</div>

                    <div class="certificate-text">
                        @if($certificate_type === 'baptism')
                            was baptized in the name of the Father, and of the Son, and of the Holy Spirit
                            @if($member->baptism_date)
                                on {{ date('F j, Y', strtotime($member->baptism_date)) }}
                            @endif
                            in accordance with the command of our Lord Jesus Christ.
                        @elseif($certificate_type === 'dedication')
                            has been dedicated to the service of the Lord
                            @if($member->membership_date)
                                on {{ date('F j, Y', strtotime($member->membership_date)) }}
                            @endif
                            and has committed to serve faithfully in His Kingdom.
                        @else
                            has been received as a member in good standing of {{ $church_name }}
                            @if($member->membership_date)
                                on {{ date('F j, Y', strtotime($member->membership_date)) }}
                            @endif
                            and is entitled to all privileges and responsibilities thereof.
                        @endif
                    </div>

                    <div class="certificate-details">
                        <strong>Branch:</strong> {{ $member->branch->name ?? 'Main Branch' }}<br>
                        @if($member->cellGroup)
                            <strong>Cell Group:</strong> {{ $member->cellGroup->name }}<br>
                        @endif
                        <strong>Membership Status:</strong> {{ $member->membership_status }}
                    </div>

                    <div class="signatures">
                        <div class="signature-left">
                            <div class="signature-line"></div>
                            <div class="signature-title">Pastor<br>{{ $pastor_name }}</div>
                        </div>

                        <div class="signature-right">
                            <div class="signature-line"></div>
                            <div class="signature-title">Date<br>{{ date('F j, Y') }}</div>
                        </div>
                    </div>

                    <div class="verse">
                        @if($certificate_type === 'baptism')
                            "Therefore we were buried with Him through baptism into death..." - Romans 6:4
                        @elseif($certificate_type === 'dedication')
                            "Present your bodies a living sacrifice, holy, acceptable to God..." - Romans 12:1
                        @else
                            "For where two or three are gathered together in My name, I am there..." - Matthew 18:20
                        @endif
                    </div>

                    <div class="seal">
                        OFFICIAL<br>CHURCH<br>SEAL
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
