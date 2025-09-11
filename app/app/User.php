<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Follow;

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

    public function follows(){
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function followers(){
        return $this->hasMany(Follow::class, 'follow_id');
    }

    public function posts(){
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function likes(){
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    public function mutualFollowUserIds(){
        $followingIds = $this->follows()->pluck('follow_id')->toArray();
        $followerIds  = $this->followers()->pluck('user_id')->toArray();

        return array_intersect($followingIds, $followerIds);
    }

}
