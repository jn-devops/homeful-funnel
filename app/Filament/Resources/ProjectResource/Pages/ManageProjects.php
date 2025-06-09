<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Http;

class ManageProjects extends ManageRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Sync')
                ->label('Sync')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {

                    $response = Http::get('https://properties.homeful.ph/fetch-projects');
                    $projects= collect($response->json()['projects']);
                    foreach ($projects as $project) {
                        $projectModel = Project::where('code', $project['code'])
                            ->orWhere('name', $project['name'])
                            ->first();

                        if (! $projectModel) {
                            // Neither code nor name exists, safe to create
                            Project::create([
                                'code' => $project['code'],
                                'name' => $project['name'],
                                'location' => $project['location'],
                            ]);
                        } else {
                            // Update the existing record (whichever matched)
                            $projectModel->code = $project['code'];
                            $projectModel->name = $project['name'];
                            $projectModel->project_location = $project['location'];
                            $projectModel->save();
                        }
                    }


                }),
        ];
    }
}
