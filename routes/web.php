<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    Mail::raw('Test email', function ($message) {
        $message->to('matuanahirina@gmail.com        ')
                ->subject('Test Email')
                ->from('glennleo242@gmail.com', 'Your App Name');
    });

    return 'Email sent';
});
