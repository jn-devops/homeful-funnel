<?php

namespace App\Filament\Widgets;

use App\Models\CampaignType;
use App\Models\Checkin;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class StatsOverviewV2 extends Widget
{
    use InteractsWithPageFilters;
    protected static string $view = 'filament.widgets.stats-overview-v2';
    public Array $data;

    public function mount()
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

         $this->data['total_accounts'] = $total_accounts;
         $this->data['visited_corp_presentation'] = $visited_corp_presentation;
         $this->data['visited_booth'] = $visited_booth;
         $this->data['visited_site'] = $visited_site;
         $this->data['consulted'] = $consulted;

    }
    
}
