<?php

namespace App\Filament\Pages;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\DutyRoster;
use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\CellGroup;
use App\Models\Branch;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ChurchDashboard extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-s-home';

    protected static string $view = 'filament.pages.church-dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return "Dashboard";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Add Member')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->action(function(){
                    return redirect("admin/members/create");
                }),
            Actions\Action::make('Record Attendance')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->action(function(){
                    return redirect("admin/attendance-records/create");
                }),
            Actions\Action::make('Add Transaction')
                ->icon('heroicon-o-banknotes')
                ->color('warning')
                ->action(function(){
                    return redirect("admin/transactions/create");
                })
        ];
    }

    public function __construct()
    {
        // Financial Metrics
        $this->today_income = $this->getTodayIncome();
        $this->today_expenses = $this->getTodayExpenses();
        $this->monthly_income = $this->getMonthlyIncome();
        $this->monthly_expenses = $this->getMonthlyExpenses();


        // Member Metrics
        $this->total_members = $this->getTotalMembers();
        $this->active_members = $this->getActiveMembers();
        $this->new_members_month = $this->getNewMembersThisMonth();
        $this->new_members_week = $this->getNewMembersThisWeek();

        // Attendance Metrics
        $this->today_attendance = $this->getTodayAttendance();
        $this->today_visitors = $this->getTodayVisitors();
        $this->pending_followups = $this->getPendingFollowups();
        $this->week_attendance = $this->getWeekAttendance();

        // Event & Ministry Metrics
        $this->upcoming_events = $this->getUpcomingEvents();
        $this->events_this_week = $this->getEventsThisWeek();
        $this->active_cell_groups = $this->getActiveCellGroups();
        $this->total_branches = $this->getTotalBranches();

        // Sunday Duty Roster
        $this->sunday_duty_roster = $this->getNextSundayDutyRoster();
        $this->next_sunday_date = $this->getNextSundayDate();
    }

    // Financial Methods
    public function getTodayIncome()
    {
        $amount = Transaction::whereDate('transaction_date', today())
            ->where('transaction_type', '!=', 'expense')
            ->sum('amount');
        return "ZMW " . number_format($amount, 2);
    }

    public function getTodayExpenses()
    {
        $amount = Transaction::whereDate('transaction_date', today())
            ->where('transaction_type', 'expense')
            ->sum('amount');
        return "ZMW " . number_format($amount, 2);
    }

    public function getMonthlyIncome()
    {
        $amount = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->where('transaction_type', '!=', 'expense')
            ->sum('amount');
        return "ZMW " . number_format($amount, 2);
    }

    public function getMonthlyExpenses()
    {
        $amount = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->where('transaction_type', 'expense')
            ->sum('amount');
        return "ZMW " . number_format($amount, 2);
    }

    // Sunday Duty Roster Methods
    public function getNextSundayDate()
    {
        $nextSunday = now()->next(Carbon::SUNDAY);
        return $nextSunday->format('F j, Y');
    }

    public function getNextSundayDutyRoster()
    {
        $nextSunday = now()->next(Carbon::SUNDAY);

        return \App\Models\DutyRoster::with([
            'serviceHost', 'intercessionLeader', 'worshipLeader',
            'announcer', 'exhortationLeader', 'sundaySchoolTeacher', 'preacher', 'branch'
        ])
        ->whereDate('service_date', $nextSunday->toDateString())
        ->where('status', 'published')
        ->first();
    }

    // Member Methods
    public function getTotalMembers()
    {
        return Member::count();
    }

    public function getActiveMembers()
    {
        return Member::where('is_active', true)->count();
    }

    public function getNewMembersThisMonth()
    {
        return Member::whereMonth('membership_date', now()->month)
            ->whereYear('membership_date', now()->year)
            ->count();
    }

    public function getNewMembersThisWeek()
    {
        return Member::whereBetween('membership_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    // Attendance Methods
    public function getTodayAttendance()
    {
        return AttendanceRecord::whereDate('check_in_time', today())->count();
    }

    public function getTodayVisitors()
    {
        return AttendanceRecord::whereDate('check_in_time', today())
            ->whereIn('attendance_type', ['Visitor', 'First Timer'])
            ->count();
    }

    public function getPendingFollowups()
    {
        return AttendanceRecord::where('follow_up_required', true)
            ->whereNull('follow_up_notes')
            ->count();
    }

    public function getWeekAttendance()
    {
        return AttendanceRecord::whereBetween('check_in_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    // Event & Ministry Methods
    public function getUpcomingEvents()
    {
        return Event::where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(30))
            ->where('status', '!=', 'Cancelled')
            ->count();
    }

    public function getEventsThisWeek()
    {
        return Event::whereBetween('start_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->where('status', '!=', 'Cancelled')
        ->count();
    }

    public function getActiveCellGroups()
    {
        return CellGroup::where('status', 'Active')->count();
    }

    public function getTotalBranches()
    {
        return Branch::count();
    }

    // Table for Recent Transactions
    public function table(Table $table): Table
    {
        $transactions = Transaction::with(['member', 'category'])
            ->latest('transaction_date')
            ->take(10);

        return $table
            ->query($transactions)
            ->poll('30s')
            ->striped()
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(function($state){
                        return match($state) {
                            'tithe' => 'Tithe',
                            'offering' => 'Offering',
                            'special_offering' => 'Special Offering',
                            'building_fund' => 'Building Fund',
                            'expense' => 'Expense',
                            default => ucfirst($state)
                        };
                    })
                    ->color(function($record){
                        return match($record->transaction_type) {
                            'tithe' => 'success',
                            'offering' => 'info',
                            'special_offering' => 'warning',
                            'building_fund' => 'primary',
                            'expense' => 'danger',
                            default => 'gray'
                        };
                    }),

                TextColumn::make('member.first_name')
                    ->label('Member')
                    ->formatStateUsing(function($record) {
                        if ($record->member) {
                            return $record->member->first_name . ' ' . $record->member->last_name;
                        }
                        return 'N/A';
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount (ZMW)')
                    ->alignEnd()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 2))
                    ->weight(FontWeight::Bold)
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->formatStateUsing(function($state){
                        return match($state) {
                            'cash' => 'Cash',
                            'mobile_money' => 'Mobile Money',
                            'bank_transfer' => 'Bank Transfer',
                            'card' => 'Card',
                            default => ucfirst($state ?? 'N/A')
                        };
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function($state){
                        return match($state) {
                            'completed' => 'Completed',
                            'pending' => 'Pending',
                            'failed' => 'Failed',
                            default => ucfirst($state ?? 'Unknown')
                        };
                    })
                    ->color(function($record){
                        return match($record->status) {
                            'completed' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            default => 'gray'
                        };
                    }),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                // Add actions if needed
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Add bulk actions if needed
                ])
            ]);
    }

    // Public properties for the view
    public $today_income;
    public $today_expenses;
    public $monthly_income;
    public $monthly_expenses;
    public $total_members;
    public $active_members;
    public $new_members_month;
    public $new_members_week;
    public $today_attendance;
    public $today_visitors;
    public $pending_followups;
    public $week_attendance;
    public $upcoming_events;
    public $events_this_week;
    public $active_cell_groups;
    public $total_branches;
    public $sunday_duty_roster;
    public $next_sunday_date;
}
