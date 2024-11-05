<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Checkin, Link};
use Crwlr\Url\Url;

class GenerateAvailUrl
{
    use AsAction;

    public function handle(Checkin $checkin): string
    {
        $url = Url::parse(url: $this->getBookingUrl());
        $url->queryArray(query: [
            'mobile' => $checkin->contact->mobile,
            'email' => $checkin->contact->email,
            'identifier' => $this->getReferenceCode($checkin),
        ]);
        $link = Link::shortenUrl($url->toString());

        return route('link.show', ['shortUrl' => $link->short_url]);
    }

    protected function getBookingUrl(): string
    {
        return __('https://kwyc-check.net/campaign-checkin/:campaign_code', [
            'campaign_code' => config('funnel.kwyc-check.campaign_code')
        ]);
    }

    protected function getReferenceCode(Checkin $checkin): string
    {
        return app(GetReferenceCode::class)->run($checkin);
    }
}
