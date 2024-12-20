<div class="flex">
    <div class="w-auto">
        <div class="flex flex-col space-y-2 px-3" x-data="{ tableFilters: @entangle('tableFilters') }" x-effect="console.log(tableFilters)">
            <x-filament::tabs label="Content tabs" class="w-full flex flex-col space-y-2" x-data="{ activeTab: 'Requests' }" >
                <x-filament::tabs.item
                    wire:click="$refresh"
                    alpine-active="activeTab === 'Requests'"
                    icon="heroicon-o-document-text"
                    x-on:click="$wire.set('tableFilters', {'state': {'value': 'App\\\\States\\\\TrippingRequested'}});activeTab = 'Requests'">
                    <x-slot name="badge">
                        {{ \App\Models\Trips::where('state','App\States\TrippingRequested')->count() + \App\Models\Trips::where('state', null)->count() }}
                    </x-slot>
                    Requests
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    wire:click="$refresh"
                    alpine-active="activeTab === 'Assigned'"
                    x-on:click="$wire.set('tableFilters', {'state': {'value': 'App\\\\States\\\\TrippingAssigned'}});activeTab = 'Assigned'"
                    icon="heroicon-o-user-circle">
                    <x-slot name="badge">
                        {{ \App\Models\Trips::where('state','App\States\TrippingAssigned')->count() }}
                    </x-slot>
                    Assigned
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    wire:click="$refresh"
                    alpine-active="activeTab === 'Confirmed'"
                    x-on:click="$wire.set('tableFilters', {'state': {'value': 'App\\\\States\\\\TrippingConfirmed'}});activeTab = 'Confirmed'"
                    icon="heroicon-o-clock">
                    <x-slot name="badge">
                        {{ \App\Models\Trips::where('state','App\States\TrippingConfirmed')->count() }}
                    </x-slot>
                    Confirmed
                </x-filament::tabs.item>
                
                <x-filament::tabs.item
                    wire:click="$refresh"
                    alpine-active="activeTab === 'Rescheduled'"
                    x-on:click="$wire.set('tableFilters', {'state': {'value': 'App\\\\States\\\\TrippingRescheduled'}});activeTab = 'Rescheduled'"
                    icon="heroicon-o-calendar-days">
                    <x-slot name="badge">
                        {{ \App\Models\Trips::where('state','App\States\TrippingRescheduled')->count() }}
                    </x-slot>
                    Re-Scheduled
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    wire:click="$refresh"
                    alpine-active="activeTab === 'Completed'"
                    x-on:click="$wire.set('tableFilters', {'state': {'value': 'App\\\\States\\\\TrippingCompleted'}});activeTab = 'Completed'"
                    icon="heroicon-o-check-circle">
                    <x-slot name="badge">
                        {{ \App\Models\Trips::where('state','App\States\TrippingCompleted')->count() }}
                    </x-slot>
                    Completed
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    wire:click="$refresh"
                    alpine-active="activeTab === 'Cancelled'"
                    x-on:click="$wire.set('tableFilters', {'state': {'value': 'App\\\\States\\\\TrippingCancelled'}});activeTab = 'Cancelled'"
                    icon="heroicon-o-x-mark">
                    <x-slot name="badge">
                        {{ \App\Models\Trips::where('state','App\States\TrippingCancelled')->count() }}
                    </x-slot>
                    Cancelled
                </x-filament::tabs.item>
            </x-filament::tabs>

        </div>
    </div>
    <div class="overflow-x-auto w-full">
        {{ $this->table}}
    </div>
</div>
