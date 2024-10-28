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
            "Owens Asia",
            "Aprio Philippines",
            "Aidea Technologies Inc",
            "Hexaware Technologies",
            "Ozland Command Center",
            "Bases Conversion and Development Authority (BCDA)",
            "Global Gateway Development Corporation",
            "Total RISC Technology (TRT) Clark Philippines",
            "Task Us",
            "Sutherland",
            "Asurion",
            "The Backroom"
        ];

        foreach ($data as $index => $d) {
            Organization::updateOrCreate(['name' => $d]);
        }

    }
}
