<?php

// 1. Create a dedicated Export class (Optional - for more advanced exports)
// app/Filament/Exports/MemberExport.php

namespace App\Filament\Exports;

use App\Models\Member;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MemberExport extends Exporter
{
    protected static ?string $model = Member::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('registration_number')->label('Registration Number'),
            ExportColumn::make('title'),
            ExportColumn::make('first_name')->label('First Name'),
            ExportColumn::make('last_name')->label('Last Name'),
            ExportColumn::make('full_name')->label('Full Name'),
            ExportColumn::make('date_of_birth')->label('Date of Birth'),
            ExportColumn::make('gender'),
            ExportColumn::make('phone'),
            ExportColumn::make('alternative_phone')->label('Alternative Phone'),
            ExportColumn::make('email'),
            ExportColumn::make('address'),
            ExportColumn::make('branch.name')->label('Branch'),
            ExportColumn::make('cellGroup.name')->label('Cell Group'),
            ExportColumn::make('membership_status')->label('Membership Status'),
            ExportColumn::make('membership_date')->label('Membership Date'),
            ExportColumn::make('marital_status')->label('Marital Status'),
            ExportColumn::make('occupation'),
            ExportColumn::make('employer'),
            ExportColumn::make('salvation_date')->label('Salvation Date'),
            ExportColumn::make('baptism_date')->label('Baptism Date'),
            ExportColumn::make('baptism_type')->label('Baptism Type'),
            ExportColumn::make('membership_class_status')->label('Membership Class'),
            ExportColumn::make('foundation_class_status')->label('Foundation Class'),
            ExportColumn::make('leadership_class_status')->label('Leadership Class'),
            ExportColumn::make('emergency_contact_name')->label('Emergency Contact'),
            ExportColumn::make('emergency_contact_phone')->label('Emergency Phone'),
            ExportColumn::make('is_active')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),
            ExportColumn::make('created_at')->label('Created At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your member export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}

// 2. Enhanced Member Model with additional methods (add to your existing Member model)
// app/Models/Member.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'registration_number', 'title', 'first_name', 'last_name',
        'date_of_birth', 'gender', 'phone', 'alternative_phone',
        'email', 'address', 'branch_id', 'cell_group_id',
        'membership_status', 'membership_date', 'marital_status',
        'occupation', 'employer', 'salvation_date', 'baptism_date',
        'baptism_type', 'membership_class_status', 'foundation_class_status',
        'leadership_class_status', 'emergency_contact_name', 'emergency_contact_phone',
        'previous_church', 'previous_church_pastor', 'skills_talents',
        'interests', 'special_needs', 'is_active', 'deactivation_reason', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
        'salvation_date' => 'date',
        'baptism_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cellGroup()
    {
        return $this->belongsTo(CellGroup::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function getFormattedPhoneAttribute()
    {
        return $this->phone ? '+260' . $this->phone : null;
    }

    // Scopes for common filters
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByMembershipStatus($query, $status)
    {
        return $query->where('membership_status', $status);
    }

    public function scopeJoinedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('membership_date', [$startDate, $endDate]);
    }

    public function scopeAgeRange($query, $minAge, $maxAge)
    {
        $maxDate = now()->subYears($minAge);
        $minDate = now()->subYears($maxAge + 1);

        return $query->whereBetween('date_of_birth', [$minDate, $maxDate]);
    }

    public function scopeBaptized($query)
    {
        return $query->whereNotNull('baptism_date');
    }

    public function scopeHasSalvation($query)
    {
        return $query->whereNotNull('salvation_date');
    }

    public function scopeWithEmail($query)
    {
        return $query->whereNotNull('email');
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByMaritalStatus($query, $status)
    {
        return $query->where('marital_status', $status);
    }
}

// 3. Additional Widget for Dashboard Statistics
// app/Filament/Widgets/MemberStatsWidget.php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class MemberStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Members', Member::count())
                ->description('All registered members')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Members', Member::active()->count())
                ->description('Currently active members')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('New This Month', Member::whereMonth('created_at', now()->month)->count())
                ->description('Members joined this month')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('info'),

            Stat::make('Leaders', Member::whereIn('membership_status', ['Leader', 'Pastor'])->count())
                ->description('Leadership members')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Baptized Members', Member::baptized()->count())
                ->description('Members who are baptized')
                ->descriptionIcon('heroicon-m-water')
                ->color('primary'),

            Stat::make('Average Age', round(Member::whereNotNull('date_of_birth')->avg(DB::raw('DATEDIFF(NOW(), date_of_birth) / 365.25'))))
                ->description('Average member age')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('gray'),
        ];
    }
}

// 4. Custom Filter Component for Advanced Date Filtering
// app/Filament/Components/DateRangeFilter.php

namespace App\Filament\Components;

use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Indicator;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DateRangeFilter
{
    public static function make(string $field, string $label): Filter
    {
        return Filter::make($field)
            ->form([
                DatePicker::make($field . '_from')
                    ->label($label . ' From'),
                DatePicker::make($field . '_until')
                    ->label($label . ' Until'),
            ])
            ->query(function (Builder $query, array $data) use ($field): Builder {
                return $query
                    ->when(
                        $data[$field . '_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate($field, '>=', $date),
                    )
                    ->when(
                        $data[$field . '_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate($field, '<=', $date),
                    );
            })
            ->indicateUsing(function (array $data) use ($field, $label): array {
                $indicators = [];
                if ($data[$field . '_from'] ?? null) {
                    $indicators[$field . '_from'] = Indicator::make($label . ' from ' . Carbon::parse($data[$field . '_from'])->toFormattedDateString())
                        ->removeField($field . '_from');
                }
                if ($data[$field . '_until'] ?? null) {
                    $indicators[$field . '_until'] = Indicator::make($label . ' until ' . Carbon::parse($data[$field . '_until'])->toFormattedDateString())
                        ->removeField($field . '_until');
                }
                return $indicators;
            });
    }
}
