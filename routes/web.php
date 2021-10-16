<?php

use App\Http\Controllers\TokenOneTimeController;
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
    return view('welcome');
});

Route::get('token', [TokenOneTimeController::class, "index"])->name('token');

Route::group(['middleware' => 'one.time.token'], function() {
    Route::get('test', [TokenOneTimeController::class, 'test'])->name('test');
});