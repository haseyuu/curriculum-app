<?php

use App\Http\Controllers\DisplayController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PostController;

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

Route::get('admin', function () {
    return view('admin');
    //return view('welcome');
})->name('admin');




Route::middleware('auth')->group(function () {
    //投稿/編集画面
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'delete'])->name('posts.delete');
    Route::get('posting', function () {
        return view('post_form');
    })->name('posting');

    //フォロー/アンフォロー
    Route::post('/user/{id}', [RegistrationController::class, 'follow'])->name('user.follow');
    Route::delete('/user/{id}', [RegistrationController::class, 'unfollow'])->name('user.unfollow');

    //いいね/いいね解除
    Route::post('/favo/{id}', [RegistrationController::class, 'favo'])->name('post.favo');
    Route::delete('/favo/{id}', [RegistrationController::class, 'unfavo'])->name('post.unfavo');

    //プロフィール編集
    Route::get('/profile/{user_id}', [DisplayController::class, 'profile_edit_view'])->name('profile_edit');
    Route::put('/profile/{user_id}', [RegistrationController::class, 'profile_update'])->name('profile_edit');
    
    //メールアドレス変更
    Route::get('/change/email', [RegistrationController::class, 'send_change_email'])->name('chngeEmail');
    
    //ユーザー削除
    Route::delete('/delete/{user_id}', [RegistrationController::class, 'user_delete'])->name('user_delete');

    Route::get('/users/delete', function () {
        return view('user.user_delete');
    })->name('user_delete');

});

Route::get('/login', function () {
    return view('user.login');
})->name('login');
Route::post('/login',[DisplayController::class, 'login'])->name('login');

Route::get('/',[DisplayController::class, 'index'])->name('/');

//認証用メールアドレス入力画面
Route::get('/passreset/email', [DisplayController::class, 'reset_email_form'])->name('resetEmail');
Route::get('/register/email', [DisplayController::class, 'regist_email_form'])->name('registEmail');

//認証用メール送信
Route::post('/register/email', [RegistrationController::class, 'send_regist_email'])->name('regist_conf');
Route::post('/passreset/email', [RegistrationController::class, 'send_reset_email'])->name('reset_conf');
Route::post('/change/email', [RegistrationController::class, 'send_email'])->name('change_conf');

//新規登録用メール内リンク
Route::get('/register/verify', [DisplayController::class, 'regist_form'])->name('register');
//パスワード再設定用メール内リンク
Route::get('/password/reset', [DisplayController::class, 'reset_form'])->name('reset');
//メールアドレス変更用メール内リンク
Route::get('/email/reset', [DisplayController::class, 'change_email_form'])->name('change_form');
//メールアドレス変更用メール内リンク
Route::get('/change/verify', [RegistrationController::class, 'change_email'])->name('change');

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

//フォロー/フォロワー一覧
Route::get('/follows/{user_id}',[DisplayController::class, 'follows_view'])->name('follows');
Route::get('/followers/{user_id}',[DisplayController::class, 'followers_view'])->name('followers');

//検索
// Route::get('/search',[DisplayController::class, 'search_page'])->name('view_search');
Route::get('/search',[DisplayController::class, 'search'])->name('search');

//ユーザーページ
Route::get('/users/{user_id}', [DisplayController::class, 'page'])->name('users.page');

//Ajax 用
Route::get('/users/{user_id}/posts', [DisplayController::class, 'getPosts'])
    ->name('users.posts');
Route::get('/users/{user_id}/likes', [DisplayController::class, 'getLikes'])
    ->name('users.likes');