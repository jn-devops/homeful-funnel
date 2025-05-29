<?php

namespace App\Filament\Resources\SocialMediaCampaignResource\Pages;

use App\Filament\Resources\SocialMediaCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialMediaCampaign extends EditRecord
{
    protected static string $resource = SocialMediaCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
