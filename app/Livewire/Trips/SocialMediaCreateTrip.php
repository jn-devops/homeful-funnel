<?php

namespace App\Livewire\Trips;

use App\Models\Contact;
use App\Models\Project;
use App\Models\SocialMediaCampaign;
use App\Models\SocialMediaCheckin;
use App\Models\Trips;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Filament\Forms;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;

class SocialMediaCreateTrip extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public String $error = '';
    public SocialMediaCampaign $campaign;
    public Contact $contact;
    public SocialMediaCheckin $checkin;
    public String $project;
    
    public function render()
    {
        return view('livewire.trips.social-media-create-trip');
    }

    public function mount(Request $request): void
    {
        $this->campaign = SocialMediaCampaign::find($request->campaign_id);
        $this->contact = Contact::find($request->contact_id);
        $this->checkin = SocialMediaCheckin::find($request->checkin_id);
        $this->form->fill([
            'project_id' => $this->campaign->project_code ?? '',
        ]);

    }

    public function form(Form $form): Form
    {
        $response = Http::get('https://properties.homeful.ph/fetch-projects');
        $projects = collect($response->json()['projects'])->mapWithKeys(function ($project) {
            return [
                $project['code'] => $project['name'],
            ];
        })->toArray();
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Choice of Project')
                    ->required()
                    ->inlineLabel()
                    ->native(false)
                    ->options($projects)
                    ->preload()
                    ->placeholder('Choose your preferred project'),
               Forms\Components\DatePicker::make('preferred_date')
                   ->required()
                   ->inlineLabel()
                   ->displayFormat('M d, Y')
                   ->minDate(Carbon::today())
                   ->native(false),
               Forms\Components\TimePicker::make('preferred_time')
                   ->required()
                   ->seconds(false)
                   ->minutesStep(00)
                   ->native(true)
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
        $data['checkin_id'] = $this->checkin->id;
        $record = Trips::create($data);
        // $this->form->model($record)->saveRelationships();
        return redirect()->route('schedule.success_page',['trip' => $record->id]);

    }
}
