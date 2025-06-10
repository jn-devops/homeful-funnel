<?php

namespace App\Notifications;

use App\Actions\ProcessFeedbackSocialMedia;
use App\Models\SocialMediaCheckin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LBHurtado\EngageSpark\Notifications\Adhoc as BaseAdhoc;

class AcknowledgeAvailmentNotificationSocialMedia extends BaseAdhoc
{
    public function __construct(SocialMediaCheckin $checkin)
    {
        $default = 'Thank you for checking in. Here is your check-in reference code: '.substr($checkin->contact->id, -12).'

If you are ready to secure the unit, kindly click on the link provided below:
https://www.homeful.ph/pagsikat/availnow/promocode=?pasinaya'.substr($checkin->contact->id, -12);

        $message = ProcessFeedbackSocialMedia::run($checkin, $checkin->campaign?->sms_feedback ?: $default);

        parent::__construct($message);
    }
}
