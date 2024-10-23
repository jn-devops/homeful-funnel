<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
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
use Filament\Forms\Components\RichEditor;

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
                FileUpload::make('splash_image')
                    ->required()
                    ->label('Splash Image')
                    ->columnSpanFull()
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(2048)
                    ->openable()
                    ->downloadable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
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
                Tables\Columns\ImageColumn::make('splash_image')
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
                        $data['event_date']=$record->meta->get('event_date');
                        $data['event_time_from']=$record->meta->get('event_time_from');
                        $data['event_time_to']=$record->meta->get('event_time_to');
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
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
