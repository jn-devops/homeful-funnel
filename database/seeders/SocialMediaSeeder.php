<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            'fb' => 'Facebook',
            'ig' => 'Instagram',
            'tt' => 'TikTok',
            'tw' => 'X (Twitter)',
            'in' => 'LinkedIn',
        ];

        foreach ($platforms as $code => $description) {
            SocialMedia::updateOrCreate(
                ['code' => $code],
                ['description' => $description]
            );
        }
    }
}
