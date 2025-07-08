<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;

class RecentIncomeTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Income Transactions';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        $currentUser = Auth::user();
        $userBranch = $currentUser->branch_id ?? null;

        return $table
            ->query(
                Income::query()
                    ->when($userBranch, fn($q) => $q->where('branch_id', $userBranch))
                    ->with(['branch', 'offeringType', 'member', 'project'])
                    ->latest('date')
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('M j, Y')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('contributor_name')
                    ->label('Contributor')
                    ->getStateUsing(function (Income $record): string {
                        return $record->contributor_name ?: 'Anonymous';
                    })
                    ->searchable(['name'])
                    ->limit(20),

                Tables\Columns\TextColumn::make('offeringType.name')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tithe' => 'success',
                        'Offering' => 'info',
                        'Projects' => 'warning',
                        'Financial Partnership' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('ZMW')
                    ->sortable()
                    ->alignRight()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->limit(20)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->color('gray'),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->toggleable(isToggledHiddenByDefault: !is_null($userBranch))
                    ->visible(fn (): bool => is_null($userBranch)), // Only show if user can see all branches
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Income $record): string => route('filament.admin.resources.incomes.view', $record))
                    ->openUrlInNewTab(false),
            ])
            ->paginated(false)
            ->poll('30s')
            ->defaultSort('date', 'desc')
            ->emptyStateHeading('No recent transactions')
            ->emptyStateDescription('Income transactions will appear here once recorded.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public static function canView(): bool
    {
        return true; // Add your permission logic here if needed
    }
}
