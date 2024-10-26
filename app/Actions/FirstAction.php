<?php

namespace App\Actions;

use http\Client\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use App\Models\Contact;

class FirstAction
{
    use AsAction;

    public function handle(string $mobile)
    {
        $contact = Contact::updateOrCreate(['mobile' => $mobile]);
        return $contact;
    }

    public function asController(ActionRequest $request, string $mobile): \Illuminate\Http\Response
    {
        return $this->handle($mobile);
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'nullable'],
        ];
    }
}
