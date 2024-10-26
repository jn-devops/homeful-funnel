<?php

use App\Http\Controllers\CheckinController;
use App\Models\Checkin;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::resource('contacts', \App\Http\Controllers\ContactController::class);

Route::get('checkin/{campaign}/{organization}',App\Livewire\Checkin\CreateCheckin::class, )->name('Checkin');
Route::get('checkin/success', App\Livewire\Checkin\SuccessPage::class )->name('success_page');

