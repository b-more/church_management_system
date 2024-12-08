<?php
namespace App\Filament\Resources;

use App\Filament\Resources\GrowthTrackRecordResource\Pages;
use App\Models\GrowthTrackRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class GrowthTrackRecordResource extends Resource
{
    protected static ?string $model = GrowthTrackRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Growth & Development';
    protected static ?string $recordTitleAttribute = 'track_type';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Track Information')
                ->description('Basic track details')
                ->schema([
                    Grid::make(3)->schema([
                        Select::make('member_id')
                            ->relationship('member', 'first_name')
                            ->required()
                            ->searchable(),
                        Select::make('track_type')
                            ->options([
                                'Membership' => 'Membership Class',
                                'Foundation' => 'Foundation School',
                                'Leadership' => 'Leadership Training',
                            ])
                            ->required(),
                        Select::make('instructor_id')
                            ->relationship('instructor', 'first_name')
                            ->searchable(),
                    ]),
                ]),

            Section::make('Progress')
                ->schema([
                    Grid::make(3)->schema([
                        DatePicker::make('start_date')
                            ->required(),
                        DatePicker::make('completion_date'),
                        Select::make('status')
                            ->options([
                                'Not Started' => 'Not Started',
                                'In Progress' => 'In Progress',
                                'Completed' => 'Completed',
                            ])
                            ->default('Not Started')
                            ->required(),
                    ]),
                ]),

            Section::make('Completion Details')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('score')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                        Toggle::make('certificate_issued')
                            ->visible(fn ($get) => $get('status') === 'Completed'),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('certificate_number')
                            ->visible(fn ($get) => $get('certificate_issued')),
                        Textarea::make('notes')
                            ->rows(2),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.first_name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('track_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Membership' => 'info',
                        'Foundation' => 'warning',
                        'Leadership' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('instructor.first_name')
                    ->label('Instructor'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('completion_date')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Not Started' => 'gray',
                        'In Progress' => 'warning',
                        'Completed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('score')
                    ->suffix('%'),
                Tables\Columns\IconColumn::make('certificate_issued')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('track_type'),
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('instructor')
                    ->relationship('instructor', 'first_name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Growth Track Records')
            ->emptyStateDescription('Start by creating a new growth track record.')
            ->emptyStateIcon('heroicon-o-academic-cap');
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
            'index' => Pages\ListGrowthTrackRecords::route('/'),
            'create' => Pages\CreateGrowthTrackRecord::route('/create'),
            'edit' => Pages\EditGrowthTrackRecord::route('/{record}/edit'),
        ];
    }
}
