<?php

namespace App\Filament\Exports;

use App\Models\Contact;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ContactExporter extends Exporter
{
    protected static ?string $model = Contact::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('created_at'),
            ExportColumn::make('name'),
            ExportColumn::make('mobile'),
            ExportColumn::make('email'),
            ExportColumn::make('organization.name'),
            ExportColumn::make('campaign')
                ->label('Latest Campaign')
                ->formatStateUsing(function (Contact $record) {
                    $checkin= $record->checkins()->latest()->first();
                    if ($checkin){
                        if ($checkin->campaign){
                            return $checkin->campaign->name;
                        }else{
                            return '';
                        }
                    }else{
                        return '';
                    }

                }),
            ExportColumn::make('checkins')
                ->label('Latest Checkin Project')
                ->formatStateUsing(function (Contact $record) {
                    $checkin= $record->checkins()->latest()->first();
                    if($checkin){
                        if ($checkin->project){
                            return $checkin->project->name;
                        }else{
                            return '';
                        }
                    }else{
                        return '';
                    }

                }),
            ExportColumn::make('state')
                ->formatStateUsing(fn(Contact $record)=>$record->state->name()??''),
//            ExportColumn::make('state'),
//            ExportColumn::make('meta'),
//            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your contact export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
