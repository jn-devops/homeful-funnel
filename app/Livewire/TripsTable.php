<?php

namespace App\Livewire;

use App\Models\Checkin;
use App\Models\Trips;
use App\States\TrippingState;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

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
                    ->label('Project Name')
                    ->searchable(),
                TextColumn::make('preferred_date')
                    ->label('Preferred Date')
                    ->formatStateUsing(function ($record) {
                        return Carbon::parse($record->preferred_date)->format('F d, Y');
                    }),
                TextColumn::make('preferred_time')
                    ->label('Preferred Time')
                    ->formatStateUsing(function ($record) {
                        return Carbon::parse($record->preferred_time)->format('h:i A');
                    }),
                TextColumn::make('remarks')
                    ->label('Remarks'),
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
                Tables\Actions\Action::make('update_state')
                    ->label('Update Status')
                    ->form([
                       Select::make('state')
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
                    ->action(function (array $data, Trips $record): void {
                        $record->state =$data['state'] ;
                        $record->save();
                    })

            ])
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
