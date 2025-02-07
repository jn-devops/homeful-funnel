<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Checkin;
use Crwlr\Url\Url;

class GenerateAvailUrl
{
    use AsAction;

    public function handle(Checkin $checkin): string
    {
        $url = Url::parse(url: config('funnel.defaults.contact_callback'));
        $query = [
            'callback' => config(''),
            'showExtra' => true,
            'hidePassword' => true,
            'name' => $checkin->contact->name,
            'email' => $checkin->contact->email,
            'mobile' => $checkin->contact->mobile
        ];

        $url->queryArray(query: array_filter($query));

        return $url->toString();
    }
}
