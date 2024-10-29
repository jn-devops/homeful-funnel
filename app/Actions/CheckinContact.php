<?php

namespace App\Actions;

use App\Notifications\AcknowledgeAvailmentNotification;
use App\Models\{Campaign, Checkin, Contact};
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Organization;
use Illuminate\Support\Arr;
use App\Models\Project;

class CheckinContact
{
    use AsAction;

    public function handle(Campaign $campaign, Contact $contact, Organization $organization = null, array $attribs = [])
    {
        $checkin = new Checkin;
        $checkin->campaign()->associate($campaign);
        if ($organization)
            $contact->organization()->associate($organization);
        if ($attribs)
            $contact->update($attribs);

        $contact->save();
        $checkin->contact()->associate($contact);
        if ($project_name = Arr::get($attribs, 'project')) {
            $project = Project::where('name', $project_name)->firstOrFail();
            if ($project instanceof Project) {
                $checkin->rider_url = $project->rider_url;
            }
        }
        $checkin->save();
//        $contact->notify(new AcknowledgeAvailmentNotification('Thank you for checking in. Here is your check-in reference code: '.substr($contact->id, -12).'
//
//If you are ready to secure the unit, kindly click on the link provided below:
//https://www.homeful.ph/pagsikat/availnow/promocode=?pasinaya'.substr($contact->id, -12)));
        $contact->notify(new AcknowledgeAvailmentNotification($checkin));

        return $checkin;
    }

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
            'email'=>['nullable','email']//contact email
        ];
    }
}
