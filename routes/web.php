<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subscribe/{user}', [SubscriptionController::class, 'create']);
Route::get('/subscriptions/{user}', [SubscriptionController::class, 'list']);   

Route::get('/feature-check/{user}', [SubscriptionController::class, 'checkFeature']);

Route::get('/cancel/{user}', [SubscriptionController::class, 'cancel']);