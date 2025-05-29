<?php

namespace App\Livewire\Checkin\SocialMedia;

use App\Models\Checkin;
use Illuminate\Http\Request;
use Livewire\Component;

class SuccessPage extends Component
{
    public $checkin;
    public function mount(Request $request)
    {
        $this->checkin = Checkin::find($request->query('checkin'));
    }
    
    public function render()
    {
        return view('livewire.checkin.social-media.success-page', [
            'success_lottiefile' => asset('animation/SuccessAnimation.json'),
        ]);
    }
}
