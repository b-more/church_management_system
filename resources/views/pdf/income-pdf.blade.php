<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Income Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .summary-item {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .summary-label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Income Report</h1>
        <p>Period: {{ \Carbon\Carbon::parse($data['filters']['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($data['filters']['end_date'])->format('d M Y') }}</p>
        @if($data['filters']['branch_id'])
            <p>Branch: {{ \App\Models\Branch::find($data['filters']['branch_id'])->name }}</p>
        @endif
        @if($data['filters']['offering_type_id'])
            <p>Offering Type: {{ \App\Models\OfferingType::find($data['filters']['offering_type_id'])->name }}</p>
        @endif
        <p>Generated: {{ $generated_at->format('d M Y H:i') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-label">Total Income</div>
            <div class="summary-value">K{{ number_format($data['summary']['total_amount'], 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Contributions</div>
            <div class="summary-value">{{ number_format($data['summary']['total_count']) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Average Amount</div>
            <div class="summary-value">K{{ number_format($data['summary']['average_amount'], 2) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Unique Contributors</div>
            <div class="summary-value">{{ number_format($data['summary']['unique_contributors']) }}</div>
        </div>
    </div>

    @if(count($data['by_branch']) > 1)
    <!-- Income by Branch -->
    <div class="section-title">Income by Branch</div>
    <table>
        <thead>
            <tr>
                <th>Branch</th>
                <th class="text-right">Total Amount</th>
                <th class="text-center">Count</th>
                <th class="text-right">Average</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_branch'] as $branch)
            <tr>
                <td>{{ $branch['branch'] }}</td>
                <td class="text-right">K{{ number_format($branch['total'], 2) }}</td>
                <td class="text-center">{{ number_format($branch['count']) }}</td>
                <td class="text-right">K{{ number_format($branch['average'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Income by Offering Type -->
    <div class="section-title">Income by Offering Type</div>
    <table>
        <thead>
            <tr>
                <th>Offering Type</th>
                <th class="text-right">Total Amount</th>
                <th class="text-center">Count</th>
                <th class="text-right">Average</th>
                <th class="text-center">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_offering_type'] as $type)
            <tr>
                <td>{{ $type['offering_type'] }}</td>
                <td class="text-right">K{{ number_format($type['total'], 2) }}</td>
                <td class="text-center">{{ number_format($type['count']) }}</td>
                <td class="text-right">K{{ number_format($type['average'], 2) }}</td>
                <td class="text-center">{{ number_format(($type['total'] / $data['summary']['total_amount']) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($data['by_month']) > 1)
    <!-- Monthly Breakdown -->
    <div class="section-title">Monthly Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-right">Total Income</th>
                <th class="text-center">Contributions</th>
                <th class="text-right">Average</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_month'] as $month)
            <tr>
                <td>{{ $month['period'] }}</td>
                <td class="text-right">K{{ number_format($month['total'], 2) }}</td>
                <td class="text-center">{{ number_format($month['count']) }}</td>
                <td class="text-right">K{{ number_format($month['average'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(count($data['top_contributors']) > 0)
    <!-- Top Contributors -->
    <div class="section-title">Top Contributors</div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th class="text-center">Type</th>
                <th class="text-right">Total Contributed</th>
                <th class="text-center">Contributions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(array_slice($data['top_contributors'], 0, 20) as $contributor)
            <tr>
                <td>{{ $contributor['name'] }}</td>
                <td class="text-center">{{ $contributor['type'] }}</td>
                <td class="text-right">K{{ number_format($contributor['total'], 2) }}</td>
                <td class="text-center">{{ number_format($contributor['count']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(count($data['project_contributions']) > 0)
    <!-- Project Contributions -->
    <div class="section-title">Project Contributions</div>
    <table>
        <thead>
            <tr>
                <th>Project</th>
                <th class="text-right">Contributed</th>
                <th class="text-right">Target</th>
                <th class="text-center">Progress</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['project_contributions'] as $project)
            <tr>
                <td>{{ $project['project'] }}</td>
                <td class="text-right">K{{ number_format($project['total'], 2) }}</td>
                <td class="text-right">K{{ number_format($project['target'], 2) }}</td>
                <td class="text-center">{{ number_format($project['progress'], 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(count($data['payment_methods']) > 0)
    <!-- Payment Methods -->
    <div class="section-title">Payment Methods</div>
    <table>
        <thead>
            <tr>
                <th>Payment Method</th>
                <th class="text-right">Total Amount</th>
                <th class="text-center">Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['payment_methods'] as $method)
            <tr>
                <td>{{ $method['method'] }}</td>
                <td class="text-right">K{{ number_format($method['total'], 2) }}</td>
                <td class="text-center">{{ number_format($method['count']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Generated by Church Management System | {{ $generated_at->format('d M Y H:i') }}</p>
    </div>
</body>
</html>
