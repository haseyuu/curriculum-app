<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    // フォーム表示
    public function regist_form(Request $request)
    {
        $token = $request->query('token'); 

        // トークンからDBでメールアドレスを取得
        $email = DB::table('email_verifications')->where('token', $token)->value('email');

        $request->session()->put('verified_email', $email);
        
        return view('registration.regist_form', compact('email', 'token'));
    }

    public function regist_email_form()
    {
        $mode = 'regist_conf';
        $headtxt = '新規登録用メールアドレス認証';

        return view('email.email_form', compact('mode', 'headtxt'));
    }

    public function reset_email_form()
    {
        $mode = 'reset_conf';
        $headtxt = 'パスワード再設定用認証';

        return view('email.email_form', compact('mode', 'headtxt'));
    }
}
