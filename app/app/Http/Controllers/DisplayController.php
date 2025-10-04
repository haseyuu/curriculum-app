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

    public function back(Request $request){
        $data = $request->session()->get('registration', []);
        $token = $request->session()->get('register_token.token');
        $email = $data['email'] ?? $request->session()->get('verified_email');

        return redirect()->to('/register/verify?token='.$token)
                        ->withInput($data);
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
        if($user->state<3){
            Auth::login($user);
            return redirect()->intended('/');
        }else if($user->id==1){
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
                ->paginate(5);
                // ->get();
        return view('main', compact('posts'));
    }

    public function page($user_id){
        $authuser = auth()->user();
        $user = User::where('user_id', $user_id)->firstOrFail();
        // dd($user);
        if(!$user) return response()->view('error', [], 404);

        // 投稿一覧
        $posts = $user->posts()->with('images', 'user')
                    ->visibleAll($authuser)              
                    ->latest()
                    ->paginate(5);

        // いいね一覧
        $likes = Post::whereIn('id', $user->favorites()->pluck('post_id'))
                    ->visibleAll($authuser)            
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
                        ->paginate(5)
                        ->appends(['search_word' => $word]);

            $posts = null;
        } else {
        // dd($word);
            $users = null;

            $posts = Post::with('user')
            ->whereHas('user', function($q) {
                $q->whereNull('deleted_at');
            })
            ->where(function($query) use ($authuser, $word) {
                $query->visibleAll($authuser)
                    ->where('comment', 'like', "%{$word}%");
            })
            ->latest()
            ->paginate(5)
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

        return view('search', compact('posts', 'users','word'));
    }

    public function admin_search(Request $request){
        $authuser = auth()->user();

        // 管理者チェック
        if (!$authuser || $authuser->id != 1) {
            return view('error');
        }

        $word = $request->input('search_word', '');
        // dd($word);
        $query = User::query()->withTrashed();
        $users = collect();

        if ($word === '') {
            // 空なら表示なし
        } elseif ($word === '0') {
            // 0なら全件表示
            $users = $query->get();
        } elseif (ctype_digit($word)) {
            // 数字のみならIDで完全一致検索
            $users = $query->where('id', $word)->get();
        } elseif (str_starts_with($word, '@')) {
            // @で始まる場合はuser_idで部分一致
            $keyword = ltrim($word, '@');
            $users = $query->where('user_id', 'like', "%{$keyword}%")->get();
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $word)) {
            // yyyy-mm-dd形式ならdeleted_atでそれ以前を検索
            $users = $query->whereDate('deleted_at', '<=', $word)->get();
        }

        // Blade に常に $users を渡す
        return view('admin', compact('users', 'word'));
    }

    public function follows_view($user_id){
        $user = User::where('user_id', $user_id)->firstOrFail();
        $name = $user->name;

        $users = $user->follows()->paginate(5);
        return view('user.follows',compact('users','name'));
    }

    public function followers_view($user_id){
        $user = User::where('user_id', $user_id)->firstOrFail();
        $name = $user->name;

        $users = $user->followers()->paginate(5);
        return view('user.follows',compact('users','name'));
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

        $user = User::withTrashed()->where('email', $googleUser->getEmail())->first();

        if ($user) {
            // ソフトデリートされていたら復元
            if ($user->trashed()) {
                $user->restore();
            }
        } else {
            // 見つからなければ新規作成
            $user = User::create([
                'email'    => $googleUser->getEmail(),
                'name'     => 'Googleユーザー',
                'password' => bcrypt(Str::random(16)),
                'user_id'  => Str::random(25),
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }
}

