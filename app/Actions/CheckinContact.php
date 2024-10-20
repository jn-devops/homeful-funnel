<?php

namespace App\Actions;

use App\Models\{Campaign, Checkin, Contact};
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Organization;
use Illuminate\Support\Arr;

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
        $checkin->save();

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
            'code' => ['nullable', 'string'],//organization code
        ];
    }
}
