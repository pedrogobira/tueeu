<?php

use App\Http\Controllers\CauseController;
use App\Http\Controllers\ChatRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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
    return redirect()->route('dashboard');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    /*
    Route::get('/dashboard', function () {
            return view('dashboard');
    })->name('dashboard');
    */

    Route::view('/profile', 'profile')->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('story', '\App\Http\Controllers\StoryController');
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    Route::post('/chat-request', [ChatRequestController::class, 'store'])->name('chat-request.store');
    Route::view('/chat', 'chat.index')->name('chat.index');
    Route::resource('cause', '\App\Http\Controllers\CauseController');
    Route::get('/cause/{id}/update', [CauseController::class, 'updateView'])->name('cause.updateView');
    Route::post('/cause/{id}/update', [CauseController::class, 'customUpdate'])->name('cause.custom-update');

    Route::get('/cause/{id}/post', [PostController::class, 'create'])->name('post.create');
    Route::post('/cause/{id}/post', [PostController::class, 'store'])->name('post.store');
    /*
        Route::get('/story', [StoryController::class, 'create'])->name('story.create');
        Route::post('/story', [StoryController::class, 'store'])->name('story.store');
        Route::post('/story', [StoryController::class, 'store'])->name('story.store');
    */
});

require __DIR__ . '/auth.php';
