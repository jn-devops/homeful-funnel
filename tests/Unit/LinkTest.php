<?php

use App\Models\{Campaign, Checkin, Contact, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Link;

uses(RefreshDatabase::class, WithFaker::class);

test('link shortens', function () {
    $originalUrl = $this->faker->url();
    $link = Link::shortenUrl($originalUrl);
    expect(strlen($link->short_url))->toBeLessThan(strlen($originalUrl));
});
