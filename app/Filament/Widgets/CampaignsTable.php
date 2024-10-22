<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use App\Models\Checkin;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class CampaignsTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        $campaigns = $this->filters['campaigns'] ?? null;
        $start = $this->filters['start_date'] ?? null;
        $end = $this->filters['end_date'] ?? null;

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
