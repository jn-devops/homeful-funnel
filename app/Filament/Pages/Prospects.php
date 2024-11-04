<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ContactResource\Widgets\ContactStateSummary;
use App\Models\Checkin;
use App\Models\Contact;
use Filament\Pages\Page;
use Filament\Resources\Components\Tab;


class Prospects extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.prospects';
    protected static ?string $navigationLabel='Prospect New (work in progress)';
    public $activeTab ='prospects';
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
