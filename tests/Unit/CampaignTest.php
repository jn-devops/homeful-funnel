<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Campaign, Checkin, Contact};


uses(RefreshDatabase::class, WithFaker::class);

test('campaign has attributes', function () {
    $campaign = Campaign::factory()->create();
    expect($campaign->id)->toBeUuid();
    expect($campaign->name)->toBeString();
});

test('campaign has checkins', function () {
    [$checkin1, $checkin2] = Checkin::factory(2)->forCampaign()->create();
    expect($checkin1->campaign->id)->toBe($checkin2->campaign->id);
    $campaign = $checkin1->campaign;
    expect($campaign->checkins)->toHaveCount(2);
});
