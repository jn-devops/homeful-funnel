<?php

namespace App\Filament\Widgets;

use App\Models\CampaignType;
use App\Models\Checkin;
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
        // Form
        $campaigns = $this->filters['campaigns'] ?? null;
        $start = $this->filters['start_date'] ?? null;
        $end = $this->filters['end_date'] ?? null;

        // Stats
        $total_accounts = 0;
        $visited_corp_presentation = 0;
        $visited_booth = 0;
        $visited_site = 0;
        $consulted = 0; // TODO: No Data Source

        // Campaign Type Ids
        $presentation_id = CampaignType::where('name', 'Presentation')->first()->id;
        $booth_id = CampaignType::where('name', 'Booth')->first()->id;
        $site_id = CampaignType::where('name', 'Site Visit')->first()->id;

        if (!empty($campaigns)){
            if (in_array('All', $campaigns)) { // All Campaign
                $model = new Checkin();
                if($start != null && $end != null){ // All Campaign with date filter
                    $total_accounts = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])->get()->count();
                    $visited_corp_presentation = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                                    ->whereHas('campaign', function ($query) use($presentation_id) {
                                                        $query->where('campaign_type_id', $presentation_id);
                                                    })->get()->count();
                    $visited_booth = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                                    ->whereHas('campaign', function ($query) use($booth_id) {
                                                        $query->where('campaign_type_id', $booth_id);
                                                    })->get()->count();
                    $visited_site = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                                    ->whereHas('campaign', function ($query) use($site_id) {
                                                        $query->where('campaign_type_id', $site_id);
                                                    })->get()->count();
                }else{ // All Campaign without date filter
                    $total_accounts = $model->all()->count();
                    $visited_corp_presentation = $model->whereHas('campaign', function ($query) use($presentation_id) {
                                                    $query->where('campaign_type_id', $presentation_id);
                                                })->get()->count();
                    $visited_booth = $model->whereHas('campaign', function ($query) use($booth_id) {
                                                    $query->where('campaign_type_id', $booth_id);
                                                })->get()->count();
                    $visited_site = $model->whereHas('campaign', function ($query) use($site_id) {
                                                    $query->where('campaign_type_id', $site_id);
                                                })->get()->count();
                }
            }else{ // Selected Campaign
                $model = new Checkin();
                if($start != null && $end != null){ // Selected Campaign with date filter
                    $total_accounts = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                            ->whereHas('campaign', function ($query) use($campaigns) {
                                                $query->whereHas('project', function ($query2) use($campaigns) {
                                                    $query2->whereIn('name', $campaigns);
                                                });
                                            })->get()->count();
                    $visited_corp_presentation = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                                    ->whereHas('campaign', function ($query) use($campaigns, $presentation_id) {
                                                        $query->whereHas('project', function ($query2) use($campaigns) {
                                                                    $query2->whereIn('name', $campaigns);
                                                                })
                                                                ->where('campaign_type_id', $presentation_id);
                                                    })->get()->count();
                    $visited_booth = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                                    ->whereHas('campaign', function ($query) use($campaigns, $booth_id) {
                                                        $query->whereHas('project', function ($query2) use($campaigns) {
                                                                    $query2->whereIn('name', $campaigns);
                                                                })
                                                                ->where('campaign_type_id', $booth_id);
                                                    })->get()->count();
                    $visited_site = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                                                    ->whereHas('campaign', function ($query) use($campaigns, $site_id) {
                                                        $query->whereHas('project', function ($query2) use($campaigns) {
                                                                    $query2->whereIn('name', $campaigns);
                                                                })
                                                                ->where('campaign_type_id', $site_id);
                                                    })->get()->count();

                }else{ // Selected Campaign without date filter
                    $total_accounts = $model->whereHas('campaign', function ($query) use($campaigns) {
                                                $query->whereHas('project', function ($query2) use($campaigns) {
                                                    $query2->whereIn('name', $campaigns);
                                                });
                                            })->get()->count();
                    $visited_corp_presentation = $model->whereHas('campaign', function ($query) use($campaigns, $presentation_id) {
                                                    $query->whereHas('project', function ($query2) use($campaigns) {
                                                                $query2->whereIn('name', $campaigns);
                                                            })
                                                            ->where('campaign_type_id', $presentation_id);
                                                })->get()->count();
                    $visited_booth = $model->whereHas('campaign', function ($query) use($campaigns, $booth_id) {
                                                    $query->whereHas('project', function ($query2) use($campaigns) {
                                                                $query2->whereIn('name', $campaigns);
                                                            })
                                                            ->where('campaign_type_id', $booth_id);
                                                })->get()->count();
                    $visited_site = $model->whereHas('campaign', function ($query) use($campaigns, $site_id) {
                                                    $query->whereHas('project', function ($query2) use($campaigns) {
                                                                $query2->whereIn('name', $campaigns);
                                                            })
                                                            ->where('campaign_type_id', $site_id);
                                                })->get()->count();
                }


            }
        }else if($start != null && $end != null){ // Date filter only
            $model = new Checkin();
            $model = $model->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
            $total_accounts = $model->get()->count();
            $visited_corp_presentation = $model->whereHas('campaign', function ($query) use ($presentation_id) {
                                        $query->where('campaign_type_id', $presentation_id);
                                    })->get()->count();
            $visited_booth = $model->whereHas('campaign', function ($query) use ($booth_id) {
                                        $query->where('campaign_type_id', $booth_id);
                                    })->get()->count();
            $visited_site = $model->whereHas('campaign', function ($query) use ($site_id) {
                                        $query->where('campaign_type_id', $site_id);
                                    })->get()->count();
        }else{ // Default
            $model = new Checkin();
            $total_accounts = $model->all()->count();
            $visited_corp_presentation = $model->whereHas('campaign', function ($query) use ($presentation_id) {
                                        $query->where('campaign_type_id', $presentation_id);
                                    })->get()->count();
            $visited_booth = $model->whereHas('campaign', function ($query) use ($booth_id) {
                                        $query->where('campaign_type_id', $booth_id);
                                    })->get()->count();
            $visited_site = $model->whereHas('campaign', function ($query) use ($site_id) {
                                        $query->where('campaign_type_id', $site_id);
                                    })->get()->count();
        }

        $total_accounts += $consulted;

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
                    'data' => [$visited_corp_presentation, $visited_booth, $visited_site, $consulted],
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
