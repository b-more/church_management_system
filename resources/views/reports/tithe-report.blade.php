# resources/views/reports/tithe-report.blade.php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tithe Report - {{ $period }}</title>
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
        Tithe Report<br>
        <span style="font-size: 14px">{{ $period }}</span>
    </div>

    <!-- Tithe Summary -->
    <div class="summary-box">
        <table class="table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Member ID</th>
                    <th class="currency">Amount (ZMW)</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tithes as $tithe)
                <tr>
                    <td>{{ $tithe->member->full_name }}</td>
                    <td>{{ $tithe->member->registration_number }}</td>
                    <td class="currency">{{ number_format($tithe->amount, 2) }}</td>
                    <td>{{ $tithe->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $tithe->payment_method }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">Total Tithes</td>
                    <td class="currency">{{ number_format($total_tithes, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Statistics -->
    <div class="summary-box">
        <h3>Statistics</h3>
        <table class="table">
            <tr>
                <td>Total Members Who Tithed</td>
                <td>{{ $stats['total_tithers'] }}</td>
            </tr>
            <tr>
                <td>Average Tithe Amount</td>
                <td>ZMW {{ number_format($stats['average_tithe'], 2) }}</td>
            </tr>
            <tr>
                <td>Highest Tithe</td>
                <td>ZMW {{ number_format($stats['highest_tithe'], 2) }}</td>
            </tr>
            <tr>
                <td>Most Common Payment Method</td>
                <td>{{ $stats['common_payment_method'] }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>This is a computer-generated report and does not require a signature.</p>
    </div>
</body>
</html>
