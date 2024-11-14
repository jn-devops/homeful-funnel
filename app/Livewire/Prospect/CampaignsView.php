<?php

namespace App\Livewire\Prospect;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

use App\Models\Contact;

class CampaignsView extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Contact $contact;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->contact->checkins()->getQuery()
            )
            ->columns([
                TextColumn::make('campaign.name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    
    public function render()
    {
        return view('livewire.prospect.campaigns-view');
    }
}
