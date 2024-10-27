<?php

namespace App\Livewire\Checkin;

use App\Models\Checkin;
use Illuminate\Http\Request;
use Livewire\Component;

class SuccessPage extends Component
{
    public $checkin;
    public function mount(Request $request)
    {
        $this->checkin = Checkin::findOrFail($request->checkin);
//        dd($this->checkin);
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
