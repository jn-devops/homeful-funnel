<?php

namespace App\Filament\Resources\TrippingsResource\Pages;

use App\Filament\Resources\TrippingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrippings extends ListRecords
{
    protected static string $resource = TrippingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
