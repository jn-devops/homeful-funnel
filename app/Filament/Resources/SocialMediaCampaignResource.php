<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialMediaCampaignResource\Pages;
use App\Filament\Resources\SocialMediaCampaignResource\RelationManagers;
use App\Models\CampaignAuthor;
use App\Models\SocialMedia;
use App\Models\SocialMediaCampaign;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class SocialMediaCampaignResource extends Resource
{
    protected static ?string $model = SocialMediaCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        $response = Http::get('https://properties.homeful.ph/fetch-projects');
        $projects = collect($response->json()['projects'])->map(function ($project) {
            return [
                'name' => $project['name'],
                'code' => $project['code'],
                'location' => $project['location'],
            ];
        })->toArray();

        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('project_code')
                            ->label('Projects')
                            ->searchable()
                            ->options(collect($projects)->pluck('name', 'code'))
                            ->live()
                            ->afterStateUpdated(function (Set $set,$state)use($projects) {
                                $project = collect($projects)->where('code',$state)->first();
                                $set('project_name',$project['name']);
                                $set('location',$project['location']);
                            })
                            ->native(false),
                        Forms\Components\DatePicker::make('date_from')
                            ->required(),
                        Forms\Components\DatePicker::make('date_to')
                            ->required(),
                        Forms\Components\Select::make('author_id')
                            ->label('Author')
                            ->required()
                            ->options(CampaignAuthor::all()->pluck('name', 'id'))
                            ->native(false),
                        Forms\Components\Select::make('social_media_code')
                            ->label('Social Media')
                            ->required()
                            ->options(SocialMedia::all()->pluck('description', 'code'))
                            ->native(false),
                        FileUpload::make('registration_logo')
                            ->label('Registration Logo')
                            ->directory('registration_logos')
                            ->image()
                            ->imageEditor()
                            ->visibility('public'),
                        FileUpload::make('registration_background')
                            ->label('Registration Background')
                            ->directory('registration_backgrounds')
                            ->image()
                            ->imageEditor()
                            ->visibility('public'),
                        Forms\Components\TextInput::make('redirect_url')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('chat_url')
                            ->maxLength(255),
                        Forms\Components\MarkdownEditor::make('sms_feedback')
                            ->helperText(new HtmlString('
                       <ul>
                            <li><strong>@mobile:</strong> Contact’s mobile number.</li>
                            <li><strong>@name:</strong> Contact\'s name.</li>
                            <li><strong>@campaign:</strong> Campaign associated with the check-in.</li>
                            <li><strong>@registration_code:</strong> Generated unique code after registration.</li>
                            <li><strong>@chat_url:</strong> Link for Chat URL.</li>
                        </ul>
                    '))
                            ->default("Hi @‌name,\n\nThank you for your Registration. Here is your registration code:  @‌registration_code\n\nShould you wish to continue with the purchase, please click on the following link: @‌avail_url\n\nThank you for choosing our projects!")
                            ->columnSpan(2),

                        FileUpload::make('splash_image_url')
                            ->label('Splash Image')
                            ->directory('splash_images')
                            ->image()
                            ->imageEditor()
                            ->visibility('public'),
                        Forms\Components\TextInput::make('trip_label')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('undecided_label')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
                Forms\Components\Section::make('')
                    ->schema([
                        Placeholder::make('qr_code')
                            ->label('QR Code')
                            ->content(function (Get $get, Model $record) {
                                return \LaraZeus\Qr\Facades\Qr::render(
                                    data:  sprintf(
                                        '%s/socials/%s',
                                        config('app.url'),
                                        $record->id,
                                    ), // This is your model. We are passing the personalizations. If you want the default just comment it out.
                                );
                            })->hiddenOn('create'),
                        Placeholder::make('link')
                            ->content(function (Get $get, Model $record) {
                                $url = sprintf(
                                    '%s/socials/%s',
                                    config('app.url'),
                                    $record->id,
                                );
                                return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline">' . $url . '</a>');
                            })->hiddenOn('create'),
                        Placeholder::make('created_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;')),

                        Placeholder::make('updated_at')
                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? new HtmlString('&mdash;'))
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_from')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_to')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('social_media_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_background')
                    ->searchable(),
                Tables\Columns\TextColumn::make('redirect_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('chat_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sms_feedback')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('splash_image_url'),
                Tables\Columns\TextColumn::make('trip_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('undecided_label')
                    ->searchable(),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialMediaCampaigns::route('/'),
            'create' => Pages\CreateSocialMediaCampaign::route('/create'),
            'edit' => Pages\EditSocialMediaCampaign::route('/{record}/edit'),
        ];
    }
}
