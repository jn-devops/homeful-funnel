<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Notifications\Adhoc;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ContactTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Contact::query())
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(function ($record) {
                        return "{$record->name}\n{$record->mobile}\n{$record->email}";
                    })
                    ->extraAttributes(['style' => 'white-space: pre-line'])
                    ->searchable(['name','mobile','email']),
                Tables\Columns\TextColumn::make('organization.name')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('checkins')
                    ->label('Last Checkin Project')
                    ->formatStateUsing(function ($record) {
                        return $record->checkins()->latest()->first()->project->name;
                    }),
//                Tables\Columns\TextColumn::make('mobile')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('email')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('name')
//                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn(Contact $record)=>$record->state->name())
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

//                Tables\Columns\TextColumn::make('campaign.name')
//                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('send')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->form([
                        Textarea::make('message')->required(),
                    ])
                    ->action(function (Contact $record, array $data) {
                        $record->notify(new Adhoc($data['message']));
                        $record->smsLogs()->create([
                            'message' => $data['message'],
                            'sent_to_mobile' => $record->mobile,
                            'sent_to_email' => $record->email,
                        ]);
                    }),
            ],Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('sms')
                        ->label('Send SMS')
                        ->icon('heroicon-m-chat-bubble-left-ellipsis')
                        ->form([
                            Textarea::make('message')->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function(Contact $record) use($data) {
                                $record->notify(new Adhoc($data['message']));
                                $record->smsLogs()->create([
                                    'message' => $data['message'],
                                    'sent_to_mobile' => $record->mobile,
                                    'sent_to_email' => $record->email,
                                ]);
                            });
                        })
                        ->deselectRecordsAfterCompletion()
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.contact-table');
    }
}
