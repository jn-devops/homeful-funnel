<?php

namespace App\Livewire\Campaign;

use App\Models\Campaign;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;

use Livewire\Component;

class ProjectChosenList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $id;
    public Campaign $campaign;

    public function mount($id){
        $this->id = $id;
        $this->campaign = Campaign::find($this->id);
    }

    public function render()
    {
        return view('livewire.campaign.project-chosen-list');
    }

    public function table(Table $table): Table{
        return $table
                ->query(function (): Builder {
                    return $this->campaign->projects()->getQuery();
                })
                ->columns([
                    TextColumn::make('name'),
                ])
                ->filters([
                ])
                ->actions([
                ])
                ->bulkActions([
                    // ...
                ]);
    }
}
