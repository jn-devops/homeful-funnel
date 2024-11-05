<?php

use App\Models\{Campaign, Checkin, Contact, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use App\Actions\GetReferenceCode;

uses(RefreshDatabase::class, WithFaker::class);

test('avail controller requires a checkin id', function () {
    Http::fake([
        'https://elanvital-booking.homeful.ph/api/create-reference' => Http::response(['reference_code' => 'JN-123456'], 200)
    ]);
    $checkin = Checkin::factory()->forContact()->forProject()->create();
    $reference_code = app(GetReferenceCode::class)->run($checkin);
    expect($reference_code)->toBe('JN-123456');
});
