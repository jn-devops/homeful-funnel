<?php

namespace App\Filament\Resources\SocialMediaCheckinsResource\Pages;

use App\Filament\Resources\SocialMediaCheckinsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialMediaCheckins extends ListRecords
{
    protected static string $resource = SocialMediaCheckinsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
