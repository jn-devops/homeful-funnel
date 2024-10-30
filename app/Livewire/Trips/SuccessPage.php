<?php

namespace App\Livewire\Trips;

use App\Models\Trips;
use Illuminate\Http\Request;
use Livewire\Component;

class SuccessPage extends Component
{
    public $trip;

    public function mount(Request $request)
    {
        $this->trip = Trips::findOrFail($request->trip);
    }
    
    public function render()
    {
        return view('livewire.trips.success-page', [
            'success_lottiefile' => asset('animation/SuccessAnimation.json'),
        ]);
    }
}
