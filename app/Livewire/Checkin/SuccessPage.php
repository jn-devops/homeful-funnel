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
