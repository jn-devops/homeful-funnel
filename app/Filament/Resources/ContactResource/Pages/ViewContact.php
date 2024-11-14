<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use App\Notifications\Adhoc;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

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
        ];
    }
}
