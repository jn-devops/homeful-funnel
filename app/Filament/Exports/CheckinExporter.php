<?php

namespace App\Filament\Exports;

use App\Models\Checkin;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CheckinExporter extends Exporter
{
    protected static ?string $model = Checkin::class;

    public static function getColumns(): array
    {
        return [
//            ExportColumn::make('id')
//                ->label('ID'),
            ExportColumn::make('created_at'),
            ExportColumn::make('contact.name')
                ->label('name'),
            ExportColumn::make('contact.mobile')
                ->label('mobile'),
            ExportColumn::make('contact.email')
                ->label('email'),
            ExportColumn::make('campaign.name')
                ->label('campaign'),
            ExportColumn::make('project.name')
                ->label('project'),
//            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your checkin export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
