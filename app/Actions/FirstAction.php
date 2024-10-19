<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Contact;

class FirstAction
{
    use AsAction;

    public function handle(string $mobile)
    {
        $contact = Contact::create(['mobile' => $mobile]);
        dd($contact);
    }

    public function asController(ActionRequest $request, string $mobile): \Illuminate\Http\Response
    {
        $this->handle($mobile);
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'nullable'],
        ];
    }
}
