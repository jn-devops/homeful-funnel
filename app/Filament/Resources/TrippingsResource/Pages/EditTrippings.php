<?php

namespace App\Filament\Resources\TrippingsResource\Pages;

use App\Filament\Resources\TrippingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrippings extends EditRecord
{
    protected static string $resource = TrippingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
