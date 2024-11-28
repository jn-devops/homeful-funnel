<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\ProjectCampaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateCampaignOldData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(Campaign::all() as $campaign){
            if($campaign->project_id){
                ProjectCampaign::create([
                    'project_id' => $campaign->project_id,
                    'campaign_id' => $campaign->id,
                ]);
            }
        }
    }
}
