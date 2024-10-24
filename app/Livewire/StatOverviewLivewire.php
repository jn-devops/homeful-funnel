<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class StatOverviewLivewire extends Component
{
    public $data;

    public function mount(Array $data): void
    {
        $this->data = $data;
    }

    public function render()
    {
        return view('livewire.stat-overview-livewire', ['data' => $this->data]);
    }
}
