<?php

namespace App\Livewire;

use App\Filament\Resources\ContactResource;
use App\Models\Trips;
use App\States\TrippingAssigned;
use App\States\TrippingCancelled;
use App\States\TrippingCompleted;
use App\States\TrippingConfirmed;
use App\States\TrippingRequested;
use App\States\TrippingState;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class TripsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Trips::query())
            ->persistFiltersInSession()
            ->columns([
                TextColumn::make('created_at')
                    ->date()
                    ->label('Created Date'),
                TextColumn::make('contact.name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable(),
                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap()
                    ->words(10)
                    ->lineClamp(2)
                    ->tooltip(fn($record)=>$record->remarks??''),
                TextColumn::make('completed_ts')
                    ->label('Date Completed')
                    ->formatStateUsing(function ($record) {
                        return Carbon::parse($record->preferred_date)->format('F d, Y h:i A') ;
                    }),
                TextColumn::make('last_updated_by')
                    ->label('Last Updated By'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('state')
                    ->label('Status')
                    ->native(false)
                    ->options(function (){
                        return collect(TrippingState::STATES)
                            ->mapWithKeys(function ($stateClass) {
                                return [$stateClass => $stateClass::label()];
                            })
                            ->all();
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
//                Tables\Actions\Action::make('update_state')
//                    ->label('Update Status')
//                    ->form([
//                       Select::make('state')
//                            ->label('Status')
//                            ->native(false)
//                            ->options(function (){
//                                return collect(TrippingState::STATES)
//                                    ->mapWithKeys(function ($stateClass) {
//                                        return [$stateClass => $stateClass::label()];
//                                    })
//                                    ->all();
//                            })
//                    ])
//                    ->action(function (array $data, Trips $record): void {
//                        $record->state =$data['state'] ;
//                        $record->save();
//                    }),
                Tables\Actions\Action::make('View Profile')
                    ->url(fn($record)=>ContactResource::getUrl('view', ['record' => $record->contact]))
                    ->icon('heroicon-o-eye')
                    ->color('secondary'),
                Tables\Actions\Action::make('Assign Contact')
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
                                ->default(fn($record) => $record->project->name)
                                ->disabled()
                                ->columnSpanFull(),
                            TextInput::make('project.project_location')
                                ->label('Project Location')
                                ->default(fn($record) => $record->project->project_location)
                                ->disabled()
                                ->columnSpanFull(),
                            TextInput::make('remarks')
                                ->label('Project Location')
                                ->default(fn($record) => $record->remarks)
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

                            Notification::make()
                            ->title('Tripping has been successfully assigned')
                            ->success()
                            ->icon('heroicon-o-check')
                            ->sendToDatabase(auth()->user())
                            ->send();
                    })
                    ->modalHeading('Assign Contact?')
                    ->modalDescription('Are you sure you want to assign the Prospect to Declared Contact? This cannot be undone.')
                    ->requiresConfirmation()
                    ->hidden(fn($record):bool=>$record->state!=TrippingRequested::class ||$record->state==null )
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\Action::make('Confirm Tripping')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->form(function(Form $form){
                        return $form->schema([
                            Placeholder::make('assigned_to')
                                ->label('Assigned To')
                                ->content(fn($record)=>$record->assigned_to??''),
                            Placeholder::make('assigned_to_mobile')
                                ->label('Assigned to Mobile')
                                ->content(fn($record)=>$record->assigned_to_mobile??''),
                            DatePicker::make('preferred_date')
                                ->label('Date')
                                ->native(false)
                                ->required(),
                            Select::make('preferred_time')
                                ->required()
                                ->label('Time')
                                ->native(false)
                                ->options([
                                    'AM'=>'AM',
                                    'PM'=>'PM'
                                ]),
//                            TextInput::make('assigned_to')
//                                ->label('Name')
//                                ->required(),
//                            TextInput::make('assigned_to_mobile')
//                                ->label('Mobile Number ')
//                                ->required()
//                                ->prefix('+63')
//                                ->regex("/^[0-9]+$/")
//                                ->minLength(10)
//                                ->maxLength(10),
                            Placeholder::make('project.name')
                                ->label('Project')
                                ->content(fn($record)=>$record->project->name??''),
                            Placeholder::make('project.name')
                                ->label('Location')
                                ->content(function(Model $record){
                                    return $record->project->project_location;
                                }),
                            Placeholder::make('remarks')
                                ->content(fn($record)=>$record->remarks??'')
                                ->columnSpanFull(),
                        ])->columns(2);
                    })
                    ->action(function (Model $record, array $data){
                        $record->state=TrippingConfirmed::class;
                        $record->preferred_date = $data['preferred_date'];
                        $record->preferred_time = $data['preferred_time'];
                        $record->last_updated_by=auth()->user()->name;
                        $record->save();
                        Notification::make()
                            ->title('Tripping has been Confirmed & Scheduled')
                            ->success()
                            ->icon('heroicon-o-check')
                            ->sendToDatabase(auth()->user())
                            ->send();
                    })
                    ->hidden(fn($record):bool=>$record->state!=TrippingAssigned::class)
                    ->requiresConfirmation()
                    ->modalHeading(' Confirm Tripping Schedule')
                    ->modalDescription('Do you want to confirm the tripping schedule? This will update the prospect Tripping Schedule. This cannot be undone')
                    ->modalWidth(MaxWidth::Small),
                Tables\Actions\Action::make('Cancel')
                    ->action(function (Model $record){
                            $record->state = TrippingCancelled::class;
                            $record->last_updated_by=auth()->user()->name;
                            $record->save();
                        Notification::make()
                            ->title('Tripping has been cancelled successfully')
                            ->success()
                            ->icon('heroicon-o-check')
                            ->sendToDatabase(auth()->user())
                            ->send();
                    })
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->hidden(fn($record):bool=>$record->state==TrippingCompleted::class),
                Tables\Actions\Action::make('Complete')
                    ->action(function (Model $record){
                        $record->state = TrippingCompleted::class;
                        $record->completed_ts = now();
                        $record->last_updated_by=auth()->user()->name;
                        $record->save();
                        Notification::make()
                            ->title('Tripping has been completed successfully')
                            ->success()
                            ->icon('heroicon-o-check')
                            ->sendToDatabase(auth()->user())
                            ->send();
                    })
                    ->color('success')
                    ->icon('heroicon-o-x-mark')
                    ->modalHeading('Tripping has been Completed?')
                    ->modalDescription('Do you want to mark the Tripping as “Completed”? This cannot be undone.')
                    ->requiresConfirmation()
                    ->hidden(fn($record):bool=>$record->state!=TrippingConfirmed::class),


            ],Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.trips-table');
    }

    public function mount(){
        $this->tableFilters['state']['value'] = 'App\\States\\TrippingRequested';
    }

    public function getTableFiltersProperty()
    {
        return $this->tableFilters;
    }
}
