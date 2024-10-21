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
            'Presentation',
            'Booth',
            'Site Visit',
        ];
        foreach ($data as $index => $d) {
            Campaign::updateOrCreate( ['name' => $d]);
        }
    }
}
