<?php

namespace App\Filament\Widgets;

use App\Models\CampaignType;
use App\Models\Checkin;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{

    use InteractsWithPageFilters;

    protected function getStats(): array
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
                Stat::make('Total Accounts', $total_accounts)
                    ->icon('heroicon-s-user-group')
                    ->iconPosition('start')
                    ->iconColor('warning')
                    ->color('success'),
                Stat::make('Visited Corp Presentation', $visited_corp_presentation)
                    ->icon('heroicon-c-presentation-chart-bar')
                    ->iconPosition('start')
                    ->iconColor('success')
                    ->color('success'),
                Stat::make('Visited Booth', $visited_booth)
                    ->icon('heroicon-m-building-storefront')
                    ->iconPosition('start')
                    ->iconColor('blue')
                    ->color('success'),
                Stat::make('Visited Site', $visited_site)
                    ->icon('heroicon-s-map-pin')
                    ->iconPosition('start')
                    ->iconColor('blue')
                    ->color('success'),
                Stat::make('Consulted', $consulted)
                    ->icon('heroicon-m-chat-bubble-bottom-center')
                    ->iconPosition('start')
                    ->iconColor('warning')
                    ->color('success'),
        ];
    }
}
