<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Actions\GenerateAvailUrl;
use App\Models\Checkin;
use App\Models\Contact;

class ProcessFeedback
{
    use AsAction;

    public function handle(Checkin|string $checkin, string $feedback): string
    {
        try {
            $checkin = $checkin instanceof Checkin ? $checkin : Checkin::where('id', $checkin)->first();

            $contact = $checkin->contact;
            $feedback = str_replace("\u{200C}", '', $feedback);
            return __(str_replace('@', ':', $feedback), [
                'mobile' => phone($contact->mobile, 'PH')->formatNational(),
                'name' => $contact->name??'',
                'organization' => $contact->organization->name??'',
                'campaign' => $checkin->campaign->name??'',
                'registration_code' => $checkin->registration_code??'',
                'campaign_type' => $checkin->campaign->campaignType->name??'',
                'avail_url' => $checkin->getOrGenerateAvailUrl()??'',
                'chat_url' => $checkin->campaign->chat_url??'',
            ]);
        }catch (\Exception $exception){
            Log::error('Error ProcessFeedback', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'checkin_id' => $checkin->id ?? null,
            ]);
            throw $exception;
        }
    }
}
