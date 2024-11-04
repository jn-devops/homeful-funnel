<x-filament-panels::page>
    <div class="flex justify-center">
        <x-filament::tabs>
            <!-- Prospects Tab -->
            <x-filament::tabs.item
                label="Prospects"
                icon="heroicon-o-user-circle"
                :badge="\App\Models\Contact::count()"
                wire:click="$set('activeTab', 'prospects')"
            >
                Prospects
            </x-filament::tabs.item>

            <!-- Registered Tab -->
            <x-filament::tabs.item
                label="Registered"
                icon="heroicon-o-qr-code"
                :badge="\App\Models\Checkin::count()"
                wire:click="$set('activeTab', 'registered')"
            >
                Registered

            </x-filament::tabs.item>

            <!-- Tripping Requests Tab -->
            <x-filament::tabs.item
                label="Tripping Requests"
                icon="heroicon-o-document-text"
                :badge="\App\Models\Checkin::count()"
                wire:click="$set('activeTab', 'tripping')"
            >
                Tripping Requests

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
