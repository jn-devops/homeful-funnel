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
            InputFieldName::WAGES => $checkin->project->minimum_salary,
            InputFieldName::TCP => $checkin->project->default_price,
            InputFieldName::PERCENT_DP => $checkin->project->default_percent_down_payment,
            InputFieldName::PERCENT_MF => $checkin->project->default_percent_miscellaneous_fees,
            InputFieldName::DP_TERM => $checkin->project->default_down_payment_term,
            InputFieldName::BP_TERM => $checkin->project->default_balance_payment_term,
            InputFieldName::BP_INTEREST_RATE => $checkin->project->default_balance_payment_interest_rate,
            InputFieldName::SELLER_COMMISSION_CODE => $checkin->project->default_seller_commission_code,
            InputFieldName::PROMO_CODE => $checkin->registration_code,
        ]);

        return $response->json('reference_code');
    }
}
