<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Report Form -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                {{ $this->form }}
            </div>
        </div>

        @if($reportData)
        <!-- Report Results -->
        <div class="space-y-6">
            <!-- Branch Performance Overview -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Performance Overview</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Branch</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Income</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contributors</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avg Contribution</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Growth Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pledge Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($reportData['branch_performance'] as $branch)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $branch['branch'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        K{{ number_format($branch['current']['total_income'], 2) }}
                                        @if(isset($branch['growth']['total_income']))
                                            <div class="text-xs {{ $branch['growth']['total_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $branch['growth']['total_income'] >= 0 ? '+' : '' }}{{ number_format($branch['growth']['total_income'], 1) }}%
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ number_format($branch['current']['unique_contributors']) }}
                                        @if(isset($branch['growth']['unique_contributors']))
                                            <div class="text-xs {{ $branch['growth']['unique_contributors'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $branch['growth']['unique_contributors'] >= 0 ? '+' : '' }}{{ number_format($branch['growth']['unique_contributors'], 1) }}%
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        K{{ number_format($branch['current']['average_contribution'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if(isset($branch['growth']['total_income']))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $branch['growth']['total_income'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $branch['growth']['total_income'] >= 0 ? '+' : '' }}{{ number_format($branch['growth']['total_income'], 1) }}%
                                            </span>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ number_format($branch['current']['pledge_fulfillment_rate'], 1) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Income Analysis by Type -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Income Analysis by Type</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">By Offering Type</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Count</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($reportData['income_analysis']['by_offering_type'] as $type)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $type['type'] }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">K{{ number_format($type['total'], 2) }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ number_format($type['count']) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">By Payment Method</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Method</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Count</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($reportData['income_analysis']['by_payment_method'] as $method)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $method['method'] }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">K{{ number_format($method['total'], 2) }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">{{ number_format($method['count']) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Trends -->
            @if(count($reportData['trends_analysis']) > 1)
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Weekly Trends</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Week</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Income</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contributions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contributors</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Average</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($reportData['trends_analysis'] as $week)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $week['week'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">K{{ number_format($week['total_income'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($week['contribution_count']) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($week['unique_contributors']) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        K{{ $week['contribution_count'] > 0 ? number_format($week['total_income'] / $week['contribution_count'], 2) : '0.00' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Comparative Analysis -->
            @if(isset($reportData['comparative_analysis']))
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Comparative Analysis vs {{ $reportData['comparative_analysis']['comparison_label'] }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <div class="text-sm text-blue-600 dark:text-blue-400">Total Income</div>
                            <div class="text-xl font-bold text-blue-900 dark:text-blue-100">
                                K{{ number_format($reportData['comparative_analysis']['current_period']['total_income'], 2) }}
                            </div>
                            @if(isset($reportData['comparative_analysis']['growth_rates']['total_income']))
                            <div class="text-xs {{ $reportData['comparative_analysis']['growth_rates']['total_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $reportData['comparative_analysis']['growth_rates']['total_income'] >= 0 ? '+' : '' }}{{ number_format($reportData['comparative_analysis']['growth_rates']['total_income'], 1) }}%
                            </div>
                            @endif
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <div class="text-sm text-green-600 dark:text-green-400">Contributors</div>
                            <div class="text-xl font-bold text-green-900 dark:text-green-100">
                                {{ number_format($reportData['comparative_analysis']['current_period']['unique_contributors']) }}
                            </div>
                            @if(isset($reportData['comparative_analysis']['growth_rates']['unique_contributors']))
                            <div class="text-xs {{ $reportData['comparative_analysis']['growth_rates']['unique_contributors'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $reportData['comparative_analysis']['growth_rates']['unique_contributors'] >= 0 ? '+' : '' }}{{ number_format($reportData['comparative_analysis']['growth_rates']['unique_contributors'], 1) }}%
                            </div>
                            @endif
                        </div>

                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <div class="text-sm text-purple-600 dark:text-purple-400">Income Count</div>
                            <div class="text-xl font-bold text-purple-900 dark:text-purple-100">
                                {{ number_format($reportData['comparative_analysis']['current_period']['income_count']) }}
                            </div>
                            @if(isset($reportData['comparative_analysis']['growth_rates']['income_count']))
                            <div class="text-xs {{ $reportData['comparative_analysis']['growth_rates']['income_count'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $reportData['comparative_analysis']['growth_rates']['income_count'] >= 0 ? '+' : '' }}{{ number_format($reportData['comparative_analysis']['growth_rates']['income_count'], 1) }}%
                            </div>
                            @endif
                        </div>

                        <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                            <div class="text-sm text-orange-600 dark:text-orange-400">New Pledges</div>
                            <div class="text-xl font-bold text-orange-900 dark:text-orange-100">
                                {{ number_format($reportData['comparative_analysis']['current_period']['pledge_count']) }}
                            </div>
                            @if(isset($reportData['comparative_analysis']['growth_rates']['pledge_count']))
                            <div class="text-xs {{ $reportData['comparative_analysis']['growth_rates']['pledge_count'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $reportData['comparative_analysis']['growth_rates']['pledge_count'] >= 0 ? '+' : '' }}{{ number_format($reportData['comparative_analysis']['growth_rates']['pledge_count'], 1) }}%
                            </div>
                            @endif
                        </div>

                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                            <div class="text-sm text-indigo-600 dark:text-indigo-400">Pledged Amount</div>
                            <div class="text-xl font-bold text-indigo-900 dark:text-indigo-100">
                                K{{ number_format($reportData['comparative_analysis']['current_period']['pledged_amount'], 2) }}
                            </div>
                            @if(isset($reportData['comparative_analysis']['growth_rates']['pledged_amount']))
                            <div class="text-xs {{ $reportData['comparative_analysis']['growth_rates']['pledged_amount'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $reportData['comparative_analysis']['growth_rates']['pledged_amount'] >= 0 ? '+' : '' }}{{ number_format($reportData['comparative_analysis']['growth_rates']['pledged_amount'], 1) }}%
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Member Engagement Analysis -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Member Engagement Analysis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Unique Contributors</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ number_format($reportData['member_engagement']['unique_contributors']) }}
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Repeat Contributors</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ number_format($reportData['member_engagement']['repeat_contributors']) }}
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Retention Rate</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $reportData['member_engagement']['unique_contributors'] > 0 ? number_format(($reportData['member_engagement']['repeat_contributors'] / $reportData['member_engagement']['unique_contributors']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>

                    @if(count($reportData['member_engagement']['contribution_frequency']) > 0)
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Contribution Frequency Distribution</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($reportData['member_engagement']['contribution_frequency'] as $freq)
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-center">
                                <div class="text-sm text-blue-600 dark:text-blue-400">{{ $freq['range'] }}</div>
                                <div class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ number_format($freq['count']) }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Branch Rankings -->
            @if(isset($reportData['rankings']) && count($reportData['rankings']['by_total_income']) > 1)
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Branch Rankings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">By Total Income</h4>
                            <div class="space-y-2">
                                @foreach(array_slice($reportData['rankings']['by_total_income'], 0, 5) as $index => $branch)
                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-800 text-xs font-medium rounded-full mr-2">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $branch['branch'] }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-300">K{{ number_format($branch['total_income'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">By Pledge Performance</h4>
                            <div class="space-y-2">
                                @foreach(array_slice($reportData['rankings']['by_pledge_performance'], 0, 5) as $index => $branch)
                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 flex items-center justify-center bg-green-100 text-green-800 text-xs font-medium rounded-full mr-2">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $branch['branch'] }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-300">{{ number_format($branch['pledge_fulfillment_rate'], 1) }}%</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">By Engagement</h4>
                            <div class="space-y-2">
                                @foreach(array_slice($reportData['rankings']['by_growth'], 0, 5) as $index => $branch)
                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 flex items-center justify-center bg-purple-100 text-purple-800 text-xs font-medium rounded-full mr-2">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $branch['branch'] }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-300">{{ number_format($branch['unique_contributors']) }} contributors</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</x-filament-panels::page>
