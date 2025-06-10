<?php

namespace App\Actions;

use App\Models\SocialMediaCheckin;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Log;

class ProcessFeedbackSocialMedia
{
    use AsAction;

    public function handle(SocialMediaCheckin|string $checkin, string $feedback): string
    {
        try {
            $checkin = $checkin instanceof SocialMediaCheckin ? $checkin : SocialMediaCheckin::where('id', $checkin)->first();

            $contact = $checkin->contact;
            $feedback = str_replace("\u{200C}", '', $feedback);
            return __(str_replace('@', ':', $feedback), [
                'mobile' => phone($contact->mobile, 'PH')->formatNational(),
                'name' => $contact->name??'',
                'campaign' => $checkin->campaign->name??'',
                'registration_code' => $checkin->registration_code??'',
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
