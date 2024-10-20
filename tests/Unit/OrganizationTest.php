<?php

use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Contact, Organization};

uses(RefreshDatabase::class, WithFaker::class);

test('organization has attributes', function () {
    $organization = Organization::factory()->create();
    expect($organization->id)->toBeUuid();
    expect($organization->code)->toBeString();
    expect($organization->name)->toBeString();
    expect($organization->meta)->toBeInstanceOf(SchemalessAttributes::class);
});

test('organization has default code', function () {
    $organization = Organization::factory()->create();
    $uuid = [];
    preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $organization->id, $uuid);
    $code = $uuid[4];
    expect($organization->code)->toBe($code);

});

test('organization has contacts', function () {
    [$contact1, $contact2] = Contact::factory(2)->forOrganization()->create();
    expect($contact1->organization->id)->toBe($contact2->organization->id);
    $organization = $contact1->organization;
    expect($organization->contacts)->toHaveCount(2);
});
