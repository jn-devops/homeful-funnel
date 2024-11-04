<?php

use App\Http\Controllers\CheckinController;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::resource('contacts', \App\Http\Controllers\ContactController::class);

Route::get('checkin/{campaign}',App\Livewire\Checkin\CreateCheckin::class, )->name('Checkin');
Route::get('checkin-success', App\Livewire\Checkin\SuccessPage::class )->name('checkin.success_page');

Route::get('schedule-trip/',App\Livewire\Trips\CreateTrip::class, )->name('schedule_trip');
Route::get('schedule-trip-success', App\Livewire\Trips\SuccessPage::class )->name('schedule.success_page');

//Route::get('checkin-success',function (Request $request){
//    dd($request->all());
//});

Route::get('avail/{checkin}', \App\Http\Controllers\AvailController::class)->name('avail');
