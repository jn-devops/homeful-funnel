<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use App\Models\Checkin;

class CampaignsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Checkin::orderBy('created_at', 'desc')->take(3)
            )
            ->columns([
                TextColumn::make('campaign_name'),
                TextColumn::make('corp_presentation'),
                TextColumn::make('visited_booth'),
                TextColumn::make('visited_site'),
                TextColumn::make('visited_consulted'),
                TextColumn::make('companies_attended'),
            ]);
    }
}
