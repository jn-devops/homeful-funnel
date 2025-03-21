<?php

namespace App\Livewire;

use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use App\Models\ContactStateHistory;
use App\Models\User;
use App\Notifications\Adhoc;
use App\States\Availed;
use App\States\ForTripping;
use App\States\Registered;
use App\States\TrippingAssigned;
use App\States\TrippingCancelled;
use App\States\TrippingCompleted;
use App\States\TrippingConfirmed;
use App\States\TrippingRequested;
use App\States\Undecided;
use App\States\Uninterested;
use App\States\Unresponsive;
use Faker\Provider\Text;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
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
                Tables\Columns\TextColumn::make('assigned.name')
                    ->extraAttributes(['style' => 'white-space: pre-line']),
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
                // Tables\Columns\TextColumn::make('state')
                //     ->formatStateUsing(fn(Contact $record)=>$record->state->name())
                //     ->searchable(),
                Tables\Columns\TextColumn::make('availed')
                    ->label('Availed')
                    ->badge()
                    ->color(function (Contact $record){
                        return ($record->state == Availed::class) ? 'success' : 'gray';
                    })
                    ->getStateUsing(function (Contact $record){
                        return ($record->state == Availed::class) ? 'Yes' : 'Not Yet';
                    }),
                Tables\Columns\TextColumn::make('for_tripping')
                    ->label('For Tripping')
                    ->badge()
                    ->color(function (Contact $record){
                        if($record->latest_trip()->count() > 0){
                            if($record->latest_trip->state == TrippingAssigned::class){
                                return 'warning';
                            }elseif($record->latest_trip->state == TrippingConfirmed::class){
                                return 'info';
                            }elseif($record->latest_trip->state == TrippingCompleted::class){
                                return 'success';
                            }elseif($record->latest_trip->state == TrippingCancelled::class){
                                return 'danger';
                            }else{
                                return 'primary';
                            }
                        }else{
                            return 'gray';
                        }
                    })
                    ->getStateUsing(function (Contact $record){
                        return ($record->latest_trip()->count() > 0) ? $record->latest_trip->state->name() : 'Not Yet';
                    }),
                Tables\Columns\TextColumn::make('not_now')
                    ->label('Not Now')
                    ->badge()
                    ->color(function (Contact $record){
                        return ($record->state == Undecided::class) ? 'success' : 'gray';
                    })
                    ->getStateUsing(function (Contact $record){
                        return ($record->state == Undecided::class) ? 'Yes' : 'Not Yet';
                    }),
                Tables\Columns\TextColumn::make('consulted')
                    ->label('Consulted')
                    ->badge()
                    ->color(function (Contact $record){
                        return 'gray'; // TODO: No data source for consulted
                    })
                    ->getStateUsing(function (Contact $record){
                        return 'Not Yet'; // TODO: No data source for consulted
                    }),
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

                ActionGroup::make([
                    Tables\Actions\Action::make('View')
                        ->url(fn($record)=>ContactResource::getUrl('view', ['record' => $record]))
                        ->icon('heroicon-o-eye')
                        ->color('secondary'),
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
                    Action::make('assign')
                        ->label('Assign To Sales')
                        ->icon('heroicon-m-pencil-square')
                        ->form([
                            Select::make('assign_to')
                                ->label('Assign To Sales')
                                ->options(User::all()->pluck('name','id'))
                                ->searchable()
                                ->native(false),
                        ])
                        ->action(function (Contact $record, array $data) {
                            $record->assigned_to=$data['assign_to'];
                            $record->save();
                        })
                        ->modalWidth(MaxWidth::ExtraSmall),
                    Action::make('update_state')
                        ->label('Update State')
                        ->icon('heroicon-m-pencil-square')
                        ->form([
                            Select::make('state')
                                ->label('State')
                                ->options([
                                    Registered::class=>'Registered',
                                    Availed::class=>'Availed',
                                    Undecided::class=>'Undecided',
                                    ForTripping::class=>'For Tripping',
                                    TrippingAssigned::class=>'Tripping Assigned',
                                    TrippingConfirmed::class=>'Tripping Confirmed',
                                    TrippingCompleted::class=>'Tripping Completed',
                                    Uninterested::class=>'Uninterested',
                                    Unresponsive::class=>'Uncontacted',
                                ])
                                ->searchable()
                                ->native(false),
                            Textarea::make('remarks')
                                ->label('Remarks')
                                ->rows(5)
                                ->cols(5)
                                ->maxLength(255),
                        ])
                        ->action(function (Contact $record, array $data) {
                            $record->state=$data['state'];
                            $record->save();
                            ContactStateHistory::create([
                               'contact_id'=>$record->id,
                               'state'=>$data['state'],
                               'remarks'=>$data['remarks']
                            ]);
                        })
                        ->modalWidth(MaxWidth::ExtraSmall),
                ])->button()->label('Actions'),

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
                        ->deselectRecordsAfterCompletion(),
                     BulkAction::make('assign')
                         ->label('Assign To Sales')
                         ->icon('heroicon-m-pencil-square')
                         ->form([
                             Select::make('assign_to')
                                 ->label('Assign To Sales')
                                 ->options(User::all()->pluck('name','id'))
                                 ->searchable()
                                 ->native(false),
                         ])
                         ->action(function (Collection $records, array $data) {
                             $records->each(function(Contact $record) use($data) {
                                 $record->assigned_to=$data['assign_to'];
                                 $record->save();
                             });
                         })
                         ->modalWidth(MaxWidth::ExtraSmall)
                         ->deselectRecordsAfterCompletion(),
                    BulkAction::make('update_state')
                        ->label('Update State')
                        ->icon('heroicon-m-pencil-square')
                        ->form([
                            Select::make('state')
                                ->label('State')
                                ->options([
                                    Registered::class=>'Registered',
                                    Availed::class=>'Availed',
                                    Undecided::class=>'Undecided',
                                    ForTripping::class=>'For Tripping',
                                    TrippingAssigned::class=>'Tripping Assigned',
                                    TrippingConfirmed::class=>'Tripping Confirmed',
                                    TrippingCompleted::class=>'Tripping Completed',
                                    Uninterested::class=>'Uninterested',
                                    Unresponsive::class=>'Uncontacted',
                                ])
                                ->searchable()
                                ->native(false),
                            Textarea::make('remarks')
                                ->label('Remarks')
                                ->rows(5)
                                ->cols(5)
                                ->maxLength(255),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function(Contact $record) use($data) {
                                $record->state=$data['state'];
                                $record->save();
                                ContactStateHistory::create([
                                    'contact_id'=>$record->id,
                                    'state'=>$data['state'],
                                    'remarks'=>$data['remarks']
                                ]);
                            });

                        })
                        ->modalWidth(MaxWidth::ExtraSmall),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.contact-table');
    }
}
