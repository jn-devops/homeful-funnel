<?php

namespace App\Livewire\Checkin;

use App\Actions\CheckinContact;
use App\Models\Campaign;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\Project;
use App\Notifications\AcknowledgeAvailmentNotification;
use Exception;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class CreateCheckin extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];
    public Campaign $campaign;
    public ?Organization $organization;
    public String $error = '';
    public bool $isDifferentCompanyBefore=false;
    public bool $isOrganizationEmpty=true;
    public string $organization_default='';

    public function mount(Campaign $campaign ,Request $request): void
    {
        if (!empty($request->organization)){
            $this->isOrganizationEmpty=false;
            $this->organization=Organization::find($request->organization)??null;
            $this->organization_default=$this->organization->name;
        }
        $this->campaign=$campaign;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([


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
                                    $set('email',$contact->email??'');
                                    $set('organization',$contact->organization->name??'');

                                    if (!empty($contact->organization->id) && $contact->organization->id && !$this->isOrganizationEmpty) {
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
                        ->required(),
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
                    Forms\Components\Select::make('organization')
                        ->label('Organization')
                        ->required()
                        ->inlineLabel()
                        ->native(false)
                        ->searchable()
                        ->options($this->campaign->organizations()->pluck('name', 'name')->toArray() + ['other' => 'Others'])
                        ->preload()
                        ->live()
                        ->default($this->organization_default)
                        ->disabled(!$this->isOrganizationEmpty)
                        ->optionsLimit(Organization::count()+1)
                        ->placeholder('Select your organization')
                        ->dehydrated(fn($state)=>$state!=='other')
                        ->visible($this->organization_default!='Public'),
                    Forms\Components\TextInput::make('organization_other')
                        ->label('Other Organization: ')
                        ->visible(fn(Get $get):bool=>$get('organization')=='other')
                        ->requiredIf('organization','other')
                        ->maxLength(255)
                        ->inlineLabel(),

                    Forms\Components\Select::make('project')
                        ->label('Choice of Project')
                        ->required()
                        ->inlineLabel()
                        ->native(false)
                        ->options(Project::whereIn('id',$this->campaign->projects()->pluck('project_id'))->pluck('name', 'name')->toArray())
                        ->preload()
                        ->placeholder('Choose your preferred project')
                    // Forms\Components\ToggleButtons::make('ready_to_avail')
                    //     ->label('Ready to avail: ')
                    //     ->inline()
                    //     ->inlineLabel()
                    //     ->required()
                    //     ->boolean()
                    //     ->columnSpanFull(),

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
            if (array_key_exists('organization_other', $data)) {

                $this->organization=Organization::firstOrCreate(['name'=>$data['organization_other']]);
            }elseif ($this->organization_default=='Public'){
                $this->organization= Organization::where('name','Public')->first();
            }else{
                $this->organization= Organization::where('name',$data['organization'])->first();
            }
//            $this->organization = $data['organization']=='other'?
//                Organization::firstOrCreate(['name'=>$data['organization_other']]):
//                Organization::where('name',$data['organization'])->first();

//            $url = route('checkin-contact', ['campaign' => $this->campaign->id, 'contact' => $data['mobile']]);
            $checkin=$this->checkinContact();
//          $response =  Http::post($url, [
//                'name' => $data['first_name'].' '.$data['last_name'],
//                'code' => $this->organization->code,
//                'last_name' => $data['last_name'],
//                'first_name' => $data['first_name'],
//                // 'middle_name' => Arr::get($data, 'middle_name', 'N/A'),
//                'mobile' => $data['mobile'],
//                // 'ready_to_avail'=>$data['ready_to_avail'],
//                'project' => $data['project'],
//                'email' => $data['email'],
//                'meta'=>[
//                    'name'=> $data['first_name'].' '.$data['last_name'],
//                    'last_name' => $data['last_name'],
//                    'first_name' => $data['first_name'],
//                    // 'middle_name' => Arr::get($data, 'middle_name', 'N/A'),
//                    'mobile' => $data['mobile'],
//                    // 'ready_to_avail' =>$data['ready_to_avail'],
//                    'project' => $data['project'],
//                    'email' => $data['email'],
//                ]
//            ]);



            // return redirect()->to($this->campaign->rider_url ?? 'https://homeful.ph/');
            return redirect()->route('checkin.success_page',['checkin' => $checkin->id??'']);

            // return null;
        }catch (Exception $e) {
            $this->error=$e->getMessage();
            $this->dispatch('open-modal', id: 'error-modal');
            return null;
        }
    }

    public function checkinContact()
    {
        try {
            $campaign = Campaign::findOrFail($this->campaign->id);
            $mobile = '+63' . $this->data['mobile'];
            $firstName = $this->data['first_name'];
            $lastName = $this->data['last_name'];
            $email = $this->data['email'];
            $fullName = "$firstName $lastName";

            $contactByMobile = Contact::where('mobile', $mobile)->first();
            $contactByName = Contact::where('name', $fullName)->first();

            if ($contactByMobile && $contactByName && $contactByMobile->id !== $contactByName->id) {
                // Conflict: mobile and name belong to different people
                throw new \Exception('The provided name and mobile number belong to different contacts.');
            }

            if ($contactByMobile) {
                $contact = $contactByMobile;
            } elseif ($contactByName) {
                // Ensure mobile isn't used by another contact
                $conflict = Contact::where('mobile', $mobile)
                    ->where('id', '!=', $contactByName->id)
                    ->exists();

                if ($conflict) {
                    throw new \Exception('Mobile number already belongs to another contact.');
                }

                $contact = $contactByName;
                $contact->mobile = $mobile;
            } else {
                // Ensure no contact already uses this mobile
                $conflict = Contact::where('mobile', $mobile)->exists();
                if ($conflict) {
                    throw new \Exception('Mobile number already belongs to another contact.');
                }

                $contact = Contact::create([
                    'mobile' => $mobile,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'name' => $fullName,
                    'email' => $email,
                ]);
            }

            // Safe to update
            $contact->first_name = $firstName;
            $contact->last_name = $lastName;
            $contact->name = $fullName;
            $contact->email = $email;

            if ($this->organization) {
                $contact->organization()->associate($this->organization);
            }

            $contact->save();

            $checkin = new Checkin();
            $checkin->campaign()->associate($campaign);
            $checkin->contact()->associate($contact);

            if (!empty($this->data['project'])) {
                $project = Project::where('name', $this->data['project'])->firstOrFail();
                $checkin->project()->associate($project);
            }

            $checkin->save();
            $contact->notify(new AcknowledgeAvailmentNotification($checkin));

            return $checkin;
        } catch (\Exception $e) {
            report($e);
            throw $e;
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
