<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceRecordResource\Pages;
use App\Filament\Resources\AttendanceRecordResource\RelationManagers;
use App\Models\AttendanceRecord;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceRecordResource extends Resource
{
    protected static ?string $model = AttendanceRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Attendance Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('service_id')
                    ->relationship('service', 'service_type')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('member_id')
                    ->relationship('member', 'first_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('attendance_type')
                    ->options([
                        'Regular' => 'Regular',
                        'Visitor' => 'Visitor',
                        'First Timer' => 'First Timer',
                        'Online' => 'Online'
                    ])
                    ->required(),

                DateTimePicker::make('check_in_time')
                    ->required(),

                DateTimePicker::make('check_out_time'),

                TextInput::make('visitor_name'),

                TextInput::make('visitor_phone')
                    ->tel(),

                Textarea::make('visitor_address'),

                Select::make('age_group')
                    ->options([
                        'Adult' => 'Adult',
                        'Youth' => 'Youth', 
                        'Child' => 'Child'
                    ]),

                Select::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female'
                    ]),

                TextInput::make('previous_church'),

                Toggle::make('follow_up_required'),

                Textarea::make('follow_up_notes'),

                Textarea::make('notes')
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('service.service_type')
                ->sortable()
                ->searchable(),
                
            TextColumn::make('member.first_name')
                ->label('Member Name')
                ->sortable()
                ->searchable(),
                
            TextColumn::make('attendance_type')
                ->sortable(),
                
            TextColumn::make('check_in_time')
                ->dateTime()
                ->sortable(),
                
            TextColumn::make('visitor_name')
                ->searchable(),
                
            ToggleColumn::make('follow_up_required')
        ])
        ->filters([
            SelectFilter::make('branch')
                ->relationship('branch', 'name'),
                
            SelectFilter::make('attendance_type')
                ->options([
                    'Regular' => 'Regular',
                    'Visitor' => 'Visitor', 
                    'First Timer' => 'First Timer',
                    'Online' => 'Online'
                ])
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAttendanceRecords::route('/'),
            'create' => Pages\CreateAttendanceRecord::route('/create'),
            'view' => Pages\ViewAttendanceRecord::route('/{record}'),
            'edit' => Pages\EditAttendanceRecord::route('/{record}/edit'),
        ];
    }
}
