<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Checkin;
use App\Models\Contact;
use App\Notifications\Adhoc;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use phpDocumentor\Reflection\Types\True_;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Prospect';
    protected static ?string $recordTitleAttribute = 'name';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->inlineLabel(true)
            ->schema([
                Section::make()
                    ->schema([
                        Fieldset::make('Personal Information')
                            ->schema([
                                TextEntry::make('first_name')
                                    ->label('First Name'),
                                TextEntry::make('last_name')
                                    ->label('Last Name'),
                                TextEntry::make('email'),
                                TextEntry::make('mobile'),
                                TextEntry::make('organization.name'),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                        Fieldset::make('Campaign attended')
                            ->schema([
                                TextEntry::make('lastest_checkin.campaign.name')
                                    ->label('Campaign'),
                                TextEntry::make('lastest_checkin.campaign.type')
                                    ->label('Type'),
                                TextEntry::make('lastest_checkin.project.name')
                                    ->label('Project Interested'),
                            ])
                            ->columns(1)
                            ->columnSpan(1),
                        Fieldset::make('Trips')
                            ->schema([
                                TextEntry::make('latest_trip.preferred_date')
                                    ->label('Date')
                                    ->date('M d, Y')
                                    ->inlineLabel(true),
                                TextEntry::make('latest_trip.preferred_time')
                                    ->label('Time')
                                    ->time('h:i A')
                                    ->inlineLabel(true),
                                TextEntry::make('latest_trip.project.name')
                                    ->label('Project')
                                    ->inlineLabel(true),
                                TextEntry::make('latest_trip.remarks')
                                    ->label('Remarks')
                                    ->inlineLabel(true),
                            ])
                            ->columns(1)
                            ->columnSpan(1)

                    ])
                    ->columns(2)
                    ->columnSpan(2),
                Section::make()
                    ->schema([
                        TextEntry::make('latest_trip.state')
                            ->label('Status')
                            ->inlineLabel(false)
                            ->badge(),
                        TextEntry::make('created_at')
                            ->label('Date Registered')
                            ->inlineLabel(false)
                            ->dateTime('M d, Y h:i A')
                            ->hint(fn($record)=>$record->created_at->diffForHumans()),
                    ])
                    ->columns(1)
                    ->columnSpan(1),
        ])->columns(3);
    }

//    public static function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\Group::make()
//                    ->schema([
//                        Forms\Components\Section::make('Personal Information')
//                            ->inlineLabel(true)
//                            ->schema([
//                                Forms\Components\Group::make()
//                                    ->schema([
//                                        Forms\Components\TextInput::make('first_name')
//                                            ->maxLength(255),
//                                        Forms\Components\TextInput::make('last_name')
//                                            ->maxLength(255),
//                                        Forms\Components\TextInput::make('email')
//                                            ->maxLength(255),
//                                        Forms\Components\TextInput::make('mobile')
//                                            ->maxLength(255),
//                                        Forms\Components\Select::make('organization_id')
//                                            ->preload()
//                                            ->relationship('organization', 'name')
//                                            ->searchable(),
//                                    ])
//                                    ->columns(1),
//                                Forms\Components\Group::make()
//                                    ->schema([
//                                        Forms\Components\Select::make('campaign_id')
//                                            ->preload()
//                                            ->relationship('campaign', 'name')
//                                            ->searchable(),
//                                        Forms\Components\TextInput::make('campaign.name')
//                                            ->maxLength(255),
//                                        Forms\Components\TextInput::make('campaign.type')
//                                            ->maxLength(255),
//
//                                    ])
//                                    ->columns(1),
//
//                            ])->columnSpan(2)->columns(2),
//                        Forms\Components\Section::make()
//                            ->schema([
//
//                            ])->columnSpan(1),
//                    ])->columns(3)->columnSpanFull(),
//
////                Forms\Components\TextInput::make('mobile')
////                    ->required()
////                    ->maxLength(255),
////                Forms\Components\TextInput::make('email')
////                    ->email()
////                    ->required()
////                    ->maxLength(255),
////
//////                Forms\Components\TextInput::make('meta'),
////                Forms\Components\Select::make('organization_id')
////                    ->preload()
////                    ->relationship('organization', 'name')
////                    ->searchable(),
////                Forms\Components\Select::make('campaign_id')
////                    ->preload()
////                    ->relationship('campaign', 'name')
////                    ->searchable(),
//            ]);
//    }

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
                        return "{$record->name}\n{$record->mobile}\n{$record->email}\n\n{$record->id}";
                    })
                    ->extraAttributes(['style' => 'white-space: pre-line'])
                    ->searchable(['name','mobile','email','id']),
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
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aging')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
//                Tables\Actions\ViewAction::make()
//                    ->mutateFormDataUsing(function (Contact $record, array $data):array{
//                        $data['personal_details']=[
//                            [
//                                'first_name'=>$record->first_name,
//                                'last_name'=>$record->last_name,
//                                'email'=>$record->email,
//                                'mobile'=>$record->mobile,
//                                'organization'=>$record->organization->name,
//                            ]
//                        ];
//                        return $data;
//                    })
//                    ->mutateRecordDataUsing(function (array $data): array {
//                        $data['user_id'] = auth()->id();
//                        dd($data);
//                        return $data;
//                    }),
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
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }

}
