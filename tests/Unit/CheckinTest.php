<?php

use Spatie\SchemalessAttributes\SchemalessAttributes;
use App\Models\{Campaign, Checkin, Contact, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class, WithFaker::class);

test('checkin has attributes', function () {
    $checkin = Checkin::factory()->create();
    expect($checkin->id)->toBeUuid();
    expect($checkin->meta)->toBeInstanceOf(SchemalessAttributes::class);
});

test('checkin has campaign', function () {
    $checkin = Checkin::factory()->forCampaign()->create();
    expect($checkin->campaign)->toBeInstanceOf(Campaign::class);
});

test('checkin has contact', function () {
    $checkin = Checkin::factory()->forContact()->create();
    expect($checkin->contact)->toBeInstanceOf(Contact::class);
});

test('checkin has project', function () {
    $checkin = Checkin::factory()->forProject()->create();
    expect($checkin->project)->toBeInstanceOf(Project::class);
    $checkin = Checkin::factory()->create();
    $project = Project::factory()->create();
    $checkin->project()->associate($project);
    $checkin->save();
    expect($checkin->project->is($project))->toBeTrue();
});

test('checkin has registration code', function () {
    $checkin = Checkin::factory()->create();

    $campaign_codes = [];
    $code = $checkin->id;
    preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $code, $campaign_codes);

    expect($checkin->registration_code)->toBe(substr($campaign_codes[Checkin::REG_CODE_UUID_GROUP_INDEX], Checkin::REG_CODE_SUBSTRING_COUNT));
});
