<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Actions\GetReferenceCode;
use Illuminate\Http\Request;
use App\Models\Checkin;

class AvailController extends Controller
{
    public Checkin $checkin;

    public function __invoke(Request $request, Checkin $checkin): \Illuminate\Http\RedirectResponse
    {
        $this->checkin = $checkin;

        return redirect()->away($this->getBookingUrl())->with([
            'mobile' => $this->checkin->contact->mobile,
            'email' => $this->checkin->contact->email,
            'identifier' => $this->getReferenceCode(),
        ]);
    }

    protected function getBookingUrl(): string
    {
        return __('https://kwyc-check.net/campaign-checkin/:campaign_code', [
            'campaign_code' => '9ccef822-4209-4e0a-bb40-232da5cafdf1'
        ]);
    }

    protected function getReferenceCode(): string
    {
        return app(GetReferenceCode::class)->run($this->checkin);
    }
}
