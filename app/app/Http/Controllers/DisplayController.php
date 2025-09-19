<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\PostTag;
use App\Tag;
use App\Image;
use App\Follow;
use App\Favorite;
use App\Mail\AuthMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DisplayController extends Controller
{
    public function regist_form(Request $request){
        $token = $request->query('token'); 

        // トークンからDBでメールアドレスを取得
        $email = DB::table('email_verifications')->where('token', $token)->value('email');

        if (!$email) {
            return redirect()->route('invalid')->withErrors('このリンクは無効または期限切れです。再度登録してください。');
        }

        $request->session()->put('verified_email', $email);
        
        return view('registration.regist_form', compact('email', 'token'));
    }

    public function reset_form(Request $request){
        $token = $request->query('token'); 

        // トークンからDBでメールアドレスを取得
        $email = DB::table('email_verifications')->where('token', $token)->value('email');

        if (!$email) {
            return redirect()->route('invalid')->withErrors('このリンクは無効または期限切れです。再度登録してください。');
        }

        $request->session()->put('verified_email', $email);
        
        return view('password.reset_form', compact('email', 'token'));
    }

    public function regist_email_form(){
        $mode = 'regist_conf';
        $headtxt = '新規登録用メールアドレス認証';

        return view('email.email_form', compact('mode', 'headtxt'));
    }

    public function reset_email_form(){
        $mode = 'reset_conf';
        $headtxt = 'パスワード再設定用認証';

        return view('email.email_form', compact('mode', 'headtxt'));
    }

    public function login(Request $request){
        $request->validate([
            'user_info' => 'required|string',
            'password'  => 'required|string',
        ]);

        // ユーザーをメールアドレスかuser_idで検索
        $user = User::where('email', $request->user_info)
                    ->orWhere('user_id', $request->user_info)
                    ->first();

        if (!$user && Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'user_info' => 'メールアドレス/ユーザーID またはパスワードが正しくありません。',
            ]);
        }
        if($user->state<2){
            Auth::login($user);
            return redirect()->intended('/');
        }else if($user->state==2){
            Auth::login($user);
            return redirect()->intended('admin');
        }
    }

    public function index(){
        $user = auth()->user();
        if(!$user){
            return redirect()->intended('login');
        }
        $posts = Post::with('user', 'images')
                ->visibleTo($user)
                ->latest()
                ->paginate(2);
                // ->get();
        return view('main', compact('posts'));
    }

    public function page($user_id){
        $user = User::where('user_id', $user_id)->firstOrFail();

        // 投稿一覧
        $posts = $user->posts()->with('images', 'user')->latest()->paginate(2);

        // いいね一覧
        $likes = Post::whereIn('id', $user->favorites()->pluck('post_id'))
                    ->with('images', 'user')
                    ->latest()
                    ->paginate(10);

        return view('user.user_page', compact('user', 'posts', 'likes'));
    }

}
