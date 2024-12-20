<?php

namespace App\Filament\Resources\ContactResource\Widgets;

use App\Models\Contact;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactStateSummary extends BaseWidget
{
    protected function getColumns(): int
    {
        return 4; // Set to fit 6 widgets per row
    }

    protected function getStats(): array
    {
        // Group contacts by state, getting readable name and count
        $stateCounts = Contact::whereNotNull('state')
            ->get()
            ->groupBy('state')
            ->map(fn($contacts) => [
                'name' => $contacts->first()->state->name(),
                'count' => $contacts->count()
            ])
            ->toArray();

        return [
            Stat::make('Availed', $stateCounts['App\\States\\Availed']['count'] ?? 0),
            Stat::make('For Tripping', $stateCounts['App\\States\\ForTripping']['count'] ?? 0),
            Stat::make('Consulted', 0), // TODO: Count for Consulted State
            Stat::make('Not Now', $stateCounts['App\\States\\Undecided']['count']??0),
        ];

        // Create stats with state names and counts
        // return collect($stateCounts)->map(function ($data) {
        //     return Stat::make($data['name'], $data['count']);
        // })->values()->all();
    }

}
