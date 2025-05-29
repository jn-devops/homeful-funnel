<?php

namespace App\Filament\Resources\CampaignAuthorResource\Pages;

use App\Filament\Resources\CampaignAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCampaignAuthors extends ManageRecords
{
    protected static string $resource = CampaignAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
