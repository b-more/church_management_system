<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use App\Models\Branch;
use App\Models\CellGroup;
use App\Services\MemberPdfService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Carbon\Carbon;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'full_name';
    protected static ?string $navigationGroup = 'Church Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Hidden::make('registration_number')
                        ->default(function () {
                            $latestMember = Member::latest()->first();
                            $nextId = $latestMember ? $latestMember->id + 1 : 1;
                            return 'HKC-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                        }),
                        Grid::make(3)
                            ->schema([
                                Select::make('title')
                                    ->options([
                                        'Mr.' => 'Mr.',
                                        'Mrs.' => 'Mrs.',
                                        'Miss' => 'Miss',
                                        'Dr.' => 'Dr.',
                                        'Rev.' => 'Rev.',
                                        'Pastor' => 'Pastor',
                                        'Apostle' => 'Apostle',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->required()
                                    ->maxDate(now()),
                                Select::make('gender')
                                    ->options([
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                        'Other' => 'Other',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->prefix('+26')
                                    ->placeholder('09XXXXXXXX')
                                    ->length(10)
                                    ->telRegex('/^[0-9]{10}$/'),
                                Forms\Components\TextInput::make('alternative_phone')
                                    ->tel()
                                    ->prefix('+26')
                                    ->placeholder('09XXXXXXXX')
                                    ->length(10)
                                    ->telRegex('/^[0-9]{10}$/'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true),
                            ]),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->rows(3),
                    ]),

                Section::make('Church Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('branch_id')
                                    ->relationship('branch', 'name')
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('registration_number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->prefix('REG NO.')
                                    ->disabled()
                                    ->default(function () {
                                        return 'HKC-' . str_pad(Member::max('id') + 1, 6, '0', STR_PAD_LEFT);
                                    }),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('membership_status')
                                    ->options([
                                        'First Timer' => 'First Timer',
                                        'New Convert' => 'New Convert',
                                        'Regular Member' => 'Regular Member',
                                        'Kingdom Worker' => 'Kingdom Worker',
                                        'Leader' => 'Leader',
                                        'Pastor' => 'Pastor',
                                    ])
                                    ->required(),
                                Forms\Components\DatePicker::make('membership_date')
                                    ->required()
                                    ->maxDate(now()),
                                Select::make('cell_group_id')
                                    ->relationship('cellGroup', 'name')
                                    ->searchable(),
                            ]),
                    ]),

                Section::make('Spiritual Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('salvation_date')
                                    ->maxDate(now()),
                                Forms\Components\DatePicker::make('baptism_date')
                                    ->maxDate(now()),
                                Select::make('baptism_type')
                                    ->options([
                                        'Immersion' => 'Immersion',
                                        'Sprinkle' => 'Sprinkle',
                                        'Both' => 'Both',
                                    ]),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('membership_class_status')
                                    ->options([
                                        'Not Started' => 'Not Started',
                                        'In Progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                    ])
                                    ->required(),
                                Select::make('foundation_class_status')
                                    ->options([
                                        'Not Started' => 'Not Started',
                                        'In Progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                    ])
                                    ->required(),
                                Select::make('leadership_class_status')
                                    ->options([
                                        'Not Started' => 'Not Started',
                                        'In Progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('marital_status')
                                    ->options([
                                        'Single' => 'Single',
                                        'Married' => 'Married',
                                        'Divorced' => 'Divorced',
                                        'Widowed' => 'Widowed',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('occupation'),
                                Forms\Components\TextInput::make('employer'),
                            ]),
                    ]),

                Section::make('Emergency Contact')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('emergency_contact_name'),
                                Forms\Components\TextInput::make('emergency_contact_phone')
                                    ->tel()
                                    ->prefix('+260')
                                    ->placeholder('09XXXXXXXX')
                                    ->length(10),
                            ]),
                    ]),

                Section::make('Previous Church Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('previous_church'),
                                Forms\Components\TextInput::make('previous_church_pastor'),
                            ]),
                    ]),

                Section::make('Skills & Interests')
                    ->schema([
                        Forms\Components\Textarea::make('skills_talents')
                            ->rows(3),
                        Forms\Components\Textarea::make('interests')
                            ->rows(3),
                        Forms\Components\Textarea::make('special_needs')
                            ->rows(3),
                    ]),

                Section::make('Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->default(true)
                                    ->reactive(),
                                Forms\Components\TextInput::make('deactivation_reason')
                                    ->visible(fn (Forms\Get $get) => !$get('is_active')),
                            ]),
                        Forms\Components\Textarea::make('notes')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('membership_status')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'First Timer' => 'gray',
                        'New Convert' => 'info',
                        'Regular Member' => 'success',
                        'Pastor' => 'success',
                        'Kingdom Worker' => 'warning',
                        'Leader' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cellGroup.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('membership_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('age')
                    ->getStateUsing(fn ($record) => $record->date_of_birth ? Carbon::parse($record->date_of_birth)->age : null)
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('date_of_birth', $direction === 'asc' ? 'desc' : 'asc');
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Basic Status Filters
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->placeholder('All Members'),

                // Church Structure Filters
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('cell_group')
                    ->relationship('cellGroup', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('membership_status')
                    ->multiple()
                    ->options([
                        'First Timer' => 'First Timer',
                        'New Convert' => 'New Convert',
                        'Regular Member' => 'Regular Member',
                        'Kingdom Worker' => 'Kingdom Worker',
                        'Leader' => 'Leader',
                        'Pastor' => 'Pastor',
                    ]),

                // Personal Information Filters
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ]),

                Tables\Filters\SelectFilter::make('marital_status')
                    ->options([
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                        'Widowed' => 'Widowed',
                    ]),

                Tables\Filters\SelectFilter::make('title')
                    ->options([
                        'Mr.' => 'Mr.',
                        'Mrs.' => 'Mrs.',
                        'Miss' => 'Miss',
                        'Dr.' => 'Dr.',
                        'Rev.' => 'Rev.',
                        'Pastor' => 'Pastor',
                        'Apostle' => 'Apostle',
                    ]),

                // Date Range Filters
                Filter::make('membership_date')
                    ->form([
                        Forms\Components\DatePicker::make('membership_from')
                            ->label('Joined From'),
                        Forms\Components\DatePicker::make('membership_until')
                            ->label('Joined Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['membership_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('membership_date', '>=', $date),
                            )
                            ->when(
                                $data['membership_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('membership_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['membership_from'] ?? null) {
                            $indicators['membership_from'] = Indicator::make('Joined from ' . Carbon::parse($data['membership_from'])->toFormattedDateString())
                                ->removeField('membership_from');
                        }
                        if ($data['membership_until'] ?? null) {
                            $indicators['membership_until'] = Indicator::make('Joined until ' . Carbon::parse($data['membership_until'])->toFormattedDateString())
                                ->removeField('membership_until');
                        }
                        return $indicators;
                    }),

                Filter::make('age_range')
                    ->form([
                        Forms\Components\TextInput::make('age_from')
                            ->label('Age From')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120),
                        Forms\Components\TextInput::make('age_to')
                            ->label('Age To')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['age_from'],
                                fn (Builder $query, $age): Builder => $query->whereDate('date_of_birth', '<=', now()->subYears($age))
                            )
                            ->when(
                                $data['age_to'],
                                fn (Builder $query, $age): Builder => $query->whereDate('date_of_birth', '>=', now()->subYears($age))
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['age_from'] ?? null) {
                            $indicators['age_from'] = Indicator::make('Age from ' . $data['age_from'])
                                ->removeField('age_from');
                        }
                        if ($data['age_to'] ?? null) {
                            $indicators['age_to'] = Indicator::make('Age to ' . $data['age_to'])
                                ->removeField('age_to');
                        }
                        return $indicators;
                    }),

                // Spiritual Journey Filters
                Tables\Filters\SelectFilter::make('membership_class_status')
                    ->label('Membership Class')
                    ->options([
                        'Not Started' => 'Not Started',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('foundation_class_status')
                    ->label('Foundation Class')
                    ->options([
                        'Not Started' => 'Not Started',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('leadership_class_status')
                    ->label('Leadership Class')
                    ->options([
                        'Not Started' => 'Not Started',
                        'In Progress' => 'In Progress',
                        'Completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('baptism_type')
                    ->options([
                        'Immersion' => 'Immersion',
                        'Sprinkle' => 'Sprinkle',
                        'Both' => 'Both',
                    ]),

                Filter::make('has_salvation_date')
                    ->label('Has Salvation Date')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('salvation_date'))
                    ->toggle(),

                Filter::make('has_baptism_date')
                    ->label('Is Baptized')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('baptism_date'))
                    ->toggle(),

                Filter::make('has_email')
                    ->label('Has Email')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email'))
                    ->toggle(),

                Filter::make('has_emergency_contact')
                    ->label('Has Emergency Contact')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('emergency_contact_name'))
                    ->toggle(),

                // Text Search Filters
                Filter::make('search_occupation')
                    ->form([
                        Forms\Components\TextInput::make('occupation')
                            ->label('Search by Occupation')
                            ->placeholder('Enter occupation to search'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['occupation'],
                            fn (Builder $query, $occupation): Builder => $query->where('occupation', 'like', "%{$occupation}%")
                        );
                    })
                    ->indicateUsing(function (array $data): ?Indicator {
                        if ($data['occupation'] ?? null) {
                            return Indicator::make('Occupation: ' . $data['occupation'])
                                ->removeField('occupation');
                        }
                        return null;
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->persistFiltersInSession()
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filters')
                    ->size('sm')
            )
            ->headerActions([
                // CSV Export Action
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
                        // Get the filtered query
                        $query = $livewire->getFilteredTableQuery();
                        $members = $query->with(['branch', 'cellGroup'])->get();

                        // Generate CSV content
                        $csvContent = self::generateCsvExport($members);

                        // Return download response
                        return response()->streamDownload(function () use ($csvContent) {
                            echo $csvContent;
                        }, 'members-export-' . now()->format('Y-m-d-H-i-s') . '.csv', [
                            'Content-Type' => 'text/csv',
                        ]);
                    }),

                // PDF Export Action
                Tables\Actions\Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label('Report Title')
                            ->default('Member Directory')
                            ->required(),
                        Forms\Components\Toggle::make('include_contact')
                            ->label('Include Contact Information')
                            ->default(true),
                        Forms\Components\Toggle::make('include_spiritual')
                            ->label('Include Spiritual Information')
                            ->default(false),
                        Forms\Components\Select::make('format')
                            ->label('Format')
                            ->options([
                                'list' => 'Member List',
                                'cards' => 'Professional Member Cards',
                                'badges' => 'Name Badges',
                                'certificates' => 'Membership Certificates',
                            ])
                            ->default('list')
                            ->required()
                            ->reactive(),
                        Forms\Components\Select::make('card_style')
                            ->label('Card Style')
                            ->options([
                                'professional' => 'Professional (Gradient)',
                                'executive' => 'Executive (Clean)',
                                'simple' => 'Simple (Minimal)',
                            ])
                            ->default('professional')
                            ->visible(fn (Forms\Get $get) => $get('format') === 'cards'),
                        Forms\Components\Select::make('certificate_type')
                            ->label('Certificate Type')
                            ->options([
                                'membership' => 'Membership Certificate',
                                'baptism' => 'Baptism Certificate',
                                'dedication' => 'Dedication Certificate',
                            ])
                            ->default('membership')
                            ->visible(fn (Forms\Get $get) => $get('format') === 'certificates'),
                        Forms\Components\TextInput::make('pastor_name')
                            ->label('Pastor Name')
                            ->default('Apostle Chris Siame')
                            ->visible(fn (Forms\Get $get) => $get('format') === 'certificates'),
                    ])
                    ->action(function ($livewire, array $data) {
                        $query = $livewire->getFilteredTableQuery();
                        $members = $query->with(['branch', 'cellGroup'])->get();

                        $pdfService = new MemberPdfService();

                        switch ($data['format']) {
                            case 'cards':
                                $pdf = $pdfService->generateMemberCards($members, [
                                    'card_style' => $data['card_style'] ?? 'professional',
                                    'include_contact' => $data['include_contact'] ?? true,
                                ]);
                                $filename = 'member-cards-' . ($data['card_style'] ?? 'professional') . '-' . now()->format('Y-m-d') . '.pdf';
                                break;

                            case 'badges':
                                $pdf = $pdfService->generateMemberBadges($members);
                                $filename = 'member-badges-' . now()->format('Y-m-d') . '.pdf';
                                break;

                            case 'certificates':
                                $pdf = $pdfService->generateMemberCertificates($members, [
                                    'certificate_type' => $data['certificate_type'] ?? 'membership',
                                    'pastor_name' => $data['pastor_name'] ?? 'Apostle Chris Siame',
                                ]);
                                $filename = ($data['certificate_type'] ?? 'membership') . '-certificates-' . now()->format('Y-m-d') . '.pdf';
                                break;

                            default: // list
                                $pdf = $pdfService->generateMemberList($members, [
                                    'title' => $data['title'],
                                    'include_contact' => $data['include_contact'],
                                    'include_spiritual' => $data['include_spiritual'],
                                ]);
                                $filename = 'member-list-' . now()->format('Y-m-d') . '.pdf';
                                break;
                        }

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $filename, [
                            'Content-Type' => 'application/pdf',
                        ]);
                    }),

                Tables\Actions\CreateAction::make()
                    ->before(function (array $data) {
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                    // Bulk CSV Export Action
                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Export Selected (CSV)')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(function ($records) {
                            $csvContent = self::generateCsvExport($records);

                            return response()->streamDownload(function () use ($csvContent) {
                                echo $csvContent;
                            }, 'selected-members-export-' . now()->format('Y-m-d-H-i-s') . '.csv', [
                                'Content-Type' => 'text/csv',
                            ]);
                        }),

                    // Bulk PDF Export Action
                    Tables\Actions\BulkAction::make('exportSelectedPdf')
                        ->label('Export Selected (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->label('Report Title')
                                ->default('Selected Members')
                                ->required(),
                            Forms\Components\Select::make('format')
                                ->label('Format')
                                ->options([
                                    'list' => 'Member List',
                                    'cards' => 'Member Cards',
                                ])
                                ->default('list')
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $pdfService = new MemberPdfService();

                            switch ($data['format']) {
                                case 'cards':
                                    $pdf = $pdfService->generateMemberCards($records, [
                                        'card_style' => $data['card_style'] ?? 'professional',
                                        'include_contact' => $data['include_contact'] ?? true,
                                    ]);
                                    $filename = 'member-cards-' . ($data['card_style'] ?? 'professional') . '-' . now()->format('Y-m-d') . '.pdf';
                                    break;

                                case 'badges':
                                    $pdf = $pdfService->generateMemberBadges($records);
                                    $filename = 'member-badges-' . now()->format('Y-m-d') . '.pdf';
                                    break;

                                case 'certificates':
                                    $pdf = $pdfService->generateMemberCertificates($records, [
                                        'certificate_type' => $data['certificate_type'] ?? 'membership',
                                        'pastor_name' => $data['pastor_name'] ?? 'Apostle Chris Siame',
                                    ]);
                                    $filename = ($data['certificate_type'] ?? 'membership') . '-certificates-' . now()->format('Y-m-d') . '.pdf';
                                    break;

                                default: // list
                                    $pdf = $pdfService->generateMemberList($records, [
                                        'title' => $data['title'],
                                        'include_contact' => $data['include_contact'] ?? true,
                                    ]);
                                    $filename = 'selected-members-' . now()->format('Y-m-d') . '.pdf';
                                    break;
                            }

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, $filename, [
                                'Content-Type' => 'application/pdf',
                            ]);
                        }),
                ]),
            ]);
    }

    /**
     * Generate CSV content for export
     */
    private static function generateCsvExport($members): string
    {
        $headers = [
            'Registration Number',
            'Title',
            'First Name',
            'Last Name',
            'Full Name',
            'Date of Birth',
            'Age',
            'Gender',
            'Phone',
            'Alternative Phone',
            'Email',
            'Address',
            'Branch',
            'Cell Group',
            'Membership Status',
            'Membership Date',
            'Marital Status',
            'Occupation',
            'Employer',
            'Salvation Date',
            'Baptism Date',
            'Baptism Type',
            'Membership Class Status',
            'Foundation Class Status',
            'Leadership Class Status',
            'Emergency Contact Name',
            'Emergency Contact Phone',
            'Previous Church',
            'Previous Church Pastor',
            'Skills & Talents',
            'Interests',
            'Special Needs',
            'Is Active',
            'Deactivation Reason',
            'Notes',
            'Created At',
            'Updated At',
        ];

        $csv = '"' . implode('","', $headers) . '"' . "\n";

        foreach ($members as $member) {
            $row = [
                $member->registration_number ?? '',
                $member->title ?? '',
                $member->first_name ?? '',
                $member->last_name ?? '',
                $member->full_name ?? '',
                $member->date_of_birth ? Carbon::parse($member->date_of_birth)->format('Y-m-d') : '',
                $member->date_of_birth ? Carbon::parse($member->date_of_birth)->age : '',
                $member->gender ?? '',
                $member->phone ?? '',
                $member->alternative_phone ?? '',
                $member->email ?? '',
                $member->address ?? '',
                $member->branch->name ?? '',
                $member->cellGroup->name ?? '',
                $member->membership_status ?? '',
                $member->membership_date ? Carbon::parse($member->membership_date)->format('Y-m-d') : '',
                $member->marital_status ?? '',
                $member->occupation ?? '',
                $member->employer ?? '',
                $member->salvation_date ? Carbon::parse($member->salvation_date)->format('Y-m-d') : '',
                $member->baptism_date ? Carbon::parse($member->baptism_date)->format('Y-m-d') : '',
                $member->baptism_type ?? '',
                $member->membership_class_status ?? '',
                $member->foundation_class_status ?? '',
                $member->leadership_class_status ?? '',
                $member->emergency_contact_name ?? '',
                $member->emergency_contact_phone ?? '',
                $member->previous_church ?? '',
                $member->previous_church_pastor ?? '',
                $member->skills_talents ?? '',
                $member->interests ?? '',
                $member->special_needs ?? '',
                $member->is_active ? 'Yes' : 'No',
                $member->deactivation_reason ?? '',
                $member->notes ?? '',
                $member->created_at ? $member->created_at->format('Y-m-d H:i:s') : '',
                $member->updated_at ? $member->updated_at->format('Y-m-d H:i:s') : '',
            ];

            // Escape and wrap each field in quotes
            $escapedRow = array_map(function ($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row);

            $csv .= implode(',', $escapedRow) . "\n";
        }

        return $csv;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
