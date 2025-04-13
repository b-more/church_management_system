# resources/views/reports/offering-report.blade.php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offering Report - {{ $period }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            padding: 20px 0;
            border-bottom: 2px solid #011EB7;
            margin-bottom: 20px;
        }
        .church-name {
            font-size: 24px;
            font-weight: bold;
            color: #011EB7;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .summary-box {
            background: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }
        .currency {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="church-name">His Kingdom Church</div>
        <div>Meanwood Kwamwena Valley, Phase 1</div>
        <div>Contact: +260 978124541</div>
    </div>

    <div class="report-title">
        Offering Report<br>
        <span style="font-size: 14px">{{ $period }}</span>
    </div>

    <!-- Offering Summary by Type -->
    <div class="summary-box">
        <h3>Offerings by Type</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Offering Type</th>
                    <th class="currency">Amount (ZMW)</th>
                    <th>Count</th>
                    <th>% of Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offerings_by_type as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td class="currency">{{ number_format($type->total, 2) }}</td>
                    <td>{{ $type->count }}</td>
                    <td>{{ number_format($type->percentage, 1) }}%</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Offerings</td>
                    <td class="currency">{{ number_format($total_offerings, 2) }}</td>
                    <td>{{ $total_count }}</td>
                    <td>100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Daily Summary -->
    <div class="summary-box">
        <h3>Daily Summary</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Service Type</th>
                    <th class="currency">Amount (ZMW)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daily_summary as $day)
                <tr>
                    <td>{{ $day->date->format('d/m/Y') }}</td>
                    <td>{{ $day->service_type }}</td>
                    <td class="currency">{{ number_format($day->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>This is a computer-generated report and does not require a signature.</p>
    </div>
</body>
</html>
