<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 相互フォローしているユーザーの ID 配列を返す
    public function mutualFollowUserIds()
    {
        // 自分がフォローしているユーザー
        $following = \DB::table('follows')
                    ->where('user_id', $this->id)
                    ->pluck('follow_id');

        // 自分をフォローしているユーザー
        $followers = \DB::table('follows')
                    ->where('follow_id', $this->id)
                    ->pluck('user_id');

        // 相互フォローしているユーザーIDだけ返す
        return $following->intersect($followers)->toArray();
    }

}
