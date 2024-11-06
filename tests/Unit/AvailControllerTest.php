<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use App\Models\{Checkin, Link};

uses(RefreshDatabase::class, WithFaker::class);

test('avail controller requires a checkin id', function () {
    Http::fake([
        'https://elanvital-booking.homeful.ph/api/create-reference' => Http::response(['reference_code' => 'JN-123456'], 200)
    ]);
    expect(Link::all())->toHaveCount(0);
    $checkin = Checkin::factory()->forContact()->forProject()->create();
    $avail_url = route('avail', ['checkin' => $checkin]);
    $response = $this->get($avail_url);
    $response = $this->get($avail_url);
    expect(Link::all())->toHaveCount(1);
    expect($response->status())->toBe(302);
});
