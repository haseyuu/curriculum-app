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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    // 確認画面
    public function confirm(Request $request){
        $request->validate([
            'name'         => 'required|string|max:20',
            'user_id'           => 'nullable|string|max:20|unique:users,user_id',
            'password'          => 'required|string|min:8',
            'password_confirm'  => 'required|same:password',
        ]);

        $request->session()->put('registration', [
            'name' => $request->input('name'),
            'user_id'   => $request->input('user_id'),
            'password'  => $request->input('password'),
            'email' => $request->session()->get('verified_email'),
        ]);

        return view('registration.regist_confirm');
    }

    // 保存処理
    public function create_user(Request $request){
        $data = $request->session()->get('registration');
        
        // user_id が空ならランダムな25文字を生成
        $userId = !empty($data['user_id']) ? $data['user_id'] : Str::random(25);

        User::create([
            'name'     => $data['name'],
            'user_id'  => $userId,
            'password' => Hash::make($data['password']),
            'email'=>$data['email'],
        ]);

        $request->session()->forget('registration');

        return view('registration.regist_complete');
    }

    //新規登録確認メール送信
    public function send_regist_email(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users,email', // 登録済みメールは不可
        ]);

        $email = $request->input('email');

        // 認証用トークンを生成
        $token = bin2hex(random_bytes(32));

        $request->session()->put('register_token', [
            'email' => $email,
            'token' => $token,
        ]);

        DB::table('email_verifications')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // メール送信
        Mail::to($email)->send(new AuthMail(
            '新規登録確認メール',
            '以下のリンクをクリックして登録を完了してください。',
            url('/register/verify?token=' . $token),
            'アカウントを有効化する'
        ));

        return redirect()->route('email_conf')->with('email', $email);
    }

    //パスワード再設定メール送信
    public function send_reset_email(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email', // 登録済みメールである必要あり
        ]);

        $email = $request->input('email');

        // リセット用トークンを生成
        $token = bin2hex(random_bytes(32));

        $request->session()->put('reset_token', [
            'email' => $email,
            'token' => $token,
        ]);

        DB::table('email_verifications')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // メール送信
        Mail::to($email)->send(new AuthMail(
            'パスワード再設定メール',
            '以下のリンクをクリックしてパスワードを再設定してください。',
            url('/password/reset?token=' . $token),
            'パスワードをリセットする'
        ));

        return redirect()->route('email_conf')->with('email', $email);
    }

    //メールアドレス再設定用メール送信
    public function send_change_email(){

        $email = auth()->user()->email;
        $id = auth()->id();

        // リセット用トークンを生成
        $token = bin2hex(random_bytes(32));

        // $request->session()->put('reset_token', [
        //     'email' => $email,
        //     'token' => $token,
        // ]);

        DB::table('email_verifications')->insert([
            'email' => $email,
            'token' => $token,
            'user_id' => $id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // メール送信
        Mail::to($email)->send(new AuthMail(
            'メールアドレス変更用メール',
            '以下のリンクをクリックして新しいメールアドレスを入力してください。',
            url('/email/reset?token=' . $token),
            'メールアドレスを変更する'
        ));

        return redirect()->route('email_conf')->with('email', $email);
    }

    //変更メールアドレス認証用メール送信
    public function send_email(Request $request){

        $email = $request->input('email');
        $token = $request->input('token');

        $record = DB::table('email_verifications')->where('token', $token)->first();
        if (!$record) {
            return redirect()->route('invalid');
        }

        // リセット用トークンを生成
        $token = bin2hex(random_bytes(32));

        // $request->session()->put('reset_token', [
        //     'email' => $email,
        //     'token' => $token,
        // ]);

        DB::table('email_verifications')->insert([
            'email' => $email,
            'token' => $token,
            'user_id'=>$record->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // メール送信
        Mail::to($email)->send(new AuthMail(
            'メールアドレス変更用メール',
            '以下のリンクをクリックして新しいメールアドレスの認証をしてください。',
            url('/change/verify?token=' . $token),
            'メールアドレスを変更する'
        ));

        return redirect()->route('email_conf')->with('email', $email);
    }

    public function pass_reset(Request $request){
        // 入力バリデーション
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        // dd($request->password);
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $message = 'パスワード再設定完了しました。';

        return view('password.reset_complete',compact('message'));
    }

    public function change_email(Request $request){
        $token = $request->query('token');
        $record = DB::table('email_verifications')->where('token', $token)->first();

        if (!$record) {
            return redirect()->route('invalid');
        }

        // ユーザー更新
        User::where('id', $record->user_id)->update([
            'email' => $record->email,
        ]);

        // 使ったトークン削除
        DB::table('email_verifications')->where('token', $token)->delete();

        $message = 'メールアドレス変更完了しました。';

        return view('password.reset_complete',compact('message'));
    }

    public function follow($id){
        if(!auth()->user())return response()->json(['success' => false,'message'=>'ログインをしてください']);
        if(auth()->id() == $id || $id == 1){
            return response()->json([
                'success' => false,
                'message' => 'フォローできないユーザーです'
            ], 403);
        }
        if(auth()->user()->follows()->where('follow_id',$id)->exists()){
            return response()->json([
                'success' => false,
                'message' => 'すでにフォローしています'
            ]);
        }
        Follow::create([
            'user_id'=>auth()->id(),
            'follow_id'=>$id,
        ]);
        return response()->json(['success' => true]);
    }

    public function unfollow($id){
        if(!auth()->user())return response()->json(['success' => false,'message'=>'ログインをしてください']);
        if(auth()->id() == $id || $id == 1){
            return response()->json([
                'success' => false,
                'message' => 'フォローできないユーザーです'
            ], 403);
        }
        if(!auth()->user()->follows()->where('follow_id',$id)->exists()){
            return response()->json([
                'success' => false,
                'message' => 'まだフォローしていません'
            ]);
        }
        DB::table('follows')
        ->where('user_id', auth()->id())
        ->where('follow_id', $id)
        ->delete();
        return response()->json(['success' => true]);
    }

    public function favo($id){
        if(!auth()->user())return response()->json(['success' => false,'message'=>'ログインをしてください']);
        if(Post::where('id',$id)->value('visibility')===2){
            return response()->json([
                'success' => false,
                'message' => 'いいねできない投稿です'
            ], 403);
        }
        if(auth()->user()->favorites()->where('post_id',$id)->exists()){
            return response()->json([
                'success' => false,
                'message' => 'すでにいいねしています'
            ]);
        }
        Favorite::create([
            'user_id'=>auth()->id(),
            'post_id'=>$id,
        ]);
        $cnt = Favorite::where('post_id',$id)->count();
        return response()->json(['success' => true,'count'=>$cnt]);
    }
    
    public function unfavo($id){
        if(!auth()->user())return response()->json(['success' => false,'message'=>'ログインをしてください']);
        if(Post::where('id',$id)->value('visibility')===2){
            return response()->json([
                'success' => false,
                'message' => 'いいねできない投稿です'
            ], 403);
        }
        if(!auth()->user()->favorites()->where('post_id',$id)->exists()){
            return response()->json([
                'success' => false,
                'message' => 'いいねしていません'
            ]);
        }
        DB::table('favorites')
        ->where('user_id', auth()->id())
        ->where('post_id', $id)
        ->delete();
        $cnt = Favorite::where('post_id',$id)->count();
        return response()->json(['success' => true,'count'=>$cnt]);
    }

    public function profile_update(Request $request,$user_id){
        if(auth()->user()->user_id!==$user_id)return view('error');
    
        
        $request->validate([
            'name'         => 'required|string|max:20',
            'user_id'      => 'required|string|max:20|unique:users,user_id',
            'profile'      => 'nullable|string|max:200',
        ]);

        $user = auth()->user();
        
        $user->name = $request->input('name');
        $user->user_id = $request->input('user_id');
        $user->profile = $request->input('profile');

        if($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('avatars','public');
            $user->icon = $path;
        }

        $user->save();

        return redirect()->route('users.page', ['user_id' => $user_id]);

    }

    public function user_delete($user_id){
        $user = User::where('user_id',$user_id)->first();
        $user->delete();
        return view('user.login');
    }
}
