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

        // Create stats with state names and counts
        return collect($stateCounts)->map(function ($data) {
            return Stat::make($data['name'], $data['count']);
        })->values()->all();
    }

}
