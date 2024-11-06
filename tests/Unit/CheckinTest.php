<?php

use App\Models\{Campaign, Checkin, Contact, Link, Project};
use Spatie\SchemalessAttributes\SchemalessAttributes;
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

test('checkin has one link', function (){
    $checkin = Checkin::factory()->create();
    expect($checkin->link)->toBeNull();
    $link_attributes = Link::factory()->definition();
    $checkin->link()->create($link_attributes);
    $checkin->save();
    $checkin->refresh();
    expect($checkin->link)->toBeInstanceOf(Link::class);
    expect($checkin->link->only(['original_url', 'short_url']))->toBe($link_attributes);
});

test('checkin can get or generation avail url', function () {
    $checkin = Checkin::factory()->forContact()->forProject()->create();
    expect(Link::all())->toHaveCount(0);
    $url1 = $checkin->getOrGenerateAvailUrl();
    expect(Link::all())->toHaveCount(1);
    $checkin->refresh();
    $url2 = $checkin->getOrGenerateAvailUrl();
    expect(Link::all())->toHaveCount(1);
    $checkin->refresh();
    expect($url2)->toBe($url1);
});
