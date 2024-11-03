<?php

namespace App\Livewire\Checkin;

use App\Models\Campaign;
use App\States\Availed;
use App\States\FirstState;
use App\States\ForTripping;
use App\States\Registered;
use App\States\Undecided;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Component;

class StateModal extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public String $state;
    public String $id;
    public Campaign $campaign;

    public function mount($state, $id): void{
        $this->state = $state;
        $this->id = $id;
        $this->campaign = Campaign::find($this->id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                switch ($this->state){
                    case 'Registered':
                        return $this->campaign->checkins()
                                        ->whereHas('contact', function ($q) {
                                                $q->whereIn('state', [Registered::class, FirstState::class]);
                                        })
                                        ->getQuery();
                        break;
                    case 'For Tripping':
                        return $this->campaign->checkins()
                                        ->whereHas('contact', function ($q) {
                                                $q->where('state', ForTripping::class);
                                        })
                                        ->getQuery();
                        break;
                    case 'Availed':
                        return $this->campaign->checkins()
                                        ->whereHas('contact', function ($q) {
                                                $q->where('state', Availed::class);
                                        })
                                        ->getQuery();
                        break;
                    case 'Not Now':
                        return $this->campaign->checkins()
                                        ->whereHas('contact', function ($q) {
                                                $q->where('state', Undecided::class);
                                        })
                                        ->getQuery();
                        break;
                    default:
                        return $this->campaign->checkins()
                                        ->whereHas('contact', function ($q) {
                                                $q->whereIn('state', [Registered::class, FirstState::class]);
                                        })
                                        ->getQuery();
                        break;
                }
            })
            ->columns([
                TextColumn::make('contact.name')
                    ->label('Name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('contact', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('contact.mobile')
                    ->label('Contact Number'),
                TextColumn::make('contact.organization.name')
                    ->label('Organization'),
                TextColumn::make('campaign.name')
                    ->label('Campaign'),
                TextColumn::make('project.name')
                    ->label('Project Interested'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('view_profile')
                    ->label('View Profile')
                    ->url(function ($record){
                        return 'checkins?tableSearch='.$record->contact->name ?? ''; // TODO: Must be in directed to Prospect's infolist
                    }) 
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-eye')
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.checkin.state-modal');
    }


}
