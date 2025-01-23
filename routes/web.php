<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Spatie\Activitylog\Models\Activity;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('register');
    });

    Volt::route('/register', 'form-reg-affiliate')->name('register');

    Volt::route('/after-reg', 'after-reg-page')->name('after-reg');

    Volt::route('/login', 'login')->name('login');
});

// Route::get('/coba', function (){
//     return response()->json(Activity::with('causer')->get()->last());
// });

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');

    //profile
    Volt::route('/profile', 'cms.profile')->name('profile');

    //dashboard
    Volt::route('/dashboard', 'cms.dashboard')->can('dashboard-page')->middleware('can:dashboard-page')->name('dashboard');

    //users
    Volt::route('/users', 'cms.users.index')->middleware('can:user-page')->name('users');

    //affiliates
    Volt::route('/affiliates', 'cms.affiliates.index')->middleware('can:affiliate-page')->name('affiliates');

    //banks
    Volt::route('/banks', 'cms.banks.index')->middleware('can:bank-page')->name('banks');

    //logs activity
    Volt::route('/logs', 'cms.logs')->middleware('can:log-page')->name('logs');
});
