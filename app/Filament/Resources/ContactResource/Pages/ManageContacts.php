<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Exports\ContactExporter;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\ContactResource\Widgets\ContactStateSummary;
use App\Filament\Resources\UpdateLogsResource\RelationManagers\UpdateLogRelationManager;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageContacts extends ManageRecords
{
    protected static string $resource = ContactResource::class;


    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(ContactExporter::class),
//            Actions\CreateAction::make(),
        ];
    }

    public  function getHeaderWidgets(): array
    {
        return [
            ContactStateSummary::class,
        ];
    }

}
