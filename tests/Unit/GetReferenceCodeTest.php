<?php

use App\Models\{Campaign, Checkin, Contact, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Actions\GetReferenceCode;

uses(RefreshDatabase::class, WithFaker::class);

test('avail controller requires a checkin id', function () {
    $checkin = Checkin::factory()->forContact()->forProject()->create();
    $reference_code = app(GetReferenceCode::class)->run($checkin);

    dd($reference_code);
});
