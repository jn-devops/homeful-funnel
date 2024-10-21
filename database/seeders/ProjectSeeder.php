<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            'Pasinaya',
            'Pagsikat',
            'Pagsibol',
        ];
        foreach ($data as $index => $d) {
            Project::updateOrCreate( ['name' => $d]);
        }
    }
}
