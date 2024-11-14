<?php

namespace App\Filament\Widgets;

use App\Models\CampaignType;
use App\Models\Checkin;
use App\Models\Contact;
use App\States\Availed;
use App\States\FirstState;
use App\States\ForTripping;
use App\States\Registered;
use App\States\Undecided;
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
        $data_campaigns = $this->filters['campaigns'] ?? null;
        $data_start = $this->filters['start_date'] ?? null;
        $data_end = $this->filters['end_date'] ?? null;

         // Stats
         $total_accounts = 0;
         $registered = 0;
         $not_now = 0;
         $for_tripping = 0;
         $availed = 0;
         $consulted = 0;

         $total_accounts_percent = 0;
         $registered_percent = 0;
         $not_now_percent = 0;
         $for_tripping_percent = 0;
         $availed_percent = 0;
         $consulted_percent = 0;
 
         // Campaign Type Ids
         $presentation_id = CampaignType::where('name', 'Presentation')->first()->id;
         $booth_id = CampaignType::where('name', 'Booth')->first()->id;
         $site_id = CampaignType::where('name', 'Site Visit')->first()->id;

         $model = new Contact();

         if (!empty($data_campaigns)){
            if (in_array('All', $data_campaigns)) { // All Campaign
                   if($data_start != null && $data_end != null){ // All Campaign with date filter
                       $registered = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                                           ->whereIn('state', [Registered::class, FirstState::class])->count();
                       $not_now = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                                           ->where('state', Undecided::class)->count();
                       $for_tripping = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                                           ->where('state', ForTripping::class)->count();
                       $availed = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                                           ->where('state', Availed::class)->count();
                       $consulted = 0; // TODO: Data source for consulted. No State for consulted
                   }else{ // All Campaign without date filter
                       $registered = $model->whereIn('state', [Registered::class, FirstState::class])->count();
                       $not_now = $model->where('state', Undecided::class)->count();
                       $for_tripping = $model->where('state', ForTripping::class)->count();
                       $availed = $model->where('state', Availed::class)->count();
                       $consulted = 0; // TODO: Data source for consulted. No State for consulted
                   }
               }else{ // Selected Campaign
                   if($data_start != null && $data_end != null){ // Selected Campaign with date filter
                       $date_filtered_model = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59']);
                       $registered = $date_filtered_model->whereIn('state', [Registered::class, FirstState::class])
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $not_now = $date_filtered_model->where('state', Undecided::class)
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $for_tripping = $date_filtered_model->where('state', ForTripping::class)
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $availed = $date_filtered_model->where('state', Availed::class)
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $consulted = 0; // TODO: Data source for consulted. No State for consulted
                   }else{ // Selected Campaign without date filter
                       $registered = $model->whereIn('state', [Registered::class, FirstState::class])
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $not_now = $model->where('state', Undecided::class)
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $for_tripping = $model->where('state', ForTripping::class)
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $availed = $model->where('state', Availed::class)
                                       ->whereHas('campaign', function ($query) use($data_campaigns) {
                                           $query->whereIn('id', $data_campaigns);
                                       })->count();
                       $consulted = 0; // TODO: Data source for consulted. No State for consulted
                   }
               }
           }else if($data_start != null && $data_end != null){ // Date filter only
               $registered = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                               ->whereIn('state', [Registered::class, FirstState::class])
                               ->count();
               $not_now = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                               ->where('state', Undecided::class)
                               ->count();
               $for_tripping = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                               ->where('state', ForTripping::class)
                               ->count();
               $availed = $model->whereBetween('created_at', [$data_start.' 00:00:00', $data_end.' 23:59:59'])
                               ->where('state', Availed::class)
                               ->count();
               $consulted = 0; // TODO: Data source for consulted. No State for consulted
           }else{ // Default
               $registered = $model->whereIn('state', [Registered::class, FirstState::class])
                               ->count();
               $not_now = $model->where('state', Undecided::class)
                               ->count();
               $for_tripping = $model->where('state', ForTripping::class)
                               ->count();
               $availed = $model->where('state', Availed::class)
                               ->count();
               $consulted = 0; // TODO: Data source for consulted. No State for consulted
           }

        $total_accounts = $registered + $not_now + $for_tripping + $availed + $consulted;

        if($total_accounts){
            $registered_percent = number_format(($registered / $total_accounts) * 100, 1);
            $not_now_percent = number_format(($not_now / $total_accounts) * 100, 1);
            $for_tripping_percent = number_format(($for_tripping / $total_accounts) * 100, 1);
            $availed_percent = number_format(($availed / $total_accounts) * 100, 1);
            $consulted_percent = number_format(($consulted / $total_accounts) * 100, 1);
        }

        return [
            'labels' => [
                'Registered',
                'Not Now',
                'For Tripping',
                'Availed',
                'Consulted',
            ],
            'datasets' => [
                [
                    'label' => '',
                    'data' => [$registered, $not_now, $for_tripping, $availed, $consulted],
                    'backgroundColor' => [
                        'rgba(76, 175, 80, 1)',
                        'rgba(24, 104, 255, 1)',
                        'rgba(252, 177, 21, 1)',
                        'rgba(174, 60, 216, 1)',
                        'rgba(225, 112, 85, 1)',
                    ],
                    'hoverOffset' => 4,
                    'cutout' => '40%'
                ]
            ],
        ];
    }

    protected static ?array $options = [
        'plugins' => [
            'responsive' => true,
                'maintainAspectRatio' => false,
            'legend' => [
                'display' => true,
            ],
            'datalabels' => [
                  'color' => '#DE3163', // Text color
                  'font' =>  [
                    'weight' => 'bold', // Text font weight
                  ],
                  'anchor' => 'center',
                  'align' => 'center',
                  // Position text within the segments
                  'offset' => '-10',
                ],
        ],
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }
}
