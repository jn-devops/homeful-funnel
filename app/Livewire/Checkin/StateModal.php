<?php

namespace App\Livewire\Checkin;

use App\Models\Campaign;
use App\States\Availed;
use App\States\FirstState;
use App\States\ForTripping;
use App\States\Registered;
use App\States\Undecided;
use Carbon\Carbon;
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
        if($this->state == 'Registered'){ // Registered
            return $table
                ->query(function (): Builder {
                    return $this->campaign->checkins()
                                    ->whereHas('contact', function ($q) {
                                            $q->whereIn('state', [Registered::class, FirstState::class]);
                                    })
                                    ->getQuery();
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
                    TextColumn::make('created_at')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->created_at)->format('F d, Y');
                        })
                        ->label('Date Registered'),
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
        }elseif($this->state == 'For Tripping'){ // For Tripping
            return $table
                ->query(function (): Builder {
                    return $this->campaign->checkins()
                                    ->whereHas('contact', function ($q) {
                                        $q->where('state', ForTripping::class);
                                    })
                                    ->getQuery();
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
                    TextColumn::make('project.name')
                            ->label('Project Interested'),
                    TextColumn::make('contact.organization.name')
                        ->label('Organization'),
                    TextColumn::make('trip.preferred_date')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->trip->preferred_date)->format('F d, Y');
                        })
                        ->label('Preferred Date'),
                    TextColumn::make('trip.preferred_time')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->trip->preferred_time)->format('h:i A');
                        })
                        ->label('Preferred Date'),
                    TextColumn::make('trip.remarks')
                        ->label('Remarks')
                        ->wrap()
                        ->lineClamp(2),
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
        }elseif($this->state == 'Availed'){ // Availed
            return $table
                ->query(function (): Builder {
                    return $this->campaign->checkins()
                                    ->whereHas('contact', function ($q) {
                                        $q->where('state', Availed::class);
                                    })
                                    ->getQuery();
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
                    TextColumn::make('contact.availed_at')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->created_at)->format('F d, Y');
                        })
                        ->label('Date Availed'),
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
        }elseif($this->state == 'Not Now'){ // Not Now
            return $table
                ->query(function (): Builder {
                    return $this->campaign->checkins()
                                    ->whereHas('contact', function ($q) {
                                        $q->where('state', Undecided::class);
                                    })
                                    ->getQuery();
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
                    TextColumn::make('created_at')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->created_at)->format('F d, Y');
                        })
                        ->label('Date Clicked "Not Now"'),
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
    }

    public function render()
    {
        return view('livewire.checkin.state-modal');
    }


}
