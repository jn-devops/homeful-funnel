<?php

namespace App\Filament\Widgets;

use App\Models\CampaignType;
use App\Models\Checkin;
use App\States\Availed;
use App\States\FirstState;
use App\States\ForTripping;
use App\States\Registered;
use App\States\Undecided;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class StatsOverviewV2 extends Widget
{
    use InteractsWithPageFilters;
    protected static string $view = 'filament.widgets.stats-overview-v2';
    public Array $data;

    // protected $listeners = ['filtersUpdated' => 'applyFilters'];

    public function mount()
    {
        $this->campaigns = $this->filters['campaigns'] ?? null;
        $this->start = $this->filters['start'] ?? null;
        $this->end = $this->filters['end'] ?? null;

        $this->data_update();
    }

    public $campaigns;
    public $start;
    public $end;

    protected $listeners = ["filtersUpdated" => "updateFilters"];

    public function updateFilters($filters)
    {
        $this->campaigns = $this->filters['campaigns'] ?? null;
        $this->start = $this->filters['start'] ?? null;
        $this->end = $this->filters['end'] ?? null;

        $this->data_update();
    }

    public function data_update()
    {
        $data_campaigns = $this->campaigns;
        $data_start = $this->start;
        $data_end = $this->end;
 
         // Stats
         $total_accounts = 0;
         $registered = 0;
         $undecided = 0;
         $for_tripping = 0;
         $availed = 0;
         $consulted = 0;

         $total_accounts_percent = 0;
         $registered_percent = 0;
         $undecided_percent = 0;
         $for_tripping_percent = 0;
         $availed_percent = 0;
         $consulted_percent = 0;
 
         // Campaign Type Ids
         $presentation_id = CampaignType::where('name', 'Presentation')->first()->id;
         $booth_id = CampaignType::where('name', 'Booth')->first()->id;
         $site_id = CampaignType::where('name', 'Site Visit')->first()->id;

         $model = new Checkin();

         if (!empty($data_campaigns)){
            if (in_array('All', $data_campaigns)) { // All Campaign
                if($data_start != null && $data_end != null){ // All Campaign with date filter
                }else{ // All Campaign without date filter
                }
            }else{ // Selected Campaign
                if($data_start != null && $data_end != null){ // Selected Campaign with date filter
                }else{ // Selected Campaign without date filter
                }
            }
        }else if($data_start != null && $data_end != null){ // Date filter only
        }else{ // Default
            $registered = $model->whereHas('contact', function ($q) {
                                $q->whereIn('state', [Registered::class, FirstState::class]);
                            })->count();
            $undecided = $model->whereHas('contact', function ($q) {
                                $q->where('state', Undecided::class);
                            })->count();
            $for_tripping = $model->whereHas('contact', function ($q) {
                                $q->where('state', ForTripping::class);
                            })->count();
            $availed = $model->whereHas('contact', function ($q) {
                                $q->where('state', Availed::class);
                            })->count();
            $consulted = 0; // TODO: Data source for consulted. No State for consulted
        }

        $total_accounts = $registered + $undecided + $for_tripping + $availed + $consulted;

        if($total_accounts){
            $registered_percent = number_format(($registered / $total_accounts) * 100, 1);
            $undecided_percent = number_format(($undecided / $total_accounts) * 100, 1);
            $for_tripping_percent = number_format(($for_tripping / $total_accounts) * 100, 1);
            $availed_percent = number_format(($availed / $total_accounts) * 100, 1);
            $consulted_percent = number_format(($consulted / $total_accounts) * 100, 1);
        }

        $this->data['total_accounts'] = $total_accounts;
        $this->data['registered'] = $registered;
        $this->data['registered_percent'] = $registered_percent;
        $this->data['undecided'] = $undecided;
        $this->data['undecided_percent'] = $undecided_percent;
        $this->data['for_tripping'] = $for_tripping;
        $this->data['for_tripping_percent'] = $for_tripping_percent;
        $this->data['availed'] = $availed;
        $this->data['availed_percent'] = $availed_percent;
        $this->data['consulted'] = $consulted;
        $this->data['consulted_percent'] = $consulted_percent;

    }
    
}
