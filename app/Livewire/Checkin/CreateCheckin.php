<?php

namespace App\Livewire\Checkin;

use App\Actions\CheckinContact;
use App\Models\Campaign;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\Project;
use Exception;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class CreateCheckin extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];
    public Campaign $campaign;
    public Organization $organization;
    public String $error = '';
    public bool $isDifferentCompanyBefore=false;

    public function mount(Campaign $campaign ,Organization $organization): void
    {
        $this->organization=$organization;
        $this->campaign=$campaign;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('project')
                        ->label('Choice of Project')
                        ->required()
                        ->inlineLabel()
                        ->options(Project::all()->pluck('name', 'name')->toArray()),
                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name: ')
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel(),
                    // Forms\Components\TextInput::make('middle_name')
                    //     ->label('Middle Name: ')
                    //     ->required()
                    //     ->maxLength(255)
                    //     ->inlineLabel(),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Last Name: ')
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel(),
                    // Forms\Components\ToggleButtons::make('ready_to_avail')
                    //     ->label('Ready to avail: ')
                    //     ->inline()
                    //     ->inlineLabel()
                    //     ->required()
                    //     ->boolean()
                    //     ->columnSpanFull(),
                    Forms\Components\TextInput::make('mobile')
                        ->label('Mobile Number ')
                        ->required()
                        ->prefix('+63')
                        ->regex("/^[0-9]+$/")
                        ->minLength(10)
                        ->maxLength(10)
                        ->afterStateUpdated(function(Set $set, String $state=null){
                            if(strlen($state??'')==10){
                                $contact = Contact::where('mobile','+63'.$state??'')->first();
                                if($contact){
                                    $set('first_name',$contact->first_name??'');
                                    $set('last_name',$contact->last_name??'');
                                    $set('middle_name',$contact->middle_name??'');

                                    if (!empty($contact->organization->id) && $contact->organization->id != $this->organization->id) {
                                        $this->isDifferentCompanyBefore = true;
                                    }else{
                                        $this->isDifferentCompanyBefore = false;
                                    }
                                }
                            }
                        })
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                            $livewire->validateOnly($component->getStatePath());
                        })
                        ->inlineLabel(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->inlineLabel()
                        ->required()
                ]),
            ])
            ->statePath('data')
            ->model(Checkin::class);
    }

    public function save():Redirector|null
    {
        $data = $this->form->getState();
        try {
//            $contact =Contact::create([
//                'mobile' => $data['mobile'],
//                'name' => $data['first_name'].' '.$data['middle_name'].' '.$data['last_name'],
//            ]);
//           $checkin= Checkin::create([
//                'campaign_id' => $this->campaign->id,
//                'organization_id' => $this->organization->id,
//                'meta' => $data,
//                'contact_id' => $contact->id,
//            ]);

            $url = route('checkin-contact', ['campaign' => $this->campaign->id, 'contact' => $data['mobile']]);
          $response =  Http::post($url, [
                'name' => $data['first_name'].' '.$data['last_name'],
                'code' => $this->organization->code,
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                // 'middle_name' => Arr::get($data, 'middle_name', 'N/A'),
                'mobile' => $data['mobile'],
                // 'ready_to_avail'=>$data['ready_to_avail'],
                'project' => $data['project'],
                'email' => $data['email'],
                'meta'=>[
                    'name'=> $data['first_name'].' '.$data['last_name'],
                    'last_name' => $data['last_name'],
                    'first_name' => $data['first_name'],
                    // 'middle_name' => Arr::get($data, 'middle_name', 'N/A'),
                    'mobile' => $data['mobile'],
                    // 'ready_to_avail' =>$data['ready_to_avail'],
                    'project' => $data['project'],
                    'email' => $data['email'],
                ]
            ]);

            // CheckinContact::dispatch($this->campaign, $contact, $this->organization, $data);


            // $this->dispatch('open-modal', id: 'success-modal');
            // if ($data['ready_to_avail']) {
            //     $params = [
            //         'last_name' => $data['last_name'],
            //         'first_name' => $data['first_name'],
            //         'middle_name' => Arr::get($data, 'middle_name', 'N/A'),
            //         'mobile' => $data['mobile'],
            //     ];
            //     return redirect()->to('https://gnc-lazarus.homeful.ph/client-information?' . http_build_query($params));
            // }

            // return redirect()->to($this->campaign->rider_url ?? 'https://homeful.ph/');

            // TODO: edit the array below. no data source
            return redirect()->to('/checkin/success')->with([
                'checkin_data' => [
                    'first_name' => 'Jerome',
                    'organization' => 'SM Store',
                    'registration_code' => '0d84jr950',
                ]
            ]);

            // return null;
        }catch (Exception $e) {
            $this->error=$e->getMessage();
            $this->dispatch('open-modal', id: 'error-modal');
            return null;
        }
    }

    public function closeModal()
    {
        $this->data =[];
    }

    public function render(): View
    {
        return view('livewire.checkin.create-checkin');
    }
}
