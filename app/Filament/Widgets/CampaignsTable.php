<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use App\Models\Checkin;
use App\States\Availed;
use App\States\FirstState;
use App\States\ForTripping;
use App\States\Registered;
use App\States\Undecided;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Tables\Actions\Action;

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
                Campaign::orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('organization')
                    ->label('Organization')
                    ->getStateUsing(function ($record) {
                        return 'view';
                    })
                    ->url(function ($record) {
                        return '/organizations';
                    })
                    ->openUrlInNewTab()
                    ->color('warning')
                    ->extraAttributes([
                        'class' => 'mx-auto font-semibold',
                    ]),
                TextColumn::make('name')
                    ->label('Campaign Name'),
                TextColumn::make('project.name')
                    ->label('Project Chosen'),
                TextColumn::make('registered')
                    ->label('Registered')
                    ->badge()
                    ->color('success')
                    ->getStateUsing(function ($record) {
                        return $record->checkins()->whereHas('contact', function ($q) {
                                    $q->whereIn('state', [Registered::class, FirstState::class]);
                                })->count() . ' Prospects';
                    })
                    ->action(
                        Action::make('registered')
                            ->form(fn($record) => [
                                Livewire::make(\App\Livewire\Checkin\StateModal::class, [
                                    'state' => 'Registered',
                                    'id' => $record->id,
                                ]),
                            ])
                            ->modalHeading(fn($record) => 'Registered from '.$record->name.' ('.$record->checkins()->whereHas('contact', function ($q) { $q->whereIn('state', [Registered::class, FirstState::class]); })->count().')')
                            ->modalWidth(MaxWidth::SixExtraLarge)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false),
                    ),
                TextColumn::make('for_tripping')
                    ->label('For Tripping')
                    ->badge()
                    ->color('warning')
                    ->getStateUsing(function ($record) {
                        return $record->checkins()->whereHas('contact', function ($q) {
                                    $q->where('state', ForTripping::class);
                                })->count() . ' Prospects';
                    })
                    ->action(
                        Action::make('for_tripping')
                            ->label('For Tripping')
                            ->form(fn($record) => [
                                Livewire::make(\App\Livewire\Checkin\StateModal::class, [
                                    'state' => 'For Tripping',
                                    'id' => $record->id,
                                ]),
                            ])
                            ->modalHeading(fn($record) => 'For Tripping Requests from '.$record->name.' ('.$record->checkins()->whereHas('contact', function ($q) { $q->where('state', ForTripping::class); })->count().')')
                            ->modalWidth(MaxWidth::SixExtraLarge)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false),
                    ),
                TextColumn::make('availed')
                    ->label('Availed')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(function ($record) {
                        return $record->checkins()->whereHas('contact', function ($q) {
                                    $q->where('state', Availed::class);
                                })->count() . ' Prospects';
                    })
                    ->action(
                        Action::make('availed')
                            ->label('Availed')
                            ->form(fn($record) => [
                                Livewire::make(\App\Livewire\Checkin\StateModal::class, [
                                    'state' => 'Availed',
                                    'id' => $record->id,
                                ]),
                            ])
                            ->modalHeading(fn($record) => 'Availed from '.$record->name.' ('.$record->checkins()->whereHas('contact', function ($q) { $q->where('state', Availed::class); })->count().')')
                            ->modalWidth(MaxWidth::SixExtraLarge)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false),
                    ),
                TextColumn::make('not_now')
                    ->label('Not Now')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(function ($record) {
                        return $record->checkins()->whereHas('contact', function ($q) {
                                    $q->where('state', Undecided::class);
                                })->count() . ' Prospects';
                    })
                    ->action(
                        Action::make('not_now')
                            ->label('Not Now')
                            ->form(fn($record) => [
                                Livewire::make(\App\Livewire\Checkin\StateModal::class, [
                                    'state' => 'Not Now',
                                    'id' => $record->id,
                                ]),
                            ])
                            ->modalHeading(fn($record) => 'Clicked Not Now from '.$record->name.' ('.$record->checkins()->whereHas('contact', function ($q) { $q->where('state', Undecided::class); })->count().')')
                            ->modalWidth(MaxWidth::SixExtraLarge)
                            ->modalSubmitAction(false)
                            ->modalCancelAction(false),
                    ),
                TextColumn::make('consulted')
                    ->label('Consulted')
                    ->badge()
                    ->color('primary')
                    ->getStateUsing(function ($record) {
                        return '0'; // TODO: No Data Source
                    }),
            ])
            ->actions([
            ]);
    }
}
