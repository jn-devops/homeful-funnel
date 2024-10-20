<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            'Seminar',
            'Conference',
            'Workshop',
            'Meeting',
            'Training',
            'Webinar',
            'Presentation',
        ];
        foreach ($data as $index => $d) {
            Campaign::updateOrCreate( ['name' => $d]);
        }
    }
}
