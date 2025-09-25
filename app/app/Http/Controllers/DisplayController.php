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
use Laravel\Socialite\Facades\Socialite;

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

    public function change_email_form(Request $request){
        $token = $request->query('token');
        $mode = 'change_conf';
        $headtxt = 'メールアドレス変更用認証';

        return view('email.email_form', compact('mode', 'headtxt','token'));
    }

    public function login(Request $request){
        $request->validate([
            'user_info' => 'required|string',
            'password'  => 'required|string',
        ]);

        // ユーザーをメールアドレスかuser_idで検索
        $user = User::withTrashed()
                    ->where('email', $request->user_info)
                    ->orWhere('user_id', $request->user_info)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'user_info' => 'メールアドレス/ユーザーID またはパスワードが正しくありません。',
            ]);
        }
        if ($user->trashed()) {
            $user->restore();
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

    public function search_page(){
        $posts = null;
        return view('search', compact('posts'));
    }

    public function search(Request $request){
        $word = $request->input('search_word','');
        
        $authuser = auth()->user();

        if (!$word) {
            $posts = null;
            $users = null;
            return view('search', compact('posts', 'users'));
        }

        if (strpos($word, '@') === 0) {
            $keyword = ltrim($word, '@');

            $users = User::where('user_id', 'like', "%{$keyword}%")
                        ->paginate(1)
                        ->appends(['search_word' => $word]);

            $posts = null;
        } else {
            $users = null;

            $posts = Post::with('user')
                ->whereHas('user', function($q) {
                    $q->whereNull('deleted_at');
                })
                ->visibleAll($authuser)
                ->where('comment', 'like', "%{$word}%")
                ->latest()
                ->paginate(1)
                ->appends(['search_word' => $word]);
        }
        if ($request->ajax()) {
            if ($posts) {
                return view('layouts.posts', compact('posts'))->render();
            }
            if ($users) {
                return view('layouts.users', compact('users'))->render();
            }
        }

        return view('search', compact('posts', 'users'));
    }

    public function follows_view($user_id){
        $users = User::where('user_id',$user_id)->first()->follows;
        return view('user.follows',compact('users'));
    }

    public function followers_view($user_id){
        $users = User::where('user_id',$user_id)->first()->followers;
        return view('user.follows',compact('users'));
    }

    public function profile_edit_view($user_id){
        if(auth()->user()->user_id!==$user_id)return view('error');
        $user = User::where('user_id',$user_id)->first();
        return view('user.profile_edit',compact('user'));
    }

    public function redirect_to_Google(){
        return Socialite::driver('google')->redirect();
    }

    public function handle_Google_callback(){
        $googleUser = Socialite::driver('google')->user();

        // ユーザーが存在しなければ作成
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            ['name' => 'googleユーザー',
            'password' => bcrypt(Str::random(16)),
            'user_id' => Str::random(25)]
        );

        Auth::login($user);

        return redirect('/');
    }
}

