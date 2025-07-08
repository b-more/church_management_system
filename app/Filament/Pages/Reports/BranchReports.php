<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Income;
use App\Models\Pledge;
use App\Models\Partnership;
use App\Models\Branch;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class BranchReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static string $view = 'filament.pages.reports.branch-reports';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Branch Performance Reports';

    public ?array $data = [];
    public ?array $reportData = null;

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
            'branch_id' => null,
            'comparison_period' => 'previous_month',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Section::make('Report Filters')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('From Date')
                                    ->required()
                                    ->default(now()->startOfMonth()),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label('To Date')
                                    ->required()
                                    ->after('start_date')
                                    ->default(now()->endOfMonth()),

                                Forms\Components\Select::make('branch_id')
                                    ->label('Branch')
                                    ->options(Branch::pluck('name', 'id'))
                                    ->placeholder('All Branches')
                                    ->searchable(),

                                Forms\Components\Select::make('comparison_period')
                                    ->label('Compare With')
                                    ->options([
                                        'previous_month' => 'Previous Month',
                                        'previous_quarter' => 'Previous Quarter',
                                        'previous_year' => 'Previous Year',
                                        'same_month_last_year' => 'Same Month Last Year',
                                    ])
                                    ->default('previous_month'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Actions')
                            ->schema([
                                Forms\Components\Actions::make([
                                    Action::make('generate_report')
                                        ->label('Generate Report')
                                        ->icon('heroicon-o-document-magnifying-glass')
                                        ->color('primary')
                                        ->action('generateReport'),

                                    Action::make('download_pdf')
                                        ->label('Download PDF')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->color('success')
                                        ->action('downloadPdf')
                                        ->visible(fn () => $this->reportData !== null),

                                    Action::make('reset')
                                        ->label('Reset')
                                        ->icon('heroicon-o-arrow-path')
                                        ->color('gray')
                                        ->action('resetReport'),
                                ])
                                ->alignment(Alignment::Center)
                                ->fullWidth(),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan('full'),
            ])
            ->statePath('data');
    }

    public function generateReport(): void
    {
        $data = $this->form->getState();

        // Generate comprehensive branch performance report
        $this->reportData = [
            'filters' => $data,
            'period' => [
                'start' => Carbon::parse($data['start_date']),
                'end' => Carbon::parse($data['end_date']),
                'comparison' => $this->getComparisonPeriod($data),
            ],
            'branch_performance' => $this->generateBranchPerformance($data),
            'income_analysis' => $this->generateIncomeAnalysis($data),
            'pledge_analysis' => $this->generatePledgeAnalysis($data),
            'partnership_analysis' => $this->generatePartnershipAnalysis($data),
            'member_engagement' => $this->generateMemberEngagement($data),
            'trends_analysis' => $this->generateTrendsAnalysis($data),
            'comparative_analysis' => $this->generateComparativeAnalysis($data),
            'rankings' => $this->generateBranchRankings($data),
        ];
    }

    protected function getComparisonPeriod(array $data): array
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $period = $data['comparison_period'];

        return match($period) {
            'previous_month' => [
                'start' => $start->copy()->subMonth(),
                'end' => $end->copy()->subMonth(),
                'label' => 'Previous Month'
            ],
            'previous_quarter' => [
                'start' => $start->copy()->subMonths(3),
                'end' => $end->copy()->subMonths(3),
                'label' => 'Previous Quarter'
            ],
            'previous_year' => [
                'start' => $start->copy()->subYear(),
                'end' => $end->copy()->subYear(),
                'label' => 'Previous Year'
            ],
            'same_month_last_year' => [
                'start' => $start->copy()->subYear(),
                'end' => $end->copy()->subYear(),
                'label' => 'Same Period Last Year'
            ],
        };
    }

    protected function generateBranchPerformance(array $data): array
    {
        $branches = $data['branch_id'] ?
                   Branch::where('id', $data['branch_id'])->get() :
                   Branch::all();

        return $branches->map(function ($branch) use ($data) {
            $currentPeriod = $this->getBranchMetrics($branch->id, $data['start_date'], $data['end_date']);
            $comparisonPeriod = $this->getComparisonPeriod($data);
            $previousPeriod = $this->getBranchMetrics(
                $branch->id,
                $comparisonPeriod['start'],
                $comparisonPeriod['end']
            );

            return [
                'branch' => $branch->name,
                'current' => $currentPeriod,
                'previous' => $previousPeriod,
                'growth' => $this->calculateGrowthMetrics($currentPeriod, $previousPeriod),
            ];
        })->toArray();
    }

    protected function getBranchMetrics(int $branchId, $startDate, $endDate): array
    {
        $incomes = Income::where('branch_id', $branchId)
            ->whereBetween('date', [$startDate, $endDate]);

        $pledges = Pledge::where('branch_id', $branchId)
            ->whereBetween('pledge_date', [$startDate, $endDate]);

        $partnerships = Partnership::where('branch_id', $branchId)
            ->whereBetween('start_date', [$startDate, $endDate]);

        return [
            'total_income' => $incomes->sum('amount'),
            'income_count' => $incomes->count(),
            'average_contribution' => $incomes->count() > 0 ? $incomes->sum('amount') / $incomes->count() : 0,
            'unique_contributors' => $incomes->whereNotNull('member_id')->distinct('member_id')->count(),
            'tithe_amount' => $incomes->whereHas('offeringType', fn($q) => $q->where('name', 'Tithe'))->sum('amount'),
            'offering_amount' => $incomes->whereHas('offeringType', fn($q) => $q->where('name', 'Offering'))->sum('amount'),
            'project_amount' => $incomes->whereHas('offeringType', fn($q) => $q->where('name', 'Projects'))->sum('amount'),
            'partnership_amount' => $incomes->whereHas('offeringType', fn($q) => $q->where('name', 'Financial Partnership'))->sum('amount'),
            'new_pledges' => $pledges->count(),
            'pledged_amount' => $pledges->sum('total_amount'),
            'pledge_fulfillment_rate' => $this->calculatePledgeFulfillmentRate($branchId, $startDate, $endDate),
            'new_partnerships' => $partnerships->count(),
            'partnership_value' => $partnerships->sum('monthly_amount'),
            'active_partnerships' => Partnership::where('branch_id', $branchId)->active()->count(),
        ];
    }

    protected function calculatePledgeFulfillmentRate(int $branchId, $startDate, $endDate): float
    {
        $pledges = Pledge::where('branch_id', $branchId)
            ->where('pledge_date', '<=', $endDate)
            ->where(function ($query) use ($startDate) {
                $query->whereNull('target_completion_date')
                      ->orWhere('target_completion_date', '>=', $startDate);
            });

        $totalPledged = $pledges->sum('total_amount');
        $totalReceived = $pledges->sum('received_amount');

        return $totalPledged > 0 ? ($totalReceived / $totalPledged) * 100 : 0;
    }

    protected function calculateGrowthMetrics(array $current, array $previous): array
    {
        $growth = [];

        foreach ($current as $key => $value) {
            if (isset($previous[$key]) && is_numeric($value) && is_numeric($previous[$key])) {
                $previousValue = $previous[$key];

                if ($previousValue > 0) {
                    $growth[$key] = (($value - $previousValue) / $previousValue) * 100;
                } else {
                    $growth[$key] = $value > 0 ? 100 : 0;
                }
            }
        }

        return $growth;
    }

    protected function generateIncomeAnalysis(array $data): array
    {
        $query = Income::whereBetween('date', [$data['start_date'], $data['end_date']]);

        if ($data['branch_id']) {
            $query->where('branch_id', $data['branch_id']);
        }

        return [
            'by_offering_type' => $query->selectRaw('offering_type_id, SUM(amount) as total, COUNT(*) as count')
                ->with('offeringType')
                ->groupBy('offering_type_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => $item->offeringType->name ?? 'Unknown',
                        'total' => $item->total,
                        'count' => $item->count,
                        'average' => $item->count > 0 ? $item->total / $item->count : 0,
                    ];
                })
                ->toArray(),

            'by_payment_method' => $query->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get()
                ->map(function ($item) {
                    return [
                        'method' => ucwords(str_replace('_', ' ', $item->payment_method)),
                        'total' => $item->total,
                        'count' => $item->count,
                    ];
                })
                ->toArray(),

            'daily_averages' => $this->getDailyAverages($query),
            'weekly_pattern' => $this->getWeeklyPattern($query),
        ];
    }

    protected function getDailyAverages($query): array
    {
        return $query->selectRaw('DAYOFWEEK(date) as day_of_week, AVG(amount) as average, COUNT(*) as count')
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get()
            ->map(function ($item) {
                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                return [
                    'day' => $days[$item->day_of_week - 1] ?? 'Unknown',
                    'average' => $item->average,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    protected function getWeeklyPattern($query): array
    {
        return $query->selectRaw('week_number, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get()
            ->map(function ($item) {
                return [
                    'week' => $item->week_number,
                    'total' => $item->total,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    protected function generatePledgeAnalysis(array $data): array
    {
        $query = Pledge::whereBetween('pledge_date', [$data['start_date'], $data['end_date']]);

        if ($data['branch_id']) {
            $query->where('branch_id', $data['branch_id']);
        }

        $pledges = $query->get();

        return [
            'summary' => [
                'total_pledges' => $pledges->count(),
                'total_amount' => $pledges->sum('total_amount'),
                'total_received' => $pledges->sum('received_amount'),
                'completion_rate' => $pledges->count() > 0 ?
                    ($pledges->where('status', 'completed')->count() / $pledges->count()) * 100 : 0,
            ],
            'by_frequency' => $pledges->groupBy('frequency')
                ->map(function ($group, $frequency) {
                    return [
                        'frequency' => ucfirst(str_replace('-', ' ', $frequency)),
                        'count' => $group->count(),
                        'total_amount' => $group->sum('total_amount'),
                        'received_amount' => $group->sum('received_amount'),
                    ];
                })
                ->values()
                ->toArray(),
            'overdue_analysis' => $this->getOverdueAnalysis($pledges),
        ];
    }

    protected function getOverdueAnalysis($pledges): array
    {
        $overdue = $pledges->filter(function ($pledge) {
            return $pledge->is_overdue;
        });

        return [
            'count' => $overdue->count(),
            'total_amount' => $overdue->sum('remaining_amount'),
            'average_overdue_days' => $overdue->count() > 0 ?
                $overdue->avg(function ($pledge) {
                    return $pledge->target_completion_date ?
                           $pledge->target_completion_date->diffInDays(now()) : 0;
                }) : 0,
        ];
    }

    protected function generatePartnershipAnalysis(array $data): array
    {
        $query = Partnership::whereBetween('start_date', [$data['start_date'], $data['end_date']]);

        if ($data['branch_id']) {
            $query->where('branch_id', $data['branch_id']);
        }

        $partnerships = $query->get();

        return [
            'new_partnerships' => $partnerships->count(),
            'total_monthly_value' => $partnerships->sum('monthly_amount'),
            'average_partnership_amount' => $partnerships->count() > 0 ?
                $partnerships->avg('monthly_amount') : 0,
            'status_breakdown' => $partnerships->groupBy('status')
                ->map(function ($group, $status) {
                    return [
                        'status' => ucfirst($status),
                        'count' => $group->count(),
                        'total_value' => $group->sum('monthly_amount'),
                    ];
                })
                ->values()
                ->toArray(),
        ];
    }

    protected function generateMemberEngagement(array $data): array
    {
        // This would analyze member participation patterns
        $query = Income::whereBetween('date', [$data['start_date'], $data['end_date']])
            ->whereNotNull('member_id');

        if ($data['branch_id']) {
            $query->where('branch_id', $data['branch_id']);
        }

        return [
            'unique_contributors' => $query->distinct('member_id')->count(),
            'repeat_contributors' => $query->selectRaw('member_id, COUNT(*) as contribution_count')
                ->groupBy('member_id')
                ->having('contribution_count', '>', 1)
                ->count(),
            'contribution_frequency' => $query->selectRaw('member_id, COUNT(*) as frequency')
                ->groupBy('member_id')
                ->get()
                ->groupBy(function ($item) {
                    if ($item->frequency == 1) return 'One-time';
                    if ($item->frequency <= 4) return '2-4 times';
                    if ($item->frequency <= 8) return '5-8 times';
                    return '9+ times';
                })
                ->map(function ($group, $range) {
                    return [
                        'range' => $range,
                        'count' => $group->count(),
                    ];
                })
                ->values()
                ->toArray(),
        ];
    }

    protected function generateTrendsAnalysis(array $data): array
    {
        // Weekly trends within the selected period
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        $trends = [];
        $currentWeek = $startDate->copy()->startOfWeek();

        while ($currentWeek <= $endDate) {
            $weekEnd = $currentWeek->copy()->endOfWeek();
            if ($weekEnd > $endDate) $weekEnd = $endDate;

            $weekQuery = Income::whereBetween('date', [$currentWeek, $weekEnd]);

            if ($data['branch_id']) {
                $weekQuery->where('branch_id', $data['branch_id']);
            }

            $trends[] = [
                'week' => $currentWeek->format('M d') . ' - ' . $weekEnd->format('M d'),
                'total_income' => $weekQuery->sum('amount'),
                'contribution_count' => $weekQuery->count(),
                'unique_contributors' => $weekQuery->whereNotNull('member_id')->distinct('member_id')->count(),
            ];

            $currentWeek->addWeek();
        }

        return $trends;
    }

    protected function generateComparativeAnalysis(array $data): array
    {
        $comparisonPeriod = $this->getComparisonPeriod($data);

        $currentMetrics = $this->getPeriodMetrics($data['start_date'], $data['end_date'], $data['branch_id']);
        $previousMetrics = $this->getPeriodMetrics(
            $comparisonPeriod['start'],
            $comparisonPeriod['end'],
            $data['branch_id']
        );

        return [
            'current_period' => $currentMetrics,
            'comparison_period' => $previousMetrics,
            'growth_rates' => $this->calculateGrowthMetrics($currentMetrics, $previousMetrics),
            'comparison_label' => $comparisonPeriod['label'],
        ];
    }

    protected function getPeriodMetrics($startDate, $endDate, $branchId = null): array
    {
        $incomeQuery = Income::whereBetween('date', [$startDate, $endDate]);
        $pledgeQuery = Pledge::whereBetween('pledge_date', [$startDate, $endDate]);

        if ($branchId) {
            $incomeQuery->where('branch_id', $branchId);
            $pledgeQuery->where('branch_id', $branchId);
        }

        return [
            'total_income' => $incomeQuery->sum('amount'),
            'income_count' => $incomeQuery->count(),
            'pledge_count' => $pledgeQuery->count(),
            'pledged_amount' => $pledgeQuery->sum('total_amount'),
            'unique_contributors' => $incomeQuery->whereNotNull('member_id')->distinct('member_id')->count(),
        ];
    }

    protected function generateBranchRankings(array $data): array
    {
        if ($data['branch_id']) {
            return []; // No rankings for single branch
        }

        $branches = Branch::all();
        $rankings = $branches->map(function ($branch) use ($data) {
            $metrics = $this->getBranchMetrics($branch->id, $data['start_date'], $data['end_date']);
            return array_merge(['branch' => $branch->name], $metrics);
        });

        return [
            'by_total_income' => $rankings->sortByDesc('total_income')->take(10)->values()->toArray(),
            'by_pledge_performance' => $rankings->sortByDesc('pledge_fulfillment_rate')->take(10)->values()->toArray(),
            'by_growth' => $rankings->sortByDesc('unique_contributors')->take(10)->values()->toArray(),
        ];
    }

    public function downloadPdf(): \Symfony\Component\HttpFoundation\Response
    {
        if (!$this->reportData) {
            return redirect()->back();
        }

        $pdf = Pdf::loadView('reports.branch-pdf', [
            'data' => $this->reportData,
            'generated_at' => now(),
        ]);

        $filename = 'branch-report-' .
                   Carbon::parse($this->reportData['filters']['start_date'])->format('Y-m-d') .
                   '-to-' .
                   Carbon::parse($this->reportData['filters']['end_date'])->format('Y-m-d') .
                   '.pdf';

        return Response::streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename, ['Content-Type' => 'application/pdf']);
    }

    public function resetReport(): void
    {
        $this->reportData = null;
        $this->form->fill([
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
            'branch_id' => null,
            'comparison_period' => 'previous_month',
        ]);
    }

    protected function getViewData(): array
    {
        return [
            'reportData' => $this->reportData,
        ];
    }
}
