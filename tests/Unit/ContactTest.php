<?php

use App\States\{FirstState, SecondState, ThirdState, FourthState};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\States\ContactState;
use App\Models\Contact;


uses(RefreshDatabase::class, WithFaker::class);

test('contact has attributes', function () {
    $contact = Contact::factory()->create();
    expect($contact->id)->toBeUuid();
    expect($contact->mobile)->toBeString();
    expect($contact->state)->toBeInstanceOf(ContactState::class);
});

test('contact has states', function () {
    $contact = Contact::factory()->create();
    expect($contact->state)->toBeInstanceOf(FirstState::class);
    $contact->state->transitionTo(SecondState::class);
    expect($contact->state)->toBeInstanceOf(SecondState::class);
    $contact->state->transitionTo(ThirdState::class);
    expect($contact->state)->toBeInstanceOf(ThirdState::class);
    $contact->state->transitionTo(FourthState::class);
    expect($contact->state)->toBeInstanceOf(FourthState::class);
});
