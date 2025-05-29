<?php

namespace App\Notifications;

use LBHurtado\EngageSpark\Notifications\Adhoc as BaseAdhoc;
use App\Actions\ProcessFeedback;
use App\Models\Checkin;

class AcknowledgeAvailmentNotification extends BaseAdhoc
{
    public function __construct(Checkin $checkin)
    {
        $default = 'Thank you for checking in. Here is your check-in reference code: '.substr($checkin->contact->id, -12).'

If you are ready to secure the unit, kindly click on the link provided below:
https://www.homeful.ph/pagsikat/availnow/promocode=?pasinaya'.substr($checkin->contact->id, -12);

        $message = ProcessFeedback::run($checkin, $checkin->campaign?->feedback ?: $default);

        parent::__construct($message);
    }
}
