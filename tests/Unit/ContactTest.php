<?php

use App\States\{FirstState, SecondState, ThirdState, FourthState};
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Campaign, Contact, Organization};
use Illuminate\Foundation\Testing\WithFaker;
use App\States\ContactState;

uses(RefreshDatabase::class, WithFaker::class);

test('contact has attributes', function () {
    $contact = Contact::factory()->create();
    expect($contact->id)->toBeUuid();
    expect($contact->mobile)->toBeString();
    expect($contact->state)->toBeInstanceOf(ContactState::class);
    expect($contact->organization)->toBeNull();
    expect($contact->campaign)->toBeNull();
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

test('contact has an organization', function () {
    $contact = Contact::factory()->forOrganization()->create();
    expect($contact->organization)->toBeInstanceOf(Organization::class);
});

test('contact has an campaign', function () {
    $contact = Contact::factory()->forCampaign()->create();
    expect($contact->campaign)->toBeInstanceOf(Campaign::class);
});

//it('returns a successful response', function () {
//    $contact = Contact::factory()->create();
//    $response = $this->post(route('first', ['user' => $contact->mobile]));
//
//    $response->assertStatus(200);
//});

//it('returns a successful response', function () {
//    $mobile = $this->faker->e164PhoneNumber();
//    $response = $this->post(route('first', ['mobile' => $mobile]));
//
//    $response->assertStatus(200);
//});
