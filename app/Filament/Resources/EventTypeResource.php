<?php
namespace App\Filament\Resources;

use App\Filament\Resources\EventTypeResource\Pages;
use App\Models\EventType;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventTypeResource extends Resource
{
    protected static ?string $model = EventType::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?int $navigationSort = 24;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->description('Define the basic details of the event type')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter event type name'),
                        TextInput::make('code')
                            //->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('Enter unique code'),
                    ]),
                    Grid::make(2)->schema([
                        Select::make('category')
                            ->options([
                                'Conference' => 'Conference',
                                'Seminar' => 'Seminar',
                                'Summit' => 'Summit',
                                'Workshop' => 'Workshop',
                                'Retreat' => 'Retreat',
                                'Training' => 'Training',
                                'Meeting' => 'Meeting',
                                'Other' => 'Other',
                            ])
                            ->required()
                            ->searchable(),
                        Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Describe the event type'),
                    ]),
                ]),

            Section::make('Settings')
                ->description('Configure event type settings')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('registration_required')
                            ->label('Registration Required')
                            ->helperText('Enable if registration is mandatory')
                            ->default(true),
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->helperText('Deactivate to hide from event creation')
                            ->default(true),
                    ]),
                    Textarea::make('notes')
                        ->rows(2)
                        ->placeholder('Additional notes or instructions'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (EventType $record): string => $record->code),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Conference' => 'success',
                        'Seminar' => 'info',
                        'Workshop' => 'warning',
                        'Retreat' => 'danger',
                        'Training' => 'primary',
                        'Meeting' => 'secondary',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('registration_required')
                    ->boolean()
                    ->label('Registration'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Event Types')
            ->emptyStateDescription('Start by creating your first event type.')
            ->emptyStateIcon('heroicon-o-tag');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
            'index' => Pages\ListEventTypes::route('/'),
            'create' => Pages\CreateEventType::route('/create'),
            'edit' => Pages\EditEventType::route('/{record}/edit'),
        ];
    }
}
