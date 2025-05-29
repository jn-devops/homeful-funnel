<?php

namespace App\Filament\Resources;

use App\Enums\SalesUnit;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PHPUnit\Metadata\Group;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationGroup = 'Dropdowns';

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
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('project_location')
                    ->label('Location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('product_code')
                    ->label('Product Code')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('project_image')
                    ->required()
                    ->label('Image')
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
                    ->downloadable(),
                Forms\Components\TextInput::make('rider_url')
                    ->label('Redirect URL')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('avail_url')
                    ->label('Avail URL')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('kwyc_check_campaign_code')
                    ->label('KWYC Campaign Code')
                    ->maxLength(255)
                    ->hidden()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('trip_notification_email')
                    ->label('Recipient for Trippings')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('default_product')
                    ->label('Default Product')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('minimum_salary')
                    ->label('Minimum Salary')
                    ->numeric()
                    ->maxValue(120000)
                    ->minValue(10000)
                    ->required(),
                Forms\Components\Select::make('sales_unit')
                    ->label('Brand')
                    ->native(false)
                    ->options(function () {
                        $data = array_map(fn($case) => [
                            'value' => $case->value,
                            'label' => $case->name,
                        ], SalesUnit::cases());
                        $values = array_column($data, 'value');
                        $labels = array_column($data, 'label');
                        return array_combine($values, array_map('ucfirst', $values));
                    })
                    ->required(),
                Forms\Components\TextInput::make('default_price')
                    ->label('Default Price')
                    ->numeric()
                    ->maxValue(10000000)
                    ->minValue(500000)
                    ->required(),
                Forms\Components\TextInput::make('default_percent_down_payment')
                    ->label('Default Percent Downpayment')
                    ->numeric()
                    ->prefix('%')
                    ->required(),
                Forms\Components\TextInput::make('default_percent_miscellaneous_fees')
                    ->label('Default Percent Miscellaneous Fees')
                    ->numeric()
                    ->prefix('%')
                    ->maxValue(15)
                    ->required(),
                Forms\Components\TextInput::make('default_down_payment_term')
                    ->label('Default Percent Downpayment Term(In Months)')
                    ->numeric()
                    ->maxValue(24)
                    ->required(),
                Forms\Components\TextInput::make('default_balance_payment_term')
                    ->label('Default Balance Payment Term(In Years)')
                    ->numeric()
                    ->maxValue(30)
                    ->required(),
                Forms\Components\TextInput::make('default_balance_payment_interest_rate')
                    ->label('Default Balance Payment Interest Rate')
                    ->numeric()
                    ->prefix('%')
                    ->maxValue(20)
                    ->required(),
                Forms\Components\TextInput::make('default_seller_commission_code')
                    ->label('Default Seller Commission Code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_code')
                    ->label('Seller Code')
                    ->required()
                    ->maxLength(255),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller_code')
                    ->label('Seller Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_code')
                    ->label('Product Code')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('project_image')
                    ->label('Image'),
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
                        $data['project_image']=$record->meta->get('project_image');
                        $data['seller_code']=$record->meta->get('seller_code');
                        $data['product_code']=$record->meta->get('product_code');
                        $data['default_product']=$record->meta->get('default_product');
                        $data['minimum_salary']=$record->meta->get('minimum_salary');
                        $data['default_price']=$record->meta->get('default_price');
                        $data['default_percent_down_payment']=$record->meta->get('default_percent_down_payment');
                        $data['default_percent_miscellaneous_fees']=$record->meta->get('default_percent_miscellaneous_fees');
                        $data['default_down_payment_term']=$record->meta->get('default_down_payment_term');
                        $data['default_balance_payment_term']=$record->meta->get('default_balance_payment_term');
                        $data['default_balance_payment_interest_rate']=$record->meta->get('default_balance_payment_interest_rate');
                        $data['default_seller_commission_code']=$record->meta->get('default_seller_commission_code');
                        $data['kwyc_check_campaign_code']=$record->meta->get('kwyc_check_campaign_code');
                        $data['sales_unit']=$record->meta->get('sales_unit');
                        $data['trip_notification_email']=$record->meta->get('trip_notification_email');
                        $data['project_location']=$record->meta->get('project_location');
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
            'index' => Pages\ManageProjects::route('/'),
        ];
    }
}
