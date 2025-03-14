<?php

use App\Models\{Campaign, Checkin, Contact, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use App\Actions\GetReferenceCode;

uses(RefreshDatabase::class, WithFaker::class);

test('avail controller requires a checkin id', function () {
    $checkin = Checkin::factory()->forContact()->forProject()->create();
    $booking_server = $checkin->project->sales_unit->booking_server();
    $booking_server_url = 'https://' . $booking_server . '/api/create-reference';
    Http::fake([
        $booking_server_url => Http::response(['reference_code' => 'JN-123456'], 200)
    ]);

    $reference_code = app(GetReferenceCode::class)->run($checkin);
    expect($reference_code)->toBe('JN-123456');
});
