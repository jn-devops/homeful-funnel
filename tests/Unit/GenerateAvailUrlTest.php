<?php

use App\Actions\{GenerateAvailUrl, GetReferenceCode};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\{Checkin, Contact, Link};
use Crwlr\Url\Url;

uses(RefreshDatabase::class, WithFaker::class);

test('generate avail url works', function () {
    $checkin = Checkin::factory()->forProject()->for(Contact::factory()->state([
        'mobile' => '09171234567',
        'email' => 'john@doe.com'
    ]), 'contact')->create();

    GetReferenceCode::mock()->shouldReceive('run')->with($checkin)->andReturn('JN-123456');

    $action = app(GenerateAvailUrl::class);
    $url = $action->run($checkin);
    $link = substr($url, strrpos($url, '/' )+1);
    $original_url = Link::getOriginalUrl($link);
    $parsed_url = Url::parse(url: $original_url);
    $parsed_url->queryArray(query: [
        'mobile' => $checkin->contact->mobile,
        'email' => $checkin->contact->email,
        'identifier' => 'JN-123456',
    ]);
    expect($original_url)->toBe($parsed_url->toString());
});
