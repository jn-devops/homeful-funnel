<?php

namespace App\Livewire\Checkin;

use Illuminate\Http\Request;
use Livewire\Component;

class SuccessPage extends Component
{
    public $checkin; 
    public function mount()
    {
        $this->checkin = session()->get('checkin_data');
    }

    public function render()
    {
        return view('livewire.checkin.success-page', [
            'success_lottiefile' => asset('animation/SuccessAnimation.json'),
        ]);
    }

    public function redirect_page_to($url){
        return redirect()->to($url);
    }
}
