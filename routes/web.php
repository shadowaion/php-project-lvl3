<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

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
    return view('main');
})->name('mainPage');

Route::get('/testRedirect', function () {
    return Redirect()->route('urls.index');
})->name('redirectTest');

Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');

Route::post('/urls', [UrlController::class, 'store'])->name('urls.store');

Route::get('/urls/{id}', [UrlController::class, 'show'])->name('urls.show');