<?php

use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Project;
use App\Models\Contact;

uses(RefreshDatabase::class, WithFaker::class);

test('project has attributes', function () {
    $project = Project::factory()->create();
    expect($project->id)->toBeUuid();
    expect($project->name)->toBeString();
    expect($project->meta)->toBeInstanceOf(SchemalessAttributes::class);
});
