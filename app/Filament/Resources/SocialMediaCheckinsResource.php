<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialMediaCheckinsResource\Pages;
use App\Filament\Resources\SocialMediaCheckinsResource\RelationManagers;
use App\Models\SocialMediaCheckin;
use App\Models\SocialMediaCheckins;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialMediaCheckinsResource extends Resource
{
    protected static ?string $model = SocialMediaCheckin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('social_media_campaign_id')
                    ->relationship('campaign', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(50)
            ->groups([
                Group::make('created_at')
                    ->label('Date')
                    ->date(),
                Group::make('contact.name')
                    ->label('Name')
                    ->getTitleFromRecordUsing(function (SocialMediaCheckin $record): string {
                        return ucfirst($record->contact->name??'');
                    })
                    ->getKeyFromRecordUsing(fn (Model $record): string => $record->created_at)
                    ->collapsible(),
                Group::make('contact.organization.name')
                    ->label('Organization')
                    ->getTitleFromRecordUsing(function (SocialMediaCheckin $record): string {
                        return ucfirst($record->contact->organization->name??'');
                    })
                    ->getKeyFromRecordUsing(fn (Model $record): string => $record->created_at)
                    ->collapsible(),
                Group::make('campaign.project.name')
                    ->label('Project')
                    ->getTitleFromRecordUsing(function (SocialMediaCheckin $record): string {
                        return ucfirst($record->campaign->project->name??'');
                    })
                    ->getKeyFromRecordUsing(fn (Model $record): string => $record->created_at)
                    ->collapsible(),

            ])
            ->columns([
                Tables\Columns\TextColumn::make('contact.name')
                    ->formatStateUsing(function ($record) {
                        return "{$record->contact->name}\n{$record->contact->mobile}\n{$record->contact->email}\n\n{$record->reference_code}";
                    })
                    ->extraAttributes(['style' => 'white-space: pre-line'])
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('contact', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('contact.organization.name'),
                    // ->searchable(),
                Tables\Columns\TextColumn::make('campaign.name'),
                    // ->searchable(),
                Tables\Columns\TextColumn::make('campaign.campaignType.name')
                        ->label('Campaign Type'),
                        // ->searchable(),
                Tables\Columns\TextColumn::make('project.name')
                        ->label('Project'),
                        // ->searchable(),
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
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSocialMediaCheckins::route('/'),
            'create' => Pages\CreateSocialMediaCheckins::route('/create'),
            'edit' => Pages\EditSocialMediaCheckins::route('/{record}/edit'),
        ];
    }
}
