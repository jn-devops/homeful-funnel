<?php

namespace App\Livewire\Checkin\SocialMedia;

use App\Models\Checkin;
use App\Models\SocialMediaCheckin;
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
        $this->checkin = SocialMediaCheckin::find($request->query('checkin'));
    }
    
    public function render()
    {
        return view('livewire.checkin.social-media.success-page', [
            'success_lottiefile' => asset('animation/SuccessAnimation.json'),
        ]);
    }

    public function trip(){
        if( $this->checkin->contact->state instanceof Registered){
            $this->checkin->contact->state->transitionTo(ForTripping::class);
        }
        return redirect()->to(config('app.url') .'/social-media/schedule-trip?campaign_id='.$this->checkin->campaign->id .'&contact_id='. $this->checkin->contact->id.' &checkin_id='.$this->checkin->id);
    }

    public function chat_url()
    {
        return redirect()->to($this->checkin->campaign->chat_url);
    }

    public function not_now()
    {
        if( $this->checkin->contact->state instanceof Registered){
            $this->checkin->contact->state->transitionTo(Undecided::class);
        }
        return redirect()->to($this->checkin->campaign->redirect_url);
    }
}
