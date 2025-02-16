<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProjectCampaign;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
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
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
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
                    ->label('Date From')
                    ->date()
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('event_date_to')
                    ->label('Date To')
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
                Forms\Components\Select::make('projects')
                    ->label('Project')
                    ->options(Project::pluck('name', 'id')->toArray())
                    ->multiple()
                    // ->hintAction(fn (Select $component) => Forms\Components\Actions\Action::make('select all')
                    //     ->action(fn () => $component->state(Project::pluck('id')->toArray()))
                    // )
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
                Forms\Components\TextInput::make('chat_url')
                    ->label('Chat URL')
                    ->live()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('feedback')
                    ->helperText(new HtmlString('
                       <ul>
                            <li><strong>@mobile:</strong> Contact’s mobile number.</li>
                            <li><strong>@name:</strong> Contact\'s name.</li>
                            <li><strong>@organization:</strong> Contact\'s organization name.</li>
                            <li><strong>@campaign:</strong> Campaign associated with the check-in.</li>
                            <li><strong>@registration_code:</strong> Generated unique code after registration.</li>
                            <li><strong>@campaign_type:</strong> Type of campaign associated with the check-in.</li>
                            <li><strong>@avail_url:</strong> Link to Avail.</li>
                            <li><strong>@chat_url:</strong> Link for Chat URL.</li>
                        </ul>
                    '))
                    ->default("Hi @‌name,\n\nThank you for your Registration. Here is your registration code:  @‌registration_code\n\nShould you wish to continue with the purchase, please click on the following link: @‌avail_url\n\nThank you for choosing our projects!")
                    ->columnSpan(2),
                Forms\Components\TextInput::make('splash_image_url')
                    ->label('Splash Image Url')
                    ->url()
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Select::make('organizations')
                    ->label('Organization List')
                    ->required()
                    ->relationship('organizations', 'name')
                    ->multiple()
                    ->searchable()
                    ->hintAction(fn (Select $component) => Forms\Components\Actions\Action::make('select all')
                        ->action(fn () => $component->state(Organization::pluck('id')->toArray()))
                    )
                    ->columnSpanFull()
                    ->preload(),
                Forms\Components\TextInput::make('avail_label')
                    ->label('Avail Label')
                    ->default('Avail Now')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('trip_label')
                    ->label('Trip Label')
                    ->default('Request Tripping')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('undecided_label')
                    ->label('Undecided Label')
                    ->default('Request Tripping')
                    ->columnSpan(1),
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('campaignType.name')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rider_url')
                    ->label('Redirect URL'),
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
                SelectFilter::make('project')
                    ->options(Project::all()->pluck('name','id'))
                    ->native(false)
                    ->multiple()
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['values'],
                            fn (\Illuminate\Database\Eloquent\Builder $query) => $query
                                ->orWhereHas('projects', function (\Illuminate\Database\Eloquent\Builder $query) use ($data) {
                                    $query->whereIn('projects.id', $data['values']);
                                })
                                ->orWhereIn('project_id', $data['values']) // Add direct condition on project_id
                        );
                    })

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

                        $data['avail_label']=$record->avail_label;
                        $data['trip_label']=$record->trip_label;
                        $data['undecided_label']=$record->undecided_label;
                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
                Action::make('View QR')
                    ->icon('heroicon-m-qr-code')
                    ->modalIcon('heroicon-m-qr-code')
                    ->form([
                        Select::make('organization')
                            ->options(function (Get $get, Campaign $record) {
                                return $record->organizations()->get()->pluck('name', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->debounce(100),
                        Placeholder::make('qr_code')
                            ->label('QR Code')
                            ->content(function (Get $get, Model $record) {
                                return \LaraZeus\Qr\Facades\Qr::render(
                                    data:  sprintf(
                                        '%s/checkin/%s%s',
                                        config('app.url'),
                                        $record->id,
                                        $get('organization') ? '?organization=' . $get('organization') : ''
                                    ), // This is your model. We are passing the personalizations. If you want the default just comment it out.
                                );
                        }),
                        Placeholder::make('link')
                            ->content(function (Get $get, Model $record) {
                                $url = sprintf(
                                    '%s/checkin/%s%s',
                                    config('app.url'),
                                    $record->id,
                                    $get('organization') ? '?organization=' . $get('organization') : ''
                                );
                                return new HtmlString('<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline">' . $url . '</a>');
                            }),


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
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
