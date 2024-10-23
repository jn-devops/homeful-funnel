<?php

use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Campaign, CampaignType, Checkin, Project};
use App\Models\Contact;

uses(RefreshDatabase::class, WithFaker::class);

test('campaign has attributes', function () {
    $campaign = Campaign::factory()->create();
    expect($campaign->id)->toBeUuid();
    expect($campaign->name)->toBeString();
    expect($campaign->meta)->toBeInstanceOf(SchemalessAttributes::class);
    expect($campaign->rider_url)->toBeString();
});

test('campaign has checkins', function () {
    [$checkin1, $checkin2] = Checkin::factory(2)->forCampaign()->create();
    expect($checkin1->campaign->id)->toBe($checkin2->campaign->id);
    $campaign = $checkin1->campaign;
    expect($campaign->checkins)->toHaveCount(2);
    $campaign->checkins()->create();
    $campaign->refresh();
    expect($campaign->checkins)->toHaveCount(3);
    $checkin = new Checkin;
    $checkin->contact()->associate($contact = Contact::factory()->create());
    $checkin->save();
    $campaign->checkins()->save($checkin);
    $campaign->refresh();
    expect($campaign->checkins)->toHaveCount(4);
    expect($campaign->checkins()->where('id', $checkin->id)->first()->contact->is($contact))->toBeTrue();
});

test('campaign has projects', function () {
    $campaign = Campaign::factory()->create();
    $project = Project::factory()->create();
    $campaign->project()->associate($project);
    expect($campaign->project->is($project))->toBeTrue();
});

test('campaign has campaign types', function () {
    $campaign = Campaign::factory()->create();
    $campaignType = CampaignType::factory()->create();
    $campaign->campaignType()->associate($campaignType);
    expect($campaign->campaignType()->is($campaignType))->toBeTrue();
});
