<?php

use App\Models\{Campaign, Checkin, Contact, Organization};
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class, WithFaker::class);

test('checkin has attributes', function () {
    $checkin = Checkin::factory()->create();
    expect($checkin->id)->toBeUuid();
    expect($checkin->meta)->toBeInstanceOf(SchemalessAttributes::class);
});

test('checkin contact has end point', function () {
    $campaign = Campaign::factory()->create();
    $mobile = '09171234567';
    $first_name = $this->faker->firstName();
    $middle_name = $this->faker->lastName();
    $last_name = $this->faker->lastName();
    $name = $this->faker->name();
    $organization = Organization::factory()->create();
    $url = route('checkin-contact', ['campaign' => $campaign->id, 'contact' => $mobile]);
    $response = $this->post($url, [
        'name' => $name,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'code' => $organization->code
    ]);
    $checkin_id = $response->json('id');
    $checkin = Checkin::find($checkin_id);
    $contact = Contact::fromMobile($mobile);
    expect($checkin)->toBeInstanceOf(Checkin::class);
    expect($checkin->campaign->is($campaign))->toBeTrue();
//    expect($contact->name)->toBe($name);
    expect($contact->name)->toBe($first_name . ' ' . $last_name);
    expect($contact->first_name)->toBe($first_name);
    expect($contact->middle_name)->toBe($middle_name);
    expect($contact->last_name)->toBe($last_name);
    expect($checkin->contact->is($contact))->toBeTrue();
    expect($checkin->contact->organization->is($organization))->toBeTrue();
})->skip();


