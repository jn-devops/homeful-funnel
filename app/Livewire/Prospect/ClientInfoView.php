<?php

namespace App\Livewire\Prospect;

use App\Models\Contact;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ClientInfoView extends Component implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public Contact $contact;

    public function personalInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->contact)
            ->schema([
                Section::make('Personal Information')
                    ->columns(2)
                    ->schema([
                        Section::make('')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('first_name')
                                    ->inlineLabel(true),
                                TextEntry::make('last_name')
                                    ->inlineLabel(true),
                                TextEntry::make('email')
                                    ->inlineLabel(true),
                                TextEntry::make('mobile')
                                    ->label('Contact Number')
                                    ->inlineLabel(true),
                            ]),
                            Section::make('')
                                ->columnSpan(1)
                                ->schema([
                                    TextEntry::make('created_at')
                                        ->getStateUsing(fn(Contact $record)=>Carbon::parse($record->created_at)->format('F d, Y'))
                                        ->label('Date Registered')
                                        ->inlineLabel(true),
                                    TextEntry::make('id')
                                        ->label('Registration Code')
                                        ->inlineLabel(true),
                                        // ->getStateUsing(function (Contact $record){
                                        //     return '---';
                                        // }),
                                    TextEntry::make('checkins')
                                        ->getStateUsing(fn(Contact $record)=>$record->checkins()->count())
                                        ->inlineLabel(true),
                                    TextEntry::make('tripping_request')
                                        ->label('Tripping Requests')
                                        ->getStateUsing(fn(Contact $record)=>$record->trips()->count())
                                        ->inlineLabel(true),
                                ]),
                    ]),
            ]);
    }
    
    public function render()
    {
        return view('livewire.prospect.client-info-view');
    }
}
