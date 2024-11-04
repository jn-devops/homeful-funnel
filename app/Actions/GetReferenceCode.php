<?php

namespace App\Actions;

use Homeful\Common\Classes\Input as InputFieldName;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Checkin;

class GetReferenceCode
{
    use AsAction;

    public function handle(Checkin $checkin)
    {
        $booking_server_url = 'https://elanvital-booking.homeful.ph/api/create-reference';

        $data = [
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
        ];

        $validated = Validator::validate($data, [
            InputFieldName::SKU => ['required', 'string'],
            InputFieldName::WAGES => ['required', 'numeric', 'min:10000', 'max:120000'],
            InputFieldName::TCP => ['required', 'numeric', 'min:500000', 'max:10000000'],
            InputFieldName::PERCENT_DP => ['required', 'numeric', 'min:0', 'max:0.50'],
            InputFieldName::PERCENT_MF => ['required', 'numeric', 'min:0', 'max:0.15'],
            InputFieldName::DP_TERM => ['required', 'integer', 'min:0', 'max:24'],
            InputFieldName::BP_TERM => ['required', 'integer', 'min:0', 'max:30'],
            InputFieldName::BP_INTEREST_RATE => ['required', 'numeric', 'min:0', 'max:0.20'],
            InputFieldName::SELLER_COMMISSION_CODE => ['required', 'string'],
            InputFieldName::PROMO_CODE => ['nullable', 'string'],
        ]);

        $response = Http::withHeader('accept', 'application/json')->post(url: $booking_server_url, data: $validated);

        return $response->json('reference_code');
    }
}
