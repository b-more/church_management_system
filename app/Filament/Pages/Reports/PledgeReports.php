<?php

namespace App\Filament\Pages\Reports;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Pledge;
use App\Models\Branch;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\Alignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use App\Notifications\PledgeReminderNotification;
use Filament\Notifications\Notification;

class PledgeReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static string $view = 'filament.pages.reports.pledge-reports';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Pledge Reports';

    public ?array $data = [];
    public ?array $reportData = null;

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'branch_id' => null,
            'project_id' => null,
            'status' => null,
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
                                    ->default(now()->startOfYear()),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label('To Date')
                                    ->required()
                                    ->after('start_date')
                                    ->default(now()->endOfYear()),

                                Forms\Components\Select::make('branch_id')
                                    ->label('Branch')
                                    ->options(Branch::pluck('name', 'id'))
                                    ->placeholder('All Branches')
                                    ->searchable(),

                                Forms\Components\Select::make('project_id')
                                    ->label('Project')
                                    ->options(Project::pluck('name', 'id'))
                                    ->placeholder('All Projects')
                                    ->searchable(),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Active',
                                        'completed' => 'Completed',
                                        'defaulted' => 'Defaulted',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->placeholder('All Statuses'),

                                Forms\Components\Select::make('frequency')
                                    ->label('Frequency')
                                    ->options([
                                        'one-time' => 'One-time',
                                        'weekly' => 'Weekly',
                                        'bi-weekly' => 'Bi-weekly',
                                        'monthly' => 'Monthly',
                                        'quarterly' => 'Quarterly',
                                        'yearly' => 'Yearly',
                                    ])
                                    ->placeholder('All Frequencies'),
                            ])
                            ->columns(3),

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

                                    Action::make('send_reminders')
                                        ->label('Send Payment Reminders')
                                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                        ->color('warning')
                                        ->action('sendPaymentReminders')
                                        ->requiresConfirmation()
                                        ->modalHeading('Send Payment Reminders')
                                        ->modalDescription('Send SMS reminders to all active pledgers with outstanding balances?')
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

        $query = Pledge::query()
            ->with(['branch', 'member', 'project'])
            ->whereBetween('pledge_date', [$data['start_date'], $data['end_date']]);

        if ($data['branch_id']) {
            $query->where('branch_id', $data['branch_id']);
        }

        if ($data['project_id']) {
            $query->where('project_id', $data['project_id']);
        }

        if ($data['status']) {
            $query->where('status', $data['status']);
        }

        if ($data['frequency']) {
            $query->where('frequency', $data['frequency']);
        }

        // Generate comprehensive report data
        $this->reportData = [
            'filters' => $data,
            'summary' => $this->generateSummary($query),
            'by_status' => $this->generateByStatus($query),
            'by_branch' => $this->generateByBranch($query),
            'by_project' => $this->generateByProject($query),
            'by_frequency' => $this->generateByFrequency($query),
            'completion_analysis' => $this->generateCompletionAnalysis($query),
            'overdue_pledges' => $this->generateOverduePledges($query),
            'top_pledgers' => $this->generateTopPledgers($query),
            'performance_metrics' => $this->generatePerformanceMetrics($query),
            'monthly_trends' => $this->generateMonthlyTrends($query),
        ];
    }

    protected function generateSummary($query): array
    {
        $pledges = $query->get();

        return [
            'total_pledges' => $pledges->count(),
            'total_pledged_amount' => $pledges->sum('total_amount'),
            'total_received_amount' => $pledges->sum('received_amount'),
            'total_outstanding' => $pledges->sum(function ($pledge) {
                return $pledge->total_amount - $pledge->received_amount;
            }),
            'average_pledge_amount' => $pledges->count() > 0 ? $pledges->avg('total_amount') : 0,
            'completion_rate' => $pledges->count() > 0 ?
                ($pledges->where('status', 'completed')->count() / $pledges->count()) * 100 : 0,
            'collection_rate' => $pledges->sum('total_amount') > 0 ?
                ($pledges->sum('received_amount') / $pledges->sum('total_amount')) * 100 : 0,
        ];
    }

    protected function generateByStatus($query): array
    {
        return $query->selectRaw('status, COUNT(*) as count, SUM(total_amount) as pledged, SUM(received_amount) as received')
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->status),
                    'count' => $item->count,
                    'pledged' => $item->pledged,
                    'received' => $item->received,
                    'outstanding' => $item->pledged - $item->received,
                    'completion_rate' => $item->pledged > 0 ? ($item->received / $item->pledged) * 100 : 0,
                ];
            })
            ->toArray();
    }

    protected function generateByBranch($query): array
    {
        return $query->selectRaw('branch_id, COUNT(*) as count, SUM(total_amount) as pledged, SUM(received_amount) as received')
            ->with('branch')
            ->groupBy('branch_id')
            ->get()
            ->map(function ($item) {
                return [
                    'branch' => $item->branch->name ?? 'Unknown',
                    'count' => $item->count,
                    'pledged' => $item->pledged,
                    'received' => $item->received,
                    'outstanding' => $item->pledged - $item->received,
                    'completion_rate' => $item->pledged > 0 ? ($item->received / $item->pledged) * 100 : 0,
                ];
            })
            ->sortByDesc('pledged')
            ->toArray();
    }

    protected function generateByProject($query): array
    {
        return $query->whereNotNull('project_id')
            ->selectRaw('project_id, COUNT(*) as count, SUM(total_amount) as pledged, SUM(received_amount) as received')
            ->with('project')
            ->groupBy('project_id')
            ->get()
            ->map(function ($item) {
                return [
                    'project' => $item->project->name ?? 'Unknown Project',
                    'count' => $item->count,
                    'pledged' => $item->pledged,
                    'received' => $item->received,
                    'outstanding' => $item->pledged - $item->received,
                    'project_target' => $item->project->target_amount ?? 0,
                    'project_progress' => $item->project->progress_percentage ?? 0,
                ];
            })
            ->sortByDesc('pledged')
            ->toArray();
    }

    protected function generateByFrequency($query): array
    {
        return $query->selectRaw('frequency, COUNT(*) as count, SUM(total_amount) as pledged, SUM(received_amount) as received')
            ->groupBy('frequency')
            ->get()
            ->map(function ($item) {
                return [
                    'frequency' => ucfirst(str_replace('-', ' ', $item->frequency)),
                    'count' => $item->count,
                    'pledged' => $item->pledged,
                    'received' => $item->received,
                    'outstanding' => $item->pledged - $item->received,
                    'completion_rate' => $item->pledged > 0 ? ($item->received / $item->pledged) * 100 : 0,
                ];
            })
            ->toArray();
    }

    protected function generateCompletionAnalysis($query): array
    {
        $pledges = $query->get();

        $ranges = [
            '0-25%' => $pledges->filter(fn($p) => $p->completion_percentage >= 0 && $p->completion_percentage < 25)->count(),
            '25-50%' => $pledges->filter(fn($p) => $p->completion_percentage >= 25 && $p->completion_percentage < 50)->count(),
            '50-75%' => $pledges->filter(fn($p) => $p->completion_percentage >= 50 && $p->completion_percentage < 75)->count(),
            '75-99%' => $pledges->filter(fn($p) => $p->completion_percentage >= 75 && $p->completion_percentage < 100)->count(),
            '100%' => $pledges->filter(fn($p) => $p->completion_percentage >= 100)->count(),
        ];

        return $ranges;
    }

    protected function generateOverduePledges($query): array
    {
        return $query->where('target_completion_date', '<', now())
            ->where('status', 'active')
            ->whereColumn('received_amount', '<', 'total_amount')
            ->with(['branch', 'member', 'project'])
            ->get()
            ->map(function ($pledge) {
                $overdueDays = $pledge->target_completion_date ?
                              $pledge->target_completion_date->diffInDays(now()) : 0;

                return [
                    'pledger' => $pledge->pledger_name,
                    'phone' => $pledge->pledger_phone,
                    'branch' => $pledge->branch->name ?? '',
                    'project' => $pledge->project->name ?? 'General',
                    'pledged' => $pledge->total_amount,
                    'received' => $pledge->received_amount,
                    'outstanding' => $pledge->remaining_amount,
                    'due_date' => $pledge->target_completion_date?->format('d/m/Y'),
                    'overdue_days' => $overdueDays,
                    'completion_rate' => $pledge->completion_percentage,
                ];
            })
            ->sortByDesc('overdue_days')
            ->toArray();
    }

    protected function generateTopPledgers($query): array
    {
        return $query->with(['member', 'branch'])
            ->get()
            ->groupBy(function ($pledge) {
                return $pledge->member_id ?: 'non-member-' . $pledge->name . '-' . $pledge->phone_number;
            })
            ->map(function ($pledges, $key) {
                $firstPledge = $pledges->first();
                return [
                    'name' => $firstPledge->pledger_name,
                    'phone' => $firstPledge->pledger_phone,
                    'branch' => $firstPledge->branch->name ?? '',
                    'total_pledges' => $pledges->count(),
                    'total_pledged' => $pledges->sum('total_amount'),
                    'total_received' => $pledges->sum('received_amount'),
                    'outstanding' => $pledges->sum('total_amount') - $pledges->sum('received_amount'),
                    'average_completion' => $pledges->avg('completion_percentage'),
                ];
            })
            ->sortByDesc('total_pledged')
            ->take(20)
            ->values()
            ->toArray();
    }

    protected function generatePerformanceMetrics($query): array
    {
        $pledges = $query->get();

        return [
            'on_time_completion' => $pledges->filter(function ($pledge) {
                return $pledge->status === 'completed' &&
                       (!$pledge->target_completion_date ||
                        $pledge->incomes()->latest('date')->first()?->date <= $pledge->target_completion_date);
            })->count(),
            'late_completion' => $pledges->filter(function ($pledge) {
                return $pledge->status === 'completed' &&
                       $pledge->target_completion_date &&
                       $pledge->incomes()->latest('date')->first()?->date > $pledge->target_completion_date;
            })->count(),
            'defaulted_count' => $pledges->where('status', 'defaulted')->count(),
            'cancelled_count' => $pledges->where('status', 'cancelled')->count(),
            'average_days_to_complete' => $this->calculateAverageDaysToComplete($pledges),
        ];
    }

    protected function calculateAverageDaysToComplete($pledges): float
    {
        $completedPledges = $pledges->where('status', 'completed');

        if ($completedPledges->isEmpty()) {
            return 0;
        }

        $totalDays = 0;
        $count = 0;

        foreach ($completedPledges as $pledge) {
            $lastPayment = $pledge->incomes()->latest('date')->first();
            if ($lastPayment) {
                $days = $pledge->pledge_date->diffInDays($lastPayment->date);
                $totalDays += $days;
                $count++;
            }
        }

        return $count > 0 ? $totalDays / $count : 0;
    }

    protected function generateMonthlyTrends($query): array
    {
        $pledges = $query->get();

        $trends = [];
        $startDate = Carbon::parse($this->data['start_date'])->startOfMonth();
        $endDate = Carbon::parse($this->data['end_date'])->endOfMonth();

        while ($startDate <= $endDate) {
            $monthPledges = $pledges->filter(function ($pledge) use ($startDate) {
                return $pledge->pledge_date->isSameMonth($startDate);
            });

            $trends[] = [
                'month' => $startDate->format('M Y'),
                'new_pledges' => $monthPledges->count(),
                'amount_pledged' => $monthPledges->sum('total_amount'),
                'amount_received' => $pledges->filter(function ($pledge) use ($startDate) {
                    return $pledge->incomes()
                        ->whereYear('date', $startDate->year)
                        ->whereMonth('date', $startDate->month)
                        ->exists();
                })->sum(function ($pledge) use ($startDate) {
                    return $pledge->incomes()
                        ->whereYear('date', $startDate->year)
                        ->whereMonth('date', $startDate->month)
                        ->sum('amount');
                }),
            ];

            $startDate->addMonth();
        }

        return $trends;
    }

    public function sendPaymentReminders(): void
    {
        if (!$this->reportData) {
            return;
        }

        $activePledges = Pledge::active()
            ->whereColumn('received_amount', '<', 'total_amount')
            ->whereNotNull('phone_number')
            ->orWhereHas('member', function ($query) {
                $query->whereNotNull('phone');
            })
            ->get();

        $sentCount = 0;
        foreach ($activePledges as $pledge) {
            if (PledgeReminderNotification::sendPaymentDue($pledge)) {
                $sentCount++;
            }
        }

        Notification::make()
            ->title("Payment reminders sent to {$sentCount} pledgers")
            ->success()
            ->send();
    }

    public function downloadPdf(): \Symfony\Component\HttpFoundation\Response
    {
        if (!$this->reportData) {
            return redirect()->back();
        }

        $pdf = Pdf::loadView('reports.pledge-pdf', [
            'data' => $this->reportData,
            'generated_at' => now(),
        ]);

        $filename = 'pledge-report-' .
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
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'branch_id' => null,
            'project_id' => null,
            'status' => null,
        ]);
    }

    protected function getViewData(): array
    {
        return [
            'reportData' => $this->reportData,
        ];
    }
}
