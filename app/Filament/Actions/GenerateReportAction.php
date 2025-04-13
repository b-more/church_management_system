<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Http\Request;
use App\Http\Controllers\Reports\FinancialReportController;

class GenerateReportAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'generate_report';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Generate Report')
            ->icon('heroicon-o-document-text')
            ->form([
                Select::make('report_type')
                    ->label('Report Type')
                    ->options([
                        'financial' => 'Financial Report',
                        'tithe' => 'Tithe Report',
                        'offering' => 'Offering Report',
                    ])
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required(),
            ])
            ->action(function (array $data) {
                $controller = new FinancialReportController();
                $request = new Request($data);

                return match ($data['report_type']) {
                    'financial' => $controller->generate($request),
                    'tithe' => $controller->generateTitheReport($request),
                    'offering' => $controller->generateOfferingReport($request),
                };
            });
    }
}
