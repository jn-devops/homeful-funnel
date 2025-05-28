<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SocialMediaCampaign;
use App\Notifications\AcknowledgeAvailmentNotification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Livewire\Component;
use Filament\Forms;

class SocialMediaLinkRegistration extends Component implements HasForms
{
    public SocialMediaCampaign $campaign;
    use InteractsWithForms;

    public ?array $data = [];
    public string $error = '';
    public function mount(SocialMediaCampaign $campaign ,Request $request): void
    {
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
                    Forms\Components\TextInput::make('organization_other')
                        ->label('Other Organization: ')
                        ->visible(fn(Get $get):bool=>$get('organization')=='other')
                        ->requiredIf('organization','other')
                        ->maxLength(255)
                        ->inlineLabel(),

                ]),
            ])
            ->statePath('data')
            ->model(Checkin::class);
    }

    public function save():Redirector|null
    {
        $data = $this->form->getState();
        try {
            $checkin=$this->checkinContact();

            return redirect()->route('checkin.success_page',['checkin' => $checkin->id??'']);

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

            $contact->save();

            $checkin = new Checkin();
//            $checkin->campaign()->associate($campaign);
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

    public function render()
    {
        return view('livewire.social-media-link-registration');
    }
}
