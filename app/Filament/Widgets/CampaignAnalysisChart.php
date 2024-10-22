<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class CampaignAnalysisChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Chart';
    protected static ?string $maxHeight = '400px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $campaign = $this->filters['campaigns'] ?? null;
        $start = $this->filters['start_date'] ?? null;
        $end = $this->filters['end_date'] ?? null;

        // TODO: Get data in model here:

        return [
            'labels' => [
                'Visited Corp Presentation',
                'Visited Booth',
                'Visited Site',
                'Consulted',
            ],
            'datasets' => [
                [
                    'label' => 'My First Dataset',
                    'data' => [300, 50, 100, 70],
                    'backgroundColor' => [
                        'rgba(76, 175, 80, 1)',
                        'rgba(24, 104, 255, 1)',
                        'rgba(174, 60, 216, 1)',
                        'rgba(225, 112, 85, 1)',
                    ],
                    'hoverOffset' => 4,
                    'cutout' => '40%'
                ]
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
