<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\VideoController;

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

Route::get('/', [HomeController::class, 'index'])
//    ->where(['category' => '[a-z]+'])
    ->name('home');

Route::get('/video/show/{id}', [VideoController::class, 'show'])
    ->where(['id' => '[0-9]+'])
    ->name('video_show');
