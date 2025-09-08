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
Route::get('admin', function () {
    return view('admin');
    //return view('welcome');
})->name('admin');
Route::get('/pass_reset', function () {
    return view('email_form');
})->name('pass_reset');
Route::get('/email_change', function () {
    return view('email_form');
})->name('email_change');
Route::get('/follow', function () {
    return view('user.follows');
})->name('follow');
Route::get('/user_page', function () {
    return view('user.user_page');
})->name('user_page');
Route::get('/reset_form', function () {
    return view('reset_form');
})->name('reset_form');
Route::post('/reset_comp', function () {
    return view('reset_complete');
})->name('reset_comp');
Route::get('/posting', function () {
    return view('post_form');
})->name('posting');
Route::get('/search', function () {
    return view('search');
})->name('search');
Route::get('/profile_edit', function () {
    return view('profile_edit');
})->name('profile_edit');

Route::get('/login', function () {
    return view('user.login');
})->name('login');
Route::post('/login',[DisplayController::class, 'login'])->name('login');

//認証用メールアドレス入力画面
Route::get('/passreset/email', [DisplayController::class, 'reset_email_form'])->name('resetEmail');
Route::get('/register/email', [DisplayController::class, 'regist_email_form'])->name('registEmail');

//認証用メール送信
Route::post('/register/email', [RegistrationController::class, 'send_regist_email'])->name('regist_conf');
Route::post('/passreset/email', [RegistrationController::class, 'send_reset_email'])->name('reset_conf');

//新規登録用メール内リンク
Route::get('/register/verify', [DisplayController::class, 'regist_form'])->name('register');
//パスワード再設定用メール内リンク
Route::get('/password/reset', [DisplayController::class, 'reset_form'])->name('reset');

//登録内容確認/新規登録
Route::post('/register/confirm', [registrationController::class, 'confirm'])->name('regist_confirm');
Route::post('/register/store', [registrationController::class, 'create_user'])->name('regist_complete');

//パスワード再設定
Route::post('/password/reset', [registrationController::class, 'pass_reset'])->name('reset_comp');

//送信完了画面
Route::get('/email/complete', function () {
    return view('email.email_conf');
})->name('email_conf');
//期限切れリンク
Route::get('/email/invalid', function () {
    return view('email.invalid');
})->name('invalid');