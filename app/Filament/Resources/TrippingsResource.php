<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrippingsResource\Pages;
use App\Filament\Resources\TrippingsResource\RelationManagers;
use App\Models\Trippings;
use App\Models\Trips;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrippingsResource extends Resource
{
    protected static ?string $model = Trips::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->date()
                    ->label('Created Date'),
                TextColumn::make('project.name')
                    ->label('Project Name'),
                TextColumn::make('preferred_date')
                    ->label('Preferred Date')
                    ->formatStateUsing(function ($record) {
                        return Carbon::parse($record->preferred_date)->format('F d, Y');
                    }),
                TextColumn::make('preferred_time')
                    ->label('Preferred Time')
                    ->formatStateUsing(function ($record) {
                        return Carbon::parse($record->preferred_time)->format('H:i A');
                    }),
                TextColumn::make('remarks')
                    ->label('Remarks'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTrippings::route('/'),
            // 'create' => Pages\CreateTrippings::route('/create'),
            // 'edit' => Pages\EditTrippings::route('/{record}/edit'),
        ];
    }
}
