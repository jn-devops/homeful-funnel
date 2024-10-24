<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Checkin;
use App\Models\Contact;

class ProcessFeedback
{
    use AsAction;

    public function handle(Checkin|string $checkin, string $feedback): string
    {
        $checkin = $checkin instanceof Checkin ? $checkin : Checkin::where('id', $checkin)->first();
        if ($checkin instanceof Checkin) {
            $contact = $checkin->contact;
            return __(str_replace('@', ':', $feedback), [
                'mobile' => phone($contact->mobile, 'PH')->formatNational(),
                'name' => $contact->name??'',
                'organization' => $contact->organization->name??'',
                'campaign' => $checkin->campaign->name??''
            ]);
        }
    }
}
