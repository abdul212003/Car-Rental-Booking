<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Livewire\Admin\AddCars;
use App\Livewire\Admin\ManageBooking;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ManageCustomerFeedback;
use App\Livewire\Admin\ManageContactUs;
use App\Livewire\Admin\ManagePayment;
use App\Livewire\CarSearch;
use App\Livewire\Admin\IncomeChart;
use App\Livewire\LandingPage;
use App\Livewire\User\Profile;
use App\Livewire\User\Payment;

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');
Route::get('/', LandingPage::class)->name('welcome');

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

Route::get('/logout', function()
{
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::get('/incomechart', IncomeChart::class)->name('admin.income-chart');
Route::get('/user/profile', Profile::class)->name('user.profile');
Route::get('/user/payment', Payment::class)->name('user.payment');
Route::get('/managepayment',ManagePayment::class)->name('admin.manage-payment');
Route::get('/managecontact',ManageContactUs::class)->name('admin.manage-contact-us');
Route::get('/managefeedback', ManageCustomerFeedback::class)->name('admin.manage-customer-feedback');
Route::get('/searchcar',CarSearch::class)->name('car-search');
Route::get('/addcars',AddCars::class)->name('admin.add-cars');
Route::get('/manageBooking',ManageBooking::class)->name('admin.manage-booking.index');
Route::get('/booking/print/{id}',[BookingController::class, 'printBooking'])->name('print.booking-print');