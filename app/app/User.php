<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Follow;
use App\Favorites;
use App\Posts;

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
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follow_id');
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'follows', 'follow_id', 'user_id');
    }

    public function posts(){
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function favorites(){
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id');
    }

    public function mutualFollowUserIds(){
        $followingIds = $this->follows()->pluck('follow_id')->toArray();
        $followerIds  = $this->followers()->pluck('follows.user_id')->toArray();

        return array_intersect($followingIds, $followerIds);
    }

}
