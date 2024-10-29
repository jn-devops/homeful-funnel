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
    expect($project->meta)->toBeInstanceOf(SchemalessAttributes::class);
    $url = $this->faker->url();
    $project->rider_url = $url;
    $project->save();
    expect($project->rider_url)->toBe($url);
});
