<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Campaign, Contact};


uses(RefreshDatabase::class, WithFaker::class);

test('campaign has attributes', function () {
    $campaign = Campaign::factory()->create();
    expect($campaign->id)->toBeUuid();
    expect($campaign->name)->toBeString();
});

test('campaign has contacts', function () {
    [$contact1, $contact2] = Contact::factory(2)->forCampaign()->create();
    expect($contact1->campaign->id)->toBe($contact2->campaign->id);
    $campaign = $contact1->campaign;
    expect($campaign->contacts)->toHaveCount(2);
});
