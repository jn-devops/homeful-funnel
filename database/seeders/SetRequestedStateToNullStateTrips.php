<?php

namespace Database\Seeders;

use App\Models\Trips;
use App\States\TrippingRequested;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetRequestedStateToNullStateTrips extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(Trips::whereNull('state')->get() as $trip){
            $trip->state = 'App\\States\\TrippingRequested';
            $trip->save();
        }
    }
}
