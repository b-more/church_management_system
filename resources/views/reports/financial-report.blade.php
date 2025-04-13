# resources/views/reports/financial-report.blade.php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report - {{ $period }}</title>
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
        Financial Report<br>
        <span style="font-size: 14px">{{ $period }}</span>
    </div>

    <!-- Income Summary -->
    <div class="summary-box">
        <h3>Income Summary</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="currency">Amount (ZMW)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tithes</td>
                    <td class="currency">{{ number_format($summary['tithe'], 2) }}</td>
                </tr>
                <tr>
                    <td>Offerings</td>
                    <td class="currency">{{ number_format($summary['offering'], 2) }}</td>
                </tr>
                <tr>
                    <td>Special Offerings</td>
                    <td class="currency">{{ number_format($summary['special_offering'], 2) }}</td>
                </tr>
                <tr>
                    <td>Building Fund</td>
                    <td class="currency">{{ number_format($summary['building_fund'], 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Income</td>
                    <td class="currency">{{ number_format($summary['total_income'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Expense Summary -->
    <div class="summary-box">
        <h3>Expense Summary</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="currency">Amount (ZMW)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->category }}</td>
                    <td class="currency">{{ number_format($expense->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Expenses</td>
                    <td class="currency">{{ number_format($summary['total_expenses'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Net Position -->
    <div class="summary-box">
        <h3>Net Position</h3>
        <table class="table">
            <tr>
                <td>Total Income</td>
                <td class="currency">{{ number_format($summary['total_income'], 2) }}</td>
            </tr>
            <tr>
                <td>Total Expenses</td>
                <td class="currency">({{ number_format($summary['total_expenses'], 2) }})</td>
            </tr>
            <tr class="total-row">
                <td>Net Position</td>
                <td class="currency">{{ number_format($summary['net_position'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>This is a computer-generated report and does not require a signature.</p>
    </div>
</body>
</html>
