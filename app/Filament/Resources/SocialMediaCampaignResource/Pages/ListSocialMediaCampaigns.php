<?php

namespace App\Filament\Resources\SocialMediaCampaignResource\Pages;

use App\Filament\Resources\SocialMediaCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialMediaCampaigns extends ListRecords
{
    protected static string $resource = SocialMediaCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
