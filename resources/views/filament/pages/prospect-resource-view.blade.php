<x-filament-panels::page>
    <div class="flex justify-center">
        <x-filament::tabs x-data="{ activeTab: 'Personal Information' }">
            <!-- Personal Information Tab -->
            <x-filament::tabs.item
                label="Personal Information"
                icon="heroicon-o-user-circle"
                alpine-active="activeTab === 'Personal Information'"
                wire:click="$set('activeTab', 'Personal Information');activeTab = 'Personal Information'"
            >
                Personal Information
            </x-filament::tabs.item>

            <!-- Campaigns Tab -->
            <x-filament::tabs.item
                label="Campaigns"
                icon="heroicon-s-flag"
                wire:click="$set('activeTab', 'Campaigns');activeTab = 'Campaigns'"
                alpine-active="activeTab === 'Campaigns'"
            >
                Campaigns
            </x-filament::tabs.item>

            <!-- Tripping Requests Tab -->
            <x-filament::tabs.item
                label="Tripping"
                icon="heroicon-o-document-text"
                wire:click="$set('activeTab', 'Tripping Request');activeTab = 'Tripping Requests';"
                alpine-active="activeTab === 'Tripping Requests'"
            >
                Tripping
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>
    @if ($activeTab === 'Personal Information')
        @livewire('prospect.client-info-view', ['contact' => $this->record])
    @endif
    @if ($activeTab === 'Campaigns')
        @livewire('prospect.campaigns-view', ['contact' => $this->record])
    @endif
</x-filament-panels::page>
