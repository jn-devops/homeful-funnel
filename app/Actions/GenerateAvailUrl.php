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
        $url = Url::parse(url: config('funnel.defaults.contact_register'));
        $query = [
            'callback' => config('funnel.defaults.contract_callback'),
            'showExtra' => true,
            'hidePassword' => true,
            'name' => $checkin->contact->name,
            'email' => $checkin->contact->email,
            'mobile' => phone($checkin->contact->mobile, 'PH')->formatForMobileDialingInCountry('PH')
        ];
        if($checkin->project){
            $query['project_code'] = $checkin->project->code;
        }

        $url->queryArray(query: array_filter($query));

        return $url->toString();
    }
}
