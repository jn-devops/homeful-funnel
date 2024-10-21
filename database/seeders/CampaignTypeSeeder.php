<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            'Presentation',
            'Booth',
            'Site Visit',
        ];
        foreach ($data as $index => $d) {
            CampaignType::updateOrCreate( ['name' => $d]);
        }
    }
}
