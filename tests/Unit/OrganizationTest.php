<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Contact, Organization};


uses(RefreshDatabase::class, WithFaker::class);

test('organization has attributes', function () {
    $organization = Organization::factory()->create();
    expect($organization->id)->toBeUuid();
    expect($organization->name)->toBeString();
});

test('organization has contacts', function () {
    [$contact1, $contact2] = Contact::factory(2)->forOrganization()->create();
    expect($contact1->organization->id)->toBe($contact2->organization->id);
    $organization = $contact1->organization;
    expect($organization->contacts)->toHaveCount(2);
});
