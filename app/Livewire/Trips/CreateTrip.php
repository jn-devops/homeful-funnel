<?php

namespace App\Livewire\Trips;

use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Trips;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class CreateTrip extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public String $error = '';
    public Campaign $campaign;
    public Contact $contact;
    public String $project;


    public function mount(Request $request): void
    {
        $this->campaign = Campaign::find($request->campaign_id);
        $this->contact = Contact::find($request->contact_id);
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Choice of Project')
                    ->required()
                    ->inlineLabel()
                    ->native(false)
                    ->options(Project::all()->pluck('name', 'id')->toArray())
                    ->preload()
                    ->placeholder('Choose your preferred project'),
                Forms\Components\DatePicker::make('preferred_date')
                    ->required()
                    ->inlineLabel()
                    ->displayFormat('M d, Y')
                    ->native(false),
                Forms\Components\TimePicker::make('preferred_time')
                    ->required()
                    ->seconds(false)
                    ->minutesStep(00)
                    ->native(false)
                    ->displayFormat('h i a')
                    ->inlineLabel()
                    ->format('H:i:s'),
                Forms\Components\Textarea::make('remarks')
                    ->maxLength(255),
            ])
            ->statePath('data')
            ->model(Trips::class);
    }

    public function save(): Redirector|null
    {
        $data = $this->form->getState();
        $data['campaign_id'] = $this->campaign->id;
        $data['contact_id'] = $this->contact->id;
        $record = Trips::create($data);
        // $this->form->model($record)->saveRelationships();
        return redirect()->route('schedule.success_page',['trip' => $record->id]);

    }

    public function render(): View
    {
        return view('livewire.trips.create-trip');
    }
}
