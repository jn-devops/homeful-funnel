<?php

namespace App\Filament\Pages;

use App\Filament\Exports\ContactExporter;
use App\Filament\Resources\ContactResource\Widgets\ContactStateSummary;
use App\Filament\Resources\UpdateLogsResource\RelationManagers\UpdateLogRelationManager;
use App\Models\Checkin;
use App\Models\Contact;
use Filament\Actions\ExportAction;
use Filament\Pages\Page;
use Filament\Resources\Components\Tab;


class Prospects extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.prospects';
    protected static ?string $navigationLabel='Prospects';
    public $activeTab ='prospects';

    public static function getNavigationBadge(): ?string
    {
        return Contact::count();
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('export_data')
                ->label('Export Data')
                ->exporter(ContactExporter::class),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        if($this->activeTab=='prospects'){
            return [
                ContactStateSummary::class,
            ];
        }
       return [];
    }


//    public function mount()
//    {
//        // Ensures $activeTab is available during the initial load
//        $this->activeTab = 'prospects';
//    }

//    protected function getTabs():array
//    {
//        return [
//            Tab::make('Prospects')
//                ->badge(Contact::count())
//                ->icon('heroicon-o-user-circle'),
//            Tab::make('Registerd')
//                ->badge(Checkin::count())
//                ->icon('heroicon-o-qr-code'),
//            Tab::make('Tripping Request')
//                ->badge(Checkin::count())
//                ->icon('heroicon-o-document-list'),
//        ];
//    }

}
