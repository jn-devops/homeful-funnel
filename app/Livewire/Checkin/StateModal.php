<?php

namespace App\Livewire\Checkin;

use App\Models\Campaign;
use App\States\Availed;
use App\States\FirstState;
use App\States\ForTripping;
use App\States\Registered;
use App\States\TrippingAssigned;
use App\States\TrippingRequested;
use App\States\TrippingState;
use App\States\Undecided;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Livewire\Notifications;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
                    TextColumn::make('project.name')
                        ->label('Project Interested'),
                    TextColumn::make('created_at')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->created_at)->format('F d, Y');
                        })
                        ->label('Date Registered'),
                    TextColumn::make('aging')
                        ->label('Aging')
                        ->getStateUsing(function ($record) {
                            $days = (int) $record->created_at->diffInDays(Carbon::now());
                            $hrs = (int) $record->created_at->diffInHours(Carbon::now());
                            return (($days < 1) ? $hrs . ' Hours' :  ($days . (($days < 2) ? ' Day' : ' Days')));
                            ;
                        }),
                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    Action::make('view_profile')
                        ->label('View Profile')
                        ->url(function ($record){
                            return 'contacts/'.$record->contact->id ?? ''; 
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
                    TextColumn::make('contact.organization.name')
                        ->label('Organization'),
                    TextColumn::make('contact.latest_trip.assigned_to')
                        ->label('Assigned To'),
                    // TextColumn::make('trip.preferred_date')
                    //     ->formatStateUsing(function ($record) {
                    //         return Carbon::parse($record->trip->preferred_date)->format('F d, Y');
                    //     })
                    //     ->label('Preferred Date'),
                    // TextColumn::make('trip.preferred_time')
                    //     ->formatStateUsing(function ($record) {
                    //         return Carbon::parse($record->trip->preferred_time)->format('h:i A');
                    //     })
                    //     ->label('Preferred Date'),
                    TextColumn::make('remarks')
                        ->label('Remarks')
                        ->getStateUsing(function ($record) {
                            return $record->contact->latest_trip->remarks ?? '';
                        })
                        ->wrap()
                        ->lineClamp(2)
                        ->tooltip(fn($record)=>$record->contact->latest_trip->remarks??''),
                ])
                ->filters([
                    // Filter::make('Status')
                    //     ->form([
                    //         Select::make('state')
                    //             ->options(collect(TrippingState::STATES)
                    //                         ->mapWithKeys(function ($stateClass) {
                    //                             return [$stateClass => $stateClass::label()];
                    //                         })
                    //                         ->all()),
                    //     ])
                    //     ->query(function (Builder $query, array $data): Builder {
                    //         return $query->whereHas('contact', function ($q) use($data) {
                    //                     $q->whereHas('latest_trip', function($q2) use($data){
                    //                         $q2->where('state', $data['state']);
                    //                     });
                    //                 });
                    //     })
                ])
                ->actions([
                    Action::make('view_profile')
                        ->label('View Profile')
                        ->url(function ($record){
                            return 'contacts/'.$record->contact->id ?? ''; 
                        }) 
                        ->openUrlInNewTab()
                        ->icon('heroicon-s-eye'),
                        
                    Action::make('assigned_contact')
                        ->label('Assign Contact')
                        ->icon('heroicon-o-pencil-square')
                        ->color('success')
                        ->form(function(Form $form){
                            return $form->schema([
                                TextInput::make('assigned_to')
                                    ->required(),
                                TextInput::make('assigned_to_mobile')
                                    ->label('Mobile Number ')
                                    ->required()
                                    ->prefix('+63')
                                    ->regex("/^[0-9]+$/")
                                    ->minLength(10)
                                    ->maxLength(10),
                                TextInput::make('project.name')
                                    ->label('Project')
                                    ->default(fn($record) => $record->contact->latest_trip->project->name)
                                    ->disabled()
                                    ->columnSpanFull(),
                                TextInput::make('project.project_location')
                                    ->label('Project Location')
                                    ->default(fn($record) => $record->contact->latest_trip->project->project_location)
                                    ->disabled()
                                    ->columnSpanFull(),
                                TextInput::make('remarks')
                                    ->label('Project Location')
                                    ->default(fn($record) => $record->contact->latest_trip->remarks)
                                    ->disabled()
                                    ->columnSpanFull(),
                            ])->columns(2);
                        })
                        ->action(function (Model $record, array $data){
                                $record->state=TrippingAssigned::class;
                                $record->assigned_to=$data['assigned_to'];
                                $record->assigned_to_mobile=$data['assigned_to_mobile'];
                                $record->last_updated_by=auth()->user()->name;
                                $record->save();

                                Notifications::make()
                                ->title('Tripping has been successfully assigned')
                                ->success()
                                ->icon('heroicon-o-check')
                                ->sendToDatabase(auth()->user())
                                ->send();
                        })
                        ->modalHeading('Assign Contact?')
                        ->modalDescription('Are you sure you want to assign the Prospect to Declared Contact? This cannot be undone.')
                        ->requiresConfirmation()
                        ->hidden(fn($record):bool=>$record->contact->latest_trip?->state ? ($record->contact->latest_trip->state != TrippingRequested::class) : true)
                        ->modalWidth(MaxWidth::Small),
                    
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
                    TextColumn::make('project.name')
                        ->label('Project Interested'),
                    TextColumn::make('contact.availed_at')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->contact->availed_at)->format('F d, Y');
                        })
                        ->label('Date Availed'),
                    TextColumn::make('aging')
                        ->label('Aging')
                        ->getStateUsing(function ($record) {
                            $days = (int) Carbon::parse($record->contact->availed_at)->diffInDays(Carbon::now());
                            $hrs = (int) Carbon::parse($record->contact->availed_at)->diffInHours(Carbon::now());
                            return (($days < 1) ? $hrs . ' Hours' :  ($days . (($days < 2) ? ' Day' : ' Days')));
                            ;
                        }),
                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    Action::make('view_profile')
                        ->label('View Profile')
                        ->url(function ($record){
                            return 'contacts/'.$record->contact->id ?? ''; 
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
                    TextColumn::make('project.name')
                        ->label('Project Interested'),
                    TextColumn::make('created_at')
                        ->formatStateUsing(function ($record) {
                            return Carbon::parse($record->created_at)->format('F d, Y');
                        })
                        ->label('Date Clicked "Not Now"'),
                    TextColumn::make('aging')
                        ->label('Aging')
                        ->getStateUsing(function ($record) {
                            $days = (int) Carbon::parse($record->created_at)->diffInDays(Carbon::now());
                            $hrs = (int) Carbon::parse($record->created_at)->diffInHours(Carbon::now());
                            return (($days < 1) ? $hrs . ' Hours' :  ($days . (($days < 2) ? ' Day' : ' Days')));
                            ;
                        }),
                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    Action::make('view_profile')
                        ->label('View Profile')
                        ->url(function ($record){
                            return 'contacts/'.$record->contact->id ?? '';
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
