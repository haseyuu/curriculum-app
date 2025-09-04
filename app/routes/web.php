<?php

use App\Http\Controllers\DisplayController;
use App\Http\Controllers\RegistrationController;

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
    //return view('welcome');
})->name('/');
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::get('/pass_reset', function () {
    return view('pass_reset');
})->name('pass_reset');
Route::post('/reset_conf', function () {
    return view('reset_confirm');
})->name('reset_conf');
Route::get('/reset_form', function () {
    return view('reset_form');
})->name('reset_form');
Route::post('/reset_comp', function () {
    return view('reset_complete');
})->name('reset_comp');
Route::post('/reset_conf', function () {
    return view('reset_conf');
})->name('reset_conf');
Route::get('/register', function () {
    return view('regist');
})->name('register');
Route::post('/regist_confirm', function () {
    return view('regist_confirm');
})->name('regist_confirm');
Route::post('/regist_complete', function () {
    return view('regist_complete');
})->name('regist_complete');
