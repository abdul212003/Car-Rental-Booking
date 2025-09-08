<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AddCars;
use App\Livewire\Admin\ManageBooking;
use App\Livewire\Admin\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/dashboard',Dashboard::class)->name('admin.dashboard');
});

Route::get('/addcars',AddCars::class)->name('admin.add-cars');
Route::get('/manageBooking',ManageBooking::class)->name('admin.manage-booking');