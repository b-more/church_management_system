<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Income;
use App\Models\Branch;
use App\Models\OfferingType;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Widgets\StatsOverviewWidget;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class IncomeReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.reports.income-reports';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Income Reports';

    public ?array $data = [];
    public ?array $reportData = null;

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
            'branch_id' => null,
            'offering_type_id' => null,
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

                                Forms\Components\Select::make('offering_type_id')
                                    ->label('Offering Type')
                                    ->options(OfferingType::where('is_active', true)->pluck('name', 'id'))
                                    ->placeholder('All Types')
                                    ->searchable(),
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

        $query = Income::query()
            ->with(['branch', 'offeringType', 'member', 'project'])
            ->whereBetween('date', [$data['start_date'], $data['end_date']]);

        if ($data['branch_id']) {
            $query->where('branch_id', $data['branch_id']);
        }

        if ($data['offering_type_id']) {
            $query->where('offering_type_id', $data['offering_type_id']);
        }

        // Generate comprehensive report data
        $this->reportData = [
            'filters' => $data,
            'summary' => $this->generateSummary($query),
            'by_branch' => $this->generateByBranch($query),
            'by_offering_type' => $this->generateByOfferingType($query),
            'by_month' => $this->generateByMonth($query),
            'by_week' => $this->generateByWeek($query),
            'top_contributors' => $this->generateTopContributors($query),
            'project_contributions' => $this->generateProjectContributions($query),
            'payment_methods' => $this->generatePaymentMethods($query),
            'daily_breakdown' => $this->generateDailyBreakdown($query),
        ];
    }

    protected function generateSummary($query): array
    {
        $totalAmount = $query->sum('amount');
        $totalCount = $query->count();
        $averageAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0;
        $uniqueContributors = $query->whereNotNull('member_id')->distinct('member_id')->count();

        return [
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'average_amount' => $averageAmount,
            'unique_contributors' => $uniqueContributors,
        ];
    }

    protected function generateByBranch($query): array
    {
        return $query->selectRaw('branch_id, SUM(amount) as total, COUNT(*) as count')
            ->with('branch')
            ->groupBy('branch_id')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'branch' => $item->branch->name ?? 'Unknown',
                    'total' => $item->total,
                    'count' => $item->count,
                    'average' => $item->count > 0 ? $item->total / $item->count : 0,
                ];
            })
            ->toArray();
    }

    protected function generateByOfferingType($query): array
    {
        return $query->selectRaw('offering_type_id, SUM(amount) as total, COUNT(*) as count')
            ->with('offeringType')
            ->groupBy('offering_type_id')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'offering_type' => $item->offeringType->name ?? 'Unknown',
                    'total' => $item->total,
                    'count' => $item->count,
                    'average' => $item->count > 0 ? $item->total / $item->count : 0,
                ];
            })
            ->toArray();
    }

    protected function generateByMonth($query): array
    {
        return $query->selectRaw('year, month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $date = Carbon::create($item->year, $item->month, 1);
                return [
                    'period' => $date->format('M Y'),
                    'total' => $item->total,
                    'count' => $item->count,
                    'average' => $item->count > 0 ? $item->total / $item->count : 0,
                ];
            })
            ->toArray();
    }

    protected function generateByWeek($query): array
    {
        return $query->selectRaw('year, week_number, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('year', 'week_number')
            ->orderBy('year')
            ->orderBy('week_number')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => "Week {$item->week_number}, {$item->year}",
                    'total' => $item->total,
                    'count' => $item->count,
                    'average' => $item->count > 0 ? $item->total / $item->count : 0,
                ];
            })
            ->toArray();
    }

    protected function generateTopContributors($query): array
    {
        // Group by member first, then by name for non-members
        $memberContributions = $query->whereNotNull('member_id')
            ->selectRaw('member_id, SUM(amount) as total, COUNT(*) as count')
            ->with('member')
            ->groupBy('member_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->member->full_name ?? 'Unknown Member',
                    'phone' => $item->member->phone ?? '',
                    'total' => $item->total,
                    'count' => $item->count,
                    'type' => 'Member',
                ];
            });

        $nonMemberContributions = $query->whereNull('member_id')
            ->whereNotNull('name')
            ->selectRaw('name, phone_number, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('name', 'phone_number')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'phone' => $item->phone_number ?? '',
                    'total' => $item->total,
                    'count' => $item->count,
                    'type' => 'Non-Member',
                ];
            });

        return $memberContributions->concat($nonMemberContributions)
            ->sortByDesc('total')
            ->take(20)
            ->values()
            ->toArray();
    }

    protected function generateProjectContributions($query): array
    {
        return $query->whereNotNull('project_id')
            ->selectRaw('project_id, SUM(amount) as total, COUNT(*) as count')
            ->with('project')
            ->groupBy('project_id')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'project' => $item->project->name ?? 'Unknown Project',
                    'total' => $item->total,
                    'count' => $item->count,
                    'target' => $item->project->target_amount ?? 0,
                    'progress' => $item->project->progress_percentage ?? 0,
                ];
            })
            ->toArray();
    }

    protected function generatePaymentMethods($query): array
    {
        return $query->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => ucwords(str_replace('_', ' ', $item->payment_method)),
                    'total' => $item->total,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    protected function generateDailyBreakdown($query): array
    {
        return $query->selectRaw('date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d/m/Y'),
                    'day' => Carbon::parse($item->date)->format('D'),
                    'total' => $item->total,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    public function downloadPdf(): \Symfony\Component\HttpFoundation\Response
    {
        if (!$this->reportData) {
            return redirect()->back();
        }

        $pdf = Pdf::loadView('reports.income-pdf', [
            'data' => $this->reportData,
            'generated_at' => now(),
        ]);

        $filename = 'income-report-' .
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
            'offering_type_id' => null,
        ]);
    }

    protected function getViewData(): array
    {
        return [
            'reportData' => $this->reportData,
        ];
    }
}
