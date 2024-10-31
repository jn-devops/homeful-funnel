<?php

namespace App\Filament\Resources\CheckinResource\Pages;

use App\Filament\Exports\CheckinExporter;
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
                ->exporter(CheckinExporter::class),
            Actions\Action::make('checkin')
                ->icon('heroicon-m-qr-code')
                ->modalIcon('heroicon-m-qr-code')
                ->form([
                    Select::make('campaign')
                        ->options(Campaign::query()->pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->required()
                        ->debounce(100),
                    Select::make('organization')
                        ->options(function (Get $get) {
                            $campaign = Campaign::find($get('campaign'));
                            if (!$campaign) {
                                return [];
                            }
                            return $campaign->organizations()->get()->pluck('name', 'id');
                        })
                        ->searchable()
                        ->live()
                        ->debounce(100),
                    Placeholder::make('qr_code')
                        ->label('QR Code')
                        ->content(function (Get $get) {
                            return \LaraZeus\Qr\Facades\Qr::render(
                                data:  sprintf(
                                    '%s/checkin/%s%s',
                                    config('app.url'),
                                    $get('campaign'),
                                    $get('organization') ? '?organization=' . $get('organization') : ''
                                ), // This is your model. We are passing the personalizations. If you want the default just comment it out.
                            );
                    })->hidden(fn (Get $get):bool=>$get('campaign')==null),
                    Placeholder::make('link')
                        ->content(function (Get $get) {
                            $url = sprintf(
                                '%s/checkin/%s%s',
                                config('app.url'),
                                $get('campaign'),
                                $get('organization') ? '?organization=' . $get('organization') : ''
                            );
                            return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline">' . $url . '</a>');
                        })->hidden(fn (Get $get):bool=>$get('campaign')==null ),
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
