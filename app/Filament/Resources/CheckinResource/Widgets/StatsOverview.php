<?php

namespace App\Filament\Resources\CheckinResource\Widgets;

use App\Models\Campaign;
use App\Models\Checkin;
use App\Models\Organization;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = Campaign::all()->map(function ($campaign) {
            $organizationCounts = $campaign->checkins()
                ->with('contact.organization') // Eager load the organization relation via contact
                ->get()
                ->groupBy('contact.organization_id') // Group the check-ins by organization
                ->map(function ($checkins, $organizationId) {
                    // Return the count of contacts per organization
                    return [
                        'org_name' => Organization::find($organizationId)->name??'',
                        'count' => $checkins->pluck('contact_id')->unique()->count(), // Get unique contacts per organization
                    ];
                });

            $description = $organizationCounts->map(function ($org) {
                return $org['org_name']. ': ' . $org['count'] ;
            })->implode('<br>'); // Combine multiple organizations into a single string

            return Stat::make($campaign->name,  $campaign->checkins()->get()->count())
                ->description($description)
                ->color('Success');
        })->toArray();

        return $stats;
//        return [
//            Stat::make('Unique views', '192.1k')
//                ->description('32k increase')
//                ->color('success')
//                ->url('/'),
//            Stat::make('Bounce rate', '21%')
//                ->description('7% decrease')
//                ->descriptionIcon('heroicon-m-arrow-trending-down'),
//            Stat::make('Average time on page', '3:12')
//                ->description('3% increase')
//                ->descriptionIcon('heroicon-m-arrow-trending-up'),
//        ];
    }
}
