<?php

use App\Http\Controllers\PublishController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/subscribe/{topic}', [SubscriberController::class, 'createSubscription'])->name('subscribe.create');
Route::post('/publish/{topic}', [PublishController::class, 'publishMessage'])->name('publish.message');
