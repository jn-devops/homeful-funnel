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
    $name = $this->faker->name();
    $organization = Organization::factory()->create();
    $url = route('checkin-contact', ['campaign' => $campaign->id, 'contact' => $mobile]);
    $response = $this->post($url, [
        'name' => $name,
        'code' => $organization->code
    ]);
    $checkin_id = $response->json('id');
    $checkin = Checkin::find($checkin_id);
    $contact = Contact::fromMobile($mobile);
    expect($checkin)->toBeInstanceOf(Checkin::class);
    expect($checkin->campaign->is($campaign))->toBeTrue();
    expect($contact->name)->toBe($name);
    expect($checkin->contact->is($contact))->toBeTrue();
    expect($checkin->contact->organization->is($organization))->toBeTrue();
});


