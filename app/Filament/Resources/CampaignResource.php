<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->unique(ignoreRecord: true,column: 'name',table: Campaign::class)
                    ->live()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('event_date')
                    ->label('Date')
                    ->date()
                    ->native(false)
                    ->required(),
                Forms\Components\TimePicker::make('event_time_from')
                    ->label('Time From')
                    ->seconds(false)
                    ->timezone('Asia/Manila')
                    ->native(false)
                    ->minutesStep(30)
                    ->displayFormat('h:i A')
                    ->required(),
                Forms\Components\TimePicker::make('event_time_to')
                    ->label('Time To')
                    ->seconds(false)
                    ->timezone('Asia/Manila')
                    ->native(false)
                    ->minutesStep(30)
                    ->rule('after:start_time')
                    ->displayFormat('h:i A')
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->preload()
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('campaign_type_id')
                    ->label('Type')
                    ->relationship('campaignType', 'name')
                    ->preload()
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('rider_url')
                    ->label('Redirect URL')
                    ->live()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('feedback')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('splash_image_url')
                    ->label('Splash Image Url')
                    ->url()
                    ->required()
                    ->columnSpan(2),
//                FileUpload::make('splash_image')
//                    ->required()
//                    ->label('Splash Image')
//                    ->columnSpanFull()
//                    ->image()
//                    ->imageEditor()
//                    ->imageEditorAspectRatios([
//                        null,
//                        '16:9',
//                        '4:3',
//                        '1:1',
//                    ])
//                    ->maxSize(2048)
//                    ->openable()
//                    ->downloadable()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('campaignType.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rider_url')
                    ->label('Redirect URL')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('feedback')
                //     ->label('Feedback')
                //     ->searchable(),
                Tables\Columns\ImageColumn::make('splash_image_url')
                    ->label('Splash Image'),
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
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data,Model $record): array {
                    $data['user_id'] = auth()->id();
                        $data['splash_image']=$record->meta->get('splash_image');
                        $data['splash_image_url']=$record->meta->get('splash_image_url');
                        $data['event_date']=$record->meta->get('event_date');
                        $data['event_time_from']=$record->meta->get('event_time_from');
                        $data['event_time_to']=$record->meta->get('event_time_to');
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
                Action::make('View QR')
                    ->icon('heroicon-m-qr-code')
                    ->modalIcon('heroicon-m-qr-code')
                    ->form([
                        Select::make('organization')
                            ->options(Organization::query()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->required(),
                        Placeholder::make('qr_code')
                            ->label('QR Code')
                            ->content(function (Get $get, Model $record) {
                                return \LaraZeus\Qr\Facades\Qr::render(
                                    data:  config('app.url').'/checkin/'.$record->id.'/'. $get('organization'), // This is your model. We are passing the personalizations. If you want the default just comment it out.
                                );
                        })->hidden(fn (Get $get):bool=>$get('organization')==null),
                        Placeholder::make('link')
                            ->content(function (Get $get, Model $record) {
                                $url = config('app.url') . '/checkin/' . $record->id . '/' . $get('organization');
                                return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline">' . $url . '</a>');
                            })->hidden(fn (Get $get):bool=>$get('organization')==null),


    //                        ->formatStateUsing(function (string $state, $record) {
    //                            return \LaraZeus\Qr\Facades\Qr::render(
    //                                data: $state,
    //                                options: $record->options // This is your model. We are passing the personalizations. If you want the default just comment it out.
    //                            );
    //                        }),
                    ])
                ->label('Generate QR')
                ->modalFooterActions([])
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalWidth(MaxWidth::Small),
            ],Tables\Enums\ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCampaigns::route('/'),
        ];
    }
}
