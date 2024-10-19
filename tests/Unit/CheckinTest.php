<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Campaign, Checkin, Contact};


uses(RefreshDatabase::class, WithFaker::class);

test('checkin has attributes', function () {
    $checkin = Checkin::factory()->create();
    expect($checkin->id)->toBeUuid();
});

test('checkin has campaign', function () {
    $checkin = Checkin::factory()->forCampaign()->create();
    expect($checkin->campaign)->toBeInstanceOf(Campaign::class);
});

test('checkin has contact', function () {
    $checkin = Checkin::factory()->forContact()->create();
    expect($checkin->contact)->toBeInstanceOf(Contact::class);
});
