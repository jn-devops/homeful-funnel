<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicOrgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "Public"
        ];

        foreach ($data as $index => $d) {
            Organization::updateOrCreate(['name' => $d]);
        }
    }
}
