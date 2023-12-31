<?php

use App\Http\Controllers\EmiDetailsController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin', function () {
    return view('admin');
});
Route::get('/emiDetails', [EmiDetailsController::class, 'index'])->name('emiDetails');
Route::get('/processEmiData', [EmiDetailsController::class, 'processEmiData'])->name('processEmiData');

