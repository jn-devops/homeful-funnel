<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\CampaignAnalysisChart;
use App\Filament\Widgets\CampaignsTable;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function getWidgets(): array
    {
        return [
            CampaignAnalysisChart::class,
            StatsOverview::class,
            CampaignsTable::class,
        ];
    }

    public function getColumns(): int
    {
        return 5;
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('')->schema([
                Select::make('campaigns')
                    ->multiple()
                    ->options([
                        'All' => 'All',
                        'Pasinaya' => 'Pasinaya',
                        'Pagsikat' => 'Pagsikat',
                        'Pagsibol' => 'Pagsibol',
                    ]),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
            ])->columns(3),
        ]);
    }

}
