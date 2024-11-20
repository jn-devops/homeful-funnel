<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Models\Campaign;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $campaign = Campaign::create($data);
        if (isset($data['projects'])) {
            $campaign->projectCampaigns()->delete(); // Clear existing associations
            foreach ($data['projects'] as $projectId) {
                $campaign->projectCampaigns()->create(['project_id' => $projectId]);
            }
        }
        return $campaign;
    }

}
