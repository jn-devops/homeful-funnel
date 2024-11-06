<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Campaign, Checkin, Link};

uses(RefreshDatabase::class, WithFaker::class);

test('link shortens', function () {
    $originalUrl = $this->faker->url();
    $link = Link::shortenUrl($originalUrl);
    expect(strlen($link->short_url))->toBeLessThan(strlen($originalUrl));
});

test('link has checkin', function () {
    $originalUrl = $this->faker->url();
    $link = Link::shortenUrl($originalUrl);
    $checkin = Checkin::factory()->forCampaign()->create();
    $link->checkin()->associate($checkin);
    $link->save();
    $link->refresh();
    expect($link->checkin->is($checkin))->toBeTrue();
});
