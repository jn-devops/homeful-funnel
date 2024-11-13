<?php

namespace App\Filament\Resources\UpdateLogsResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UpdateLogRelationManager extends RelationManager
{
    protected static string $relationship = 'updateLog';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\TextInput::make('field')
//                    ->required()
//                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('updated_at')
                    ->formatStateUsing(function (string $state, Model $record) {
                        $date = Carbon::parse($state);
                        $formattedDate = $date->format('F j, Y');
                        $formattedTime = $date->format('g:i A');
                        $timeAgo = $date->diffForHumans(); // 1 hour ago
                        $user = $record->user==null?'':$record->user->name;
                        return $formattedDate . '<br>' . $formattedTime . '<br>'.$user. '<br><small>' . $timeAgo . '</small>';
                    })
                    ->html(),
                TextColumn::make('field')->grow(false),
                TextColumn::make('from')->wrap()->grow(false),
                TextColumn::make('to')->wrap()->grow(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
