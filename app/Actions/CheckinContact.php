<?php

namespace App\Actions;

use App\Notifications\AcknowledgeAvailmentNotification;
use App\Models\{Campaign, Checkin, Contact};
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Organization;
use Illuminate\Support\Arr;
use App\Models\Project;
use function PHPUnit\Framework\throwException;

class CheckinContact
{
    use AsAction;

    public function handle(Campaign $campaign, Contact $contact, Organization $organization = null, array $attribs = [])
    {
        try {
            $checkin = new Checkin();
            $checkin->campaign()->associate($campaign);

            // If there are new attribs to apply
            if (!empty($attribs)) {
                // Check if another contact exists with the same name or mobile
                $existing = Contact::where(function ($q) use ($attribs) {
                    $q->where('name', $attribs['name'] ?? null)
                        ->orWhere('mobile', $attribs['mobile'] ?? null);
                })
                    ->where('id', '!=', $contact->id)
                    ->first();

                // If found, use the existing one
                if ($existing) {
                    $contact = $existing;
                } else {
                    $contact->fill($attribs);
                }
            }

            // Associate organization if applicable
            if ($organization) {
                $contact->organization()->associate($organization);
            }

            $contact->save();

            $checkin->contact()->associate($contact);

            if ($projectName = Arr::get($attribs, 'project')) {
                $project = Project::where('name', $projectName)->firstOrFail();
                $checkin->project()->associate($project);
            }

            $checkin->save();

            $contact->notify(new AcknowledgeAvailmentNotification($checkin));

            return $checkin;

        } catch (\Exception $e) {
            Log::error('Error CheckinContact', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'checkin_id' => $checkin->id ?? null,
            ]);

            throw $e;
        }
    }


//    public function handle(Campaign $campaign, Contact $contact, Organization $organization = null, array $attribs = [])
//    {
//        try {
//            $checkin = new Checkin;
//            $checkin->campaign()->associate($campaign);
//            if ($organization)
//                $contact->organization()->associate($organization);
//            if ($attribs){
//                $contact->update($attribs);
//            }
//
//            $contact->save();
//
//            $checkin->contact()->associate($contact);
//            if ($project_name = Arr::get($attribs, 'project')) {
//                $project = Project::where('name', $project_name)->firstOrFail();
//                $checkin->project()->associate($project);
//            }
//            $checkin->save();
//            $contact->notify(new AcknowledgeAvailmentNotification($checkin));
//            return $checkin;
//
//        }catch (\Exception $e){
//            Log::error('Error CheckinContact', [
//                'error' => $e->getMessage(),
//                'trace' => $e->getTraceAsString(),
//                'checkin_id' => $checkin->id ?? null,
//            ]);
//            throw $e;
//        }
//    }

    public function asController(ActionRequest $request, Campaign $campaign, Contact $contact)
    {
        $attribs = $request->validated();
        $code = Arr::pull($attribs, 'code');
        $organization = Organization::where('code', $code)->first();
        $checkin = $this->handle($campaign, $contact, $organization, $attribs);

        return $checkin->toArray();
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],//contact name
            'first_name' => ['nullable', 'string'],//contact name
            'middle_name' => ['nullable', 'string'],//contact name
            'last_name' => ['nullable', 'string'],//contact name
            'code' => ['nullable', 'string'],//organization code
            'email'=> ['nullable','email'],//contact email,
            'project' => ['nullable','string']//project name,
        ];
    }
}
