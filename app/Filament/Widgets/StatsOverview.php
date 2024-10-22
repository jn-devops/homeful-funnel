<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Checkin;
use App\Models\Organization;
use Illuminate\Support\HtmlString;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Support\Colors\Color;
use Filament\Pages\Actions\Grid;


class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        // $stats = Campaign::all()->map(function ($campaign) {
        //     $organizationCounts = $campaign->checkins()
        //         ->with('contact.organization') // Eager load the organization relation via contact
        //         ->get()
        //         ->groupBy('contact.organization_id') // Group the check-ins by organization
        //         ->map(function ($checkins, $organizationId) {
        //             // Return the count of contacts per organization
        //             return [
        //                 'org_name' => Organization::find($organizationId)->name??'',
        //                 'count' => $checkins->pluck('contact_id')->unique()->count(), // Get unique contacts per organization
        //             ];
        //         });

        //     $description = $organizationCounts->map(function ($org) {
        //         return $org['org_name']. ': ' . $org['count'] ;
        //     })->implode('<br>'); // Combine multiple organizations into a single string

        //     return Stat::make($campaign->name,  $campaign->checkins()->get()->count())
        //         ->description(new HtmlString($description))
        //         ->color('Success');
        // })->toArray();

        // return $stats;

        return [
                Stat::make('Total Accounts', '100')
                    ->icon('heroicon-s-user-group')
                    ->iconPosition('start')
                    ->color('success'),
                Stat::make('Visited Corp Presentation', '100')
                    ->icon('heroicon-c-presentation-chart-bar')
                    ->iconPosition('start')
                    ->color('success'),
                Stat::make('Visited Booth', '100')
                    ->icon('heroicon-m-building-storefront')
                    ->iconPosition('start')
                    ->color('success'),
                Stat::make('Visited Site', '100')
                    ->icon('heroicon-s-map-pin')
                    ->iconPosition('start')
                    ->color('success'),
                Stat::make('Consulted', '100')
                    ->icon('heroicon-m-chat-bubble-bottom-center')
                    ->iconPosition('start')
                    ->color('success'),
        ];
    }
}
