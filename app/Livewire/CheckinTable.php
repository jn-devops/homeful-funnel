<?php

namespace App\Livewire;

use App\Models\Checkin;
use App\Models\Contact;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CheckinTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public static function table(Table $table): Table
    {
        // dd(Checkin::where('id', '9d4f86fc-c4a4-4f1f-8268-604a4b21e506')->first()->contact);
        return $table
            ->query(Checkin::query())
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->groups([
                Group::make('created_at')
                    ->label('Date')
                    ->date(),
                Group::make('contact.name')
                    ->label('Name')
                    ->getTitleFromRecordUsing(function (Checkin $record): string {
                        return ucfirst($record->contact->name??'');
                    })
                    ->getKeyFromRecordUsing(fn (Model $record): string => $record->created_at)
                    ->collapsible(),
                Group::make('contact.organization.name')
                    ->label('Organization')
                    ->getTitleFromRecordUsing(function (Checkin $record): string {
                        return ucfirst($record->contact->organization->name??'');
                    })
                    ->getKeyFromRecordUsing(fn (Model $record): string => $record->created_at)
                    ->collapsible(),
                Group::make('campaign.project.name')
                    ->label('Project')
                    ->getTitleFromRecordUsing(function (Checkin $record): string {
                        return ucfirst($record->campaign->project->name??'');
                    })
                    ->getKeyFromRecordUsing(fn (Model $record): string => $record->created_at)
                    ->collapsible(),

            ])
            ->columns([
                // Tables\Columns\TextColumn::make('id')
                //     ->label('ID')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('contact.name')
                    ->formatStateUsing(function ($record) {
                        return "{$record->contact->name}\n{$record->contact->mobile}\n{$record->contact->email}";
                    })
                    ->extraAttributes(['style' => 'white-space: pre-line'])
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('contact', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('contact.organization.name'),
                // ->searchable(),
                Tables\Columns\TextColumn::make('campaign.name'),
                // ->searchable(),
                Tables\Columns\TextColumn::make('campaign.campaignType.name')
                    ->label('Campaign Type'),
                // ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project'),
                // ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.checkin-table');
    }
}
