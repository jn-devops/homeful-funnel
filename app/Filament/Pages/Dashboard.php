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
use App\Filament\Widgets\StatsOverviewV2;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public $campaigns;
    public $start;
    public $end;

    public function getWidgets(): array
    {
        return [
            CampaignAnalysisChart::class,
            // StatsOverview::class,
            StatsOverviewV2::class,
            CampaignsTable::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 1;
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
                    ])
                    ->afterStateUpdated(function ($state) {
                        $this->campaigns = $state;
                        $this->updateWidgets();
                    }),
                DatePicker::make('start_date')
                        ->afterStateUpdated(function ($state) {
                            $this->start = $state;
                            $this->updateWidgets();
                        }),
                DatePicker::make('end_date')
                        ->afterStateUpdated(function ($state) {
                            $this->end = $state;
                            $this->updateWidgets();
                        }),
            ])->columns(3),
        ]);
    }

    protected function updateWidgets()
    {
        $this->dispatch("filtersUpdated", [
            'campaigns' => $this->campaigns,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }

}
