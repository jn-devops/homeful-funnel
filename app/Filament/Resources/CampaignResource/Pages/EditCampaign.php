<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\ProjectCampaign;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $model = Campaign::find($data['id']);
        $data['projects'] = collect(ProjectCampaign::where('campaign_id', $data['id'])
            ->with('project') // Eager-load the project relationship
            ->get() // Fetch the results as a collection
            ->pluck('project_id')
            ->toArray());
        
        $data['event_date'] = $model->event_date;
        $data['event_date_to'] = $model->event_date_to;
        $data['event_time_from'] = $model->event_time_from;
        $data['event_time_to'] = $model->event_time_to;
        $data['splash_image_url'] = $model->splash_image_url;
        $data['avail_label'] = $model->avail_label;
        $data['trip_label'] = $model->trip_label;
        $data['undecided_label'] = $model->undecided_label;


        // dd($data);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $campaign = Campaign::find($this->record->id);

        // Sync the selected projects through ProjectCampaign
        if (isset($data['projects'])) {
            $campaign->projectCampaigns()->delete(); // Clear existing associations
            foreach ($data['projects'] as $projectId) {
                $campaign->projectCampaigns()->create(['project_id' => $projectId]);
            }
        }

        $campaign->name = $data['name'];
        $campaign->campaign_type_id = $data['campaign_type_id'];
        $campaign->rider_url = $data['rider_url'];
        $campaign->feedback = $data['feedback'];
        // $campaign->organizations = $data['organizations'];
        $campaign->event_date = $data['event_date'];
        $campaign->event_date_to = $data['event_date_to'];
        $campaign->event_time_from = $data['event_time_from'];
        $campaign->event_time_to = $data['event_time_to'];
        $campaign->splash_image_url = $data['splash_image_url'];
        $campaign->avail_label = $data['avail_label'];
        $campaign->trip_label = $data['trip_label'];
        $campaign->undecided_label = $data['undecided_label'];
    
        $campaign->save();
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
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
                    ])
                ->label('Generate QR')
                ->modalFooterActions([])
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalWidth(MaxWidth::Small),
        ];
    }

}
