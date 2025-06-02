<?php

namespace App\Filament\Resources\SocialMediaCheckinsResource\Pages;

use App\Filament\Resources\SocialMediaCheckinsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialMediaCheckins extends EditRecord
{
    protected static string $resource = SocialMediaCheckinsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
