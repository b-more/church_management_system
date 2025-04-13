<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use App\Filament\Actions\GenerateReportAction;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Financial Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Transaction Details')
                ->schema([
                    Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required()
                        ->searchable(),

                    Select::make('transaction_type')
                        ->options([
                            'tithe' => 'Tithe',
                            'offering' => 'Offering',
                            'expense' => 'Expense',
                            'donation' => 'Donation',
                        ])
                        ->required(),

                    TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('ZMW'),

                    Select::make('payment_method')
                        ->options([
                            'cash' => 'Cash',
                            'mobile_money' => 'Mobile Money',
                            'bank_transfer' => 'Bank Transfer',
                        ])
                        ->required(),

                    TextInput::make('payment_reference')
                        ->placeholder('Enter payment reference number'),

                    DateTimePicker::make('transaction_date')
                        ->required()
                        ->default(now()),

                    Select::make('member_id')
                        ->relationship('member', 'first_name')
                        ->searchable()
                        ->preload(),

                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable(),

                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'failed' => 'Failed',
                            'reversed' => 'Reversed',
                        ])
                        ->required()
                        ->default('completed'),

                    TextInput::make('description')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('notes')
                        ->rows(3),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                GenerateReportAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tithe' => 'success',
                        'offering' => 'info',
                        'expense' => 'danger',
                        'donation' => 'warning',
                        'projects' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->money('ZMW')
                    ->sortable(),

                Tables\Columns\TextColumn::make('member.full_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->badge(),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'reversed' => 'info',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type'),
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('payment_method'),
                Tables\Filters\Filter::make('transaction_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('transaction_date', 'desc');
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
