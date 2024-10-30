<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use App\Models\SmsLogs;
use App\Notifications\Adhoc;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('mobile')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
//                Forms\Components\TextInput::make('meta'),
                Forms\Components\Select::make('organization_id')
                    ->preload()
                    ->relationship('organization', 'name')
                    ->searchable(),
//                Forms\Components\Select::make('campaign_id')
//                    ->preload()
//                    ->relationship('campaign', 'name')
//                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContacts::route('/'),
        ];
    }
}
