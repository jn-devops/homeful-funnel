<?php

namespace App\Livewire;

use App\Filament\Resources\ContactResource;
use App\Models\Trips;
use App\States\TrippingRequested;
use App\States\TrippingState;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class TripsTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public static function table(Table $table): Table
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
//                TextColumn::make('preferred_date')
//                    ->label('Preferred Date')
//                    ->formatStateUsing(function ($record) {
//                        return Carbon::parse($record->preferred_date)->format('F d, Y');
//                    }),
//                TextColumn::make('preferred_time')
//                    ->label('Preferred Time')
//                    ->formatStateUsing(function ($record) {
//                        return Carbon::parse($record->preferred_time)->format('h:i A');
//                    }),
                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap()
                    ->words(10)
                    ->lineClamp(2),
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
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('mobile')
                                ->label('Mobile Number ')
                                ->required()
                                ->prefix('+63')
                                ->regex("/^[0-9]+$/")
                                ->minLength(10)
                                ->maxLength(10),
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

                    })
                    ->hidden(fn($record):bool=>$record->state!=TrippingRequested::class)
                    ->modalWidth(MaxWidth::TwoExtraLarge),



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
}
