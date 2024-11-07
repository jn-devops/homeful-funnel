<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\{Checkin, Link};
use Crwlr\Url\Url;
use function PHPUnit\Framework\throwException;

class GenerateAvailUrl
{
    use AsAction;

    public function handle(Checkin $checkin): string
    {
	    try {
		    $url = Url::parse(url: $this->getBookingUrl($checkin));
		    $url->queryArray(query: [
			    'mobile' => $checkin->contact->mobile,
			    'email' => $checkin->contact->email,
			    'identifier' => $this->getReferenceCode($checkin),
		    ]);
		    $link = Link::shortenUrl($url->toString());
		    $link->checkin()->associate($checkin);
		    $link->save();

	    }catch (\Exception $exception){
		    throwException($exception);
	    }
	    return route('link.show', ['shortUrl' => $link->short_url]);
    }

    protected function getBookingUrl(Checkin $checkin): string
    {
        $authentication_server = config('funnel.defaults.authentication_server');
        $authentication_url = 'https://' . $authentication_server . '/campaign-checkin/:campaign_code';

        return __($authentication_url, [
            'campaign_code' => $checkin->project?->kwyc_check_campaign_code
                ? $checkin->project->sales_unit->campaign_code()
                : config('funnel.kwyc-check.campaign_code')
        ]);
    }

    protected function getReferenceCode(Checkin $checkin): string
    {
        return app(GetReferenceCode::class)->run($checkin);
    }
}
