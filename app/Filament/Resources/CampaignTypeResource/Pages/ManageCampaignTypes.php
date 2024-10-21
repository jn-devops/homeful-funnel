<?php

namespace App\Filament\Resources\CampaignTypeResource\Pages;

use App\Filament\Resources\CampaignTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCampaignTypes extends ManageRecords
{
    protected static string $resource = CampaignTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
