<?php

use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\CampaignType;

uses(RefreshDatabase::class, WithFaker::class);

test('campaign type has attributes', function () {
    $campaign_type = CampaignType::factory()->create();
    expect($campaign_type->id)->toBeUuid();
    expect($campaign_type->name)->toBeString();
    expect($campaign_type->meta)->toBeInstanceOf(SchemalessAttributes::class);
});
