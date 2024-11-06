<?php

namespace App\Livewire\Checkin;

use App\Actions\GetReferenceCode;
use App\Models\Checkin;
use App\States\Availed;
use App\States\ForTripping;
use App\States\Registered;
use App\States\Undecided;
use Illuminate\Http\Request;
use Livewire\Component;

class SuccessPage extends Component
{
    public $checkin;
    public function mount(Request $request)
    {
        $this->checkin = Checkin::findOrFail($request->checkin);
    }

    public function render()
    {
        return view('livewire.checkin.success-page', [
            'success_lottiefile' => asset('animation/SuccessAnimation.json'),
        ]);
    }

    public function availed()
    {
        if( $this->checkin->contact->state instanceof Registered){
            $this->checkin->contact->state->transitionTo(Availed::class);
        }
//        $reference_code = app(GetReferenceCode::class)->run($this->checkin);
//        dd($reference_code);
//        return redirect()->to(config('kwyc-check.campaign_url') . '?email=' . $this->checkin->contact->email . '&mobile=' . $this->checkin->contact->mobile . '&identifier='.$this->checkin->registration_code.'&code='.$this->checkin->campaign->project->seller_code.'&choice='.$this->checkin->campaign->project->product_code );
//        return redirect()->to(config('kwyc-check.campaign_url') . '?email=' . $this->checkin->contact->email . '&mobile=' . $this->checkin->contact->mobile . '&identifier='.$reference_code.'&code='.$this->checkin->campaign->project->seller_code.'&choice='.$this->checkin->campaign->project->product_code );
        return redirect()->to($this->checkin->getOrGenerateAvailUrl());
    }

    public function trip(){
        if( $this->checkin->contact->state instanceof Registered){
            $this->checkin->contact->state->transitionTo(ForTripping::class);
        }
        return redirect()->to(config('app.url') .'/schedule-trip?campaign_id='.$this->checkin->campaign->id .'&contact_id='. $this->checkin->contact->id.' &checkin_id='.$this->checkin->id);
    }

    public function not_now()
    {
        if( $this->checkin->contact->state instanceof Registered){
            $this->checkin->contact->state->transitionTo(Undecided::class);
        }
        return redirect()->to($this->checkin->project->rider_url);
    }

    public function redirect_page_to($url){
        return redirect()->to($url);
    }
}
