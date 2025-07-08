<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Projects Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Details')
                    ->schema([
                        Forms\Components\Select::make('branch_id')
                            ->label('Branch')
                            ->options(Branch::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('project_category_id')
                            ->label('Category')
                            ->options(ProjectCategory::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Financial Information')
                    ->schema([
                        Forms\Components\TextInput::make('target_amount')
                            ->label('Target Amount (K)')
                            ->numeric()
                            ->required()
                            ->prefix('K')
                            ->step(0.01),

                        Forms\Components\TextInput::make('current_amount')
                            ->label('Current Amount (K)')
                            ->numeric()
                            ->prefix('K')
                            ->step(0.01)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Timeline')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->default(today()),

                        Forms\Components\DatePicker::make('end_date')
                            ->after('start_date'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'paused' => 'Paused',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(1000),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('projectCategory.name')
                    ->label('Category')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('target_amount')
                    ->label('Target')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_amount')
                    ->label('Raised')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->color(fn ($state) => match(true) {
                        $state >= 100 => 'success',
                        $state >= 75 => 'info',
                        $state >= 50 => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'primary' => 'completed',
                        'warning' => 'paused',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('project_category_id')
                    ->label('Category')
                    ->options(ProjectCategory::pluck('name', 'id')),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'paused' => 'Paused',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Project $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (Project $record) {
                        $record->update(['status' => 'completed']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Project Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('branch.name')
                            ->label('Branch'),
                        Infolists\Components\TextEntry::make('projectCategory.name')
                            ->label('Category'),
                        Infolists\Components\TextEntry::make('description'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Financial Progress')
                    ->schema([
                        Infolists\Components\TextEntry::make('target_amount')
                            ->label('Target Amount')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('current_amount')
                            ->label('Amount Raised')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('remaining_amount')
                            ->label('Remaining')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('progress_percentage')
                            ->label('Progress')
                            ->formatStateUsing(fn ($state) => $state . '%'),
                        Infolists\Components\TextEntry::make('total_pledged')
                            ->label('Total Pledged')
                            ->money('ZMW'),
                        Infolists\Components\TextEntry::make('total_received')
                            ->label('Total Received')
                            ->money('ZMW'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Timeline & Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('start_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('end_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'completed' => 'primary',
                                'paused' => 'warning',
                                'cancelled' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('is_overdue')
                            ->label('Overdue')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                            ->color(fn ($state) => $state ? 'danger' : 'success'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            //'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }
}
