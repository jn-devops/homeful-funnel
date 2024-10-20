<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::resource('contacts', \App\Http\Controllers\ContactController::class);

Route::get('checkin/{campaign}/{organization}',App\Livewire\Checkin\CreateCheckin::class, )->name('Checkin');
