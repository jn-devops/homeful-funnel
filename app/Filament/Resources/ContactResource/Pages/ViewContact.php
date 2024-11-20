<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use App\Models\ContactStateHistory;
use App\Notifications\Adhoc;
use App\States\Availed;
use App\States\ForTripping;
use App\States\Registered;
use App\States\TrippingAssigned;
use App\States\TrippingCompleted;
use App\States\TrippingConfirmed;
use App\States\Undecided;
use App\States\Uninterested;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;
    protected static string $view = 'filament.pages.prospect-resource-view';
    public $activeTab ='Personal Information';
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Send Email')
                ->label('Send Email')
                ->color('info')
                ->icon('heroicon-s-envelope')
                    ->form([
                        Textarea::make('message')->required(),
                    ])
                    ->action(function (Contact $record, array $data) {
                        // TODO: Email Notif
                        // $record->notify(new Adhoc($data['message']));
                        // $record->smsLogs()->create([
                        //     'message' => $data['message'],
                        //     'sent_to_mobile' => $record->mobile,
                        //     'sent_to_email' => $record->email,
                        // ]);
                    }),
            Action::make('Send SMS')
                ->label('Send SMS')
                ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
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
        ];
    }
}
