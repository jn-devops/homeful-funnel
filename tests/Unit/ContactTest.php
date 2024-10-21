<?php

use App\States\{FirstState, SecondState, ThirdState, FourthState};
use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\ContactPersistedFromRouteBinding;
use App\Models\{Checkin, Contact, Organization};
use Illuminate\Foundation\Testing\WithFaker;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Event;
use App\States\ContactState;

uses(RefreshDatabase::class, WithFaker::class);

test('contact has attributes', function () {
    $contact = Contact::factory()->create();
    expect($contact->id)->toBeUuid();
    expect($contact->mobile)->toBeString();
    expect($contact->mobile_country)->toBeString();
    expect($contact->state)->toBeInstanceOf(ContactState::class);
    expect($contact->organization)->toBeNull();
    expect($contact->campaign)->toBeNull();
    expect($contact->meta)->toBeInstanceOf(SchemalessAttributes::class);
    expect($contact->first_name)->toBeString();
    expect($contact->middle_name)->toBeString();
    expect($contact->last_name)->toBeString();
});

test('contact has default values', function () {
    $contact = Contact::factory()->create();
    expect($contact->mobile_country)->toBe('PH');
});

test('contact can be persisted from a mobile number', function () {
    $mobile = '9171234567';
    $contact = Contact::create(['mobile' => $mobile]);
    expect($contact)->toBeInstanceOf(Contact::class);
    expect((new PhoneNumber($mobile, 'PH'))->equals(new PhoneNumber($contact->mobile, 'PH')))->toBeTrue();
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

test('contact has checkins', function () {
    [$checkin1, $checkin2] = Checkin::factory(2)->forContact()->create();
    expect($checkin1->contact->id)->toBe($checkin2->contact->id);
    $contact = $checkin1->contact;
    expect($contact->checkins)->toHaveCount(2);
});

//test('contact has route model binding from existing record', function () {
//    Event::fake(ContactPersistedFromRouteBinding::class);
//    $mobile = '9171234567';
//    $contact = Contact::create(['mobile' => $mobile]);
//    $url = route('contacts.show', ['contact' => $contact->mobile]);
//    $response = $this->get($url);
//    expect($response->status())->toBe(200);
//    expect((new PhoneNumber($response->json('mobile'), 'PH'))->equals(new PhoneNumber($mobile, 'PH')))->toBeTrue();
//    Event::assertNothingDispatched(ContactPersistedFromRouteBinding::class);
//});

//test('contact has route model binding from non-existing record', function () {
//    Event::fake(ContactPersistedFromRouteBinding::class);
//    $mobile = '9171234567';
//    $url = route('contacts.show', ['contact' => $mobile]);
//    $response = $this->get($url);
//    expect($response->status())->toBe(200);
//    expect((new PhoneNumber($response->json('mobile'), 'PH'))->equals(new PhoneNumber($mobile, 'PH')))->toBeTrue();
//    $contact = Contact::fromMobile($mobile);
//    expect($contact)->toBeInstanceOf(Contact::class);
//    Event::assertDispatched(ContactPersistedFromRouteBinding::class);
//});

//test('contact not persisted if mobile is not right', function () {
//    Event::fake(ContactPersistedFromRouteBinding::class);
//    $mobile = '34567';
//    $url = route('contacts.show', ['contact' => $mobile]);
//    $response = $this->get($url);
//    expect($response->status())->toBe(404);
//    Event::assertNotDispatched(ContactPersistedFromRouteBinding::class);
//})->expect(NumberParseException::class);
