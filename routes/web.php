<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('group-conversation', [App\Http\Controllers\MessageController::class, 'groupConversation'])
    ->name('message.group-conversation');

    Route::get('conversation/{userId}', [App\Http\Controllers\MessageController::class, 'conversation'])
    ->name('message.conversation');

    Route::post('conversation-messages/', [App\Http\Controllers\MessageController::class, 'getMessagesConversation'])
    ->name('message.conversation-messages');


    Route::post('send-message', [App\Http\Controllers\MessageController::class, 'sendMessage'])
    ->name('message.send-message');

});