<?php

use Illuminate\Support\Facades\Route;
use App\Actions\CheckinContact;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('checkin/{campaign}/{contact}', CheckinContact::class)
    ->name('checkin-contact');
