<?php

namespace App\Filament\Resources\CheckinResource\Pages;

use App\Filament\Exports\ContactExporter;
use App\Filament\Resources\CheckinResource;
use App\Models\Campaign;
use App\Models\Organization;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ManageCheckins extends ManageRecords
{
    protected static string $resource = CheckinResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
            ExportAction::make()
                ->exporter(ContactExporter::class)
                ,
            Actions\Action::make('checkin')
                ->icon('heroicon-m-qr-code')
                ->modalIcon('heroicon-m-qr-code')
                ->form([
                    Select::make('campaign')
                        ->options(Campaign::query()->pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->required(),
                    Select::make('organization')
                        ->options(Organization::query()->pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->required(),
                    Placeholder::make('qr_code')
                        ->label('QR Code')
                        ->content(function (Get $get) {
                            return \LaraZeus\Qr\Facades\Qr::render(
                                data:  config('app.url').'/checkin/'.$get('campaign').'/'. $get('organization'), // This is your model. We are passing the personalizations. If you want the default just comment it out.
                            );
                    }),
                    Placeholder::make('link')
                        ->content(function (Get $get) {
                            $url = config('app.url') . '/checkin/' . $get('campaign') . '/' . $get('organization');
                            return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline">' . $url . '</a>');
                        })
//                        ->formatStateUsing(function (string $state, $record) {
//                            return \LaraZeus\Qr\Facades\Qr::render(
//                                data: $state,
//                                options: $record->options // This is your model. We are passing the personalizations. If you want the default just comment it out.
//                            );
//                        }),
                ])
                ->label('Generate QR')
                ->modalFooterActions([])
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalWidth(MaxWidth::Small),
        ];
    }
}
