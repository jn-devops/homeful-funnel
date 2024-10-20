<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'SM Investments Corporation',
            'BDO Unibank, Inc.',
            'Ayala Corporation',
            'JG Summit Holdings, Inc.',
            'San Miguel Corporation',
            'PLDT Inc.',
            'Metropolitan Bank & Trust Company (Metrobank)',
            'Universal Robina Corporation',
            'Aboitiz Equity Ventures, Inc.',
            'Bank of the Philippine Islands (BPI)',
            'Globe Telecom, Inc.',
            'Manila Electric Company (MERALCO)',
            'Jollibee Foods Corporation',
            'DMCI Holdings, Inc.',
            'LT Group, Inc.',
            'International Container Terminal Services, Inc. (ICTSI)',
            'First Gen Corporation',
            'Alliance Global Group, Inc.',
            'Robinsons Retail Holdings, Inc.',
            'Security Bank Corporation',
            'Semirara Mining and Power Corporation',
            'Puregold Price Club, Inc.',
            'Filinvest Land, Inc.',
            'Megaworld Corporation',
            'Metro Pacific Investments Corporation',
            'Cebu Air, Inc.',
            'Phoenix Petroleum Philippines, Inc.',
            'Nickel Asia Corporation',
            'Philippine National Bank',
            'Alsons Consolidated Resources, Inc.',
            'Vista Land & Lifescapes, Inc.',
            'Century Pacific Food, Inc.',
            'D&L Industries, Inc.',
            'DoubleDragon Properties Corp.',
            'China Banking Corporation',
            'Manila Water Company, Inc.',
            'Union Bank of the Philippines',
            'Integrated Micro-Electronics, Inc.',
            'Emperador Inc.',
            'Shang Properties, Inc.',
            'Pilmico Foods Corporation',
            'Asia United Bank Corporation',
            'Philex Mining Corporation',
            'AllHome Corp.',
            'Sta. Lucia Land, Inc.',
            'SPC Power Corporation',
            'Lepanto Consolidated Mining Company',
            'AllDay Supermarket Inc.',
            'Boulevard Holdings, Inc.',
            'Apex Mining Co., Inc.'
        ];

        foreach ($data as $index => $d) {
            Organization::updateOrCreate(['name' => $d]);
        }

    }
}
