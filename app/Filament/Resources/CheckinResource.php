<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckinResource\Pages;
use App\Filament\Resources\CheckinResource\RelationManagers;
use App\Models\Checkin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CheckinResource extends Resource
{
    protected static ?string $model = Checkin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\Select::make('contact_id')
//                    ->relationship('contact', 'id'),
//                Forms\Components\TextInput::make('contact.name'),

                Forms\Components\Select::make('campaign_id')
                    ->relationship('campaign', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        // dd(Checkin::where('id', '9d4f86fc-c4a4-4f1f-8268-604a4b21e506')->first()->contact);
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // Tables\Columns\TextColumn::make('id')
                //     ->label('ID')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('contact.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact.organization.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('campaign.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('campaign.campaignType.name')
                        ->label('Campaign Type')
                        ->searchable(),
                Tables\Columns\TextColumn::make('campaign.project.name')
                        ->label('Project')
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
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageCheckins::route('/'),
        ];
    }
}
