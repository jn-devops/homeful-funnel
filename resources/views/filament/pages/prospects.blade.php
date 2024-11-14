<x-filament-panels::page>
    <div class="flex justify-center">
        <x-filament::tabs x-data="{ activeTab: 'Prospects' }">
            <!-- Prospects Tab -->
            <x-filament::tabs.item
                label="Prospects"
                icon="heroicon-o-user-circle"
                :badge="\App\Models\Contact::count()"
                alpine-active="activeTab === 'Prospects'"
                wire:click="$set('activeTab', 'prospects');activeTab = 'Prospects'"
            >
                Prospects
            </x-filament::tabs.item>

            <!-- Registered Tab -->
            <x-filament::tabs.item
                label="Registered"
                icon="heroicon-o-qr-code"
                :badge="\App\Models\Checkin::count()"
                wire:click="$set('activeTab', 'registered');activeTab = 'Registered'"
                alpine-active="activeTab === 'Registered'"
            >
                Prospect Check-in Logs
            </x-filament::tabs.item>

            <!-- Tripping Requests Tab -->
            <x-filament::tabs.item
                label="Tripping"
                icon="heroicon-o-document-text"
                :badge="\App\Models\Trips::count()"
                wire:click="$set('activeTab', 'tripping');activeTab = 'Tripping Requests';"
                alpine-active="activeTab === 'Tripping Requests'"
            >
                Tripping
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>
    @if ($activeTab === 'prospects')
        @livewire('contact-table')
    @endif
    @if ($activeTab === 'registered')
        @livewire('checkin-table')
    @endif
    @if ($activeTab === 'tripping')
        @livewire('trips-table')
    @endif
</x-filament-panels::page>
