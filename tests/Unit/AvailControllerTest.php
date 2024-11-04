<?php

use App\Models\{Campaign, Checkin, Contact, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class, WithFaker::class);

test('avail controller requires a checkin id', function () {
    $checkin = Checkin::factory()->forContact()->forProject()->create();
    $rider_endpoint = route('avail', ['checkin' => $checkin]);
    $response = $this->get($rider_endpoint);
    expect($response->status())->toBe(302);
});
