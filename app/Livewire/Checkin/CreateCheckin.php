<?php

namespace App\Livewire\Checkin;

use App\Models\Campaign;
use App\Models\Checkin;
use App\Models\Contact;
use App\Models\Organization;
use Exception;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Routing\Redirector;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class CreateCheckin extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public Campaign $campaign;
    public Organization $organization;
    public String $error = '';

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
                    Forms\Components\TextInput::make('mobile')
                        ->label('Mobile Number: ')
                        ->unique(table: Contact::class,column: 'mobile')
                        ->required()
                        ->prefix('+63')
                        ->regex("/^[0-9]+$/")
                        ->minLength(10)
                        ->maxLength(10)
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                            $livewire->validateOnly($component->getStatePath());
                        })
                        ->inlineLabel(),
                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name: ')
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel(),
                    Forms\Components\TextInput::make('middle_name')
                        ->label('Middle Name: ')
                        ->maxLength(255)
                        ->inlineLabel(),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Last Name: ')
                        ->required()
                        ->maxLength(255)
                        ->inlineLabel(),
                    Forms\Components\ToggleButtons::make('ready_to_avail')
                        ->label('Ready to avail: ')
                        ->inline(true)
                        ->inlineLabel(true)
                        ->required()
                        ->boolean()
                        ->columnSpanFull(),

                ]),
            ])
            ->statePath('data')
            ->model(Checkin::class);
    }

    public function save():Redirector|null
    {
        $data = $this->form->getState();
        try {
            $contact =Contact::create([
                'mobile' => $data['mobile'],
                'name' => $data['first_name'].' '.$data['middle_name'].' '.$data['last_name'],
            ]);
            Checkin::create([
                'campaign_id' => $this->campaign->id,
                'organization_id' => $this->organization->id,
                'meta' => $data,
                'contact_id' => $contact->id,
            ]);

            $this->dispatch('open-modal', id: 'success-modal');
            if ($data['ready_to_avail']) {
                $params = [
                    'last_name' => $data['last_name'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'],
                    'mobile' => $data['mobile'],
                ];
                return redirect()->to('http://homeful-lazarus.test/client-information?' . http_build_query($params));
            }
            return null;
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
