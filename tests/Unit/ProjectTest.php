<?php

use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Project;

uses(RefreshDatabase::class, WithFaker::class);

test('project has attributes', function () {
    $project = Project::factory()->create();
    expect($project->id)->toBeUuid();
    expect($project->name)->toBeString();
    expect($project->default_product)->toBeString();
    expect($project->minimum_salary)->toBeFloat();
    expect($project->default_price)->toBeFloat();
    expect($project->default_percent_down_payment)->toBeFloat();
    expect($project->default_percent_miscellaneous_fees)->toBeFloat();
    expect($project->default_down_payment_term)->toBeInt();
    expect($project->default_balance_payment_term)->toBeInt();
    expect($project->default_balance_payment_interest_rate)->toBeFloat();
    expect($project->default_seller_commission_code)->toBeString();
    expect($project->kwyc_check_campaign_code)->toBeString();
    expect($project->meta)->toBeInstanceOf(SchemalessAttributes::class);
    $url = $this->faker->url();
    $project->rider_url = $url;
    $project->save();
    expect($project->rider_url)->toBe($url);
//    dd('create route for avail, generate reference from project defaults as inputs, then redirec to kwyc-check');
});
