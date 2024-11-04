<?php

namespace App\Actions;

use Homeful\Common\Classes\Input as InputFieldName;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Http;
use App\Models\Checkin;

class GetReferenceCode
{
    use AsAction;

    public function handle(Checkin $checkin)
    {
        $booking_server_url = 'https://elanvital-booking.homeful.ph/api/create-reference';

        $response = Http::withHeader('accept', 'application/json')->post(url: $booking_server_url, data: [
            InputFieldName::SKU => $checkin->project->product_code,
            InputFieldName::WAGES => 15000,
            InputFieldName::TCP => 850000,
            InputFieldName::PERCENT_DP => 0.10,
            InputFieldName::PERCENT_MF => 0.085,
            InputFieldName::DP_TERM => 24,
            InputFieldName::BP_TERM => 30,
            InputFieldName::BP_INTEREST_RATE => 0.065,
            InputFieldName::SELLER_COMMISSION_CODE => $checkin->project->default_seller_commission_code,
            InputFieldName::PROMO_CODE => $checkin->registration_code,
        ]);

        return $response->json('reference_code');
    }
}
