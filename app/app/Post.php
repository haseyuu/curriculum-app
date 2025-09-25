<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'comment',
        'reserve',
        'visibility',
        'reply_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault()
        ->whereNull('deleted_at');
    }

    public function replyTo()
    {
        return $this->belongsTo(Post::class, 'reply_id');
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'reply_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'post_id', 'user_id');
    }
    
    // 自分が見れる投稿を取得するスコープ
    public function scopeVisibleTo($query, $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('visibility', 0)
              ->whereIn('user_id', $user->follows()->pluck('follow_id'))
              ->orWhere(function($q2) use ($user) {
                  // 相互フォローのみ
                  $q2->where('visibility', 1)
                     ->whereIn('user_id', $user->mutualFollowUserIds());
              })
              ->orWhere(function($q3) use ($user) {
                  // 自分の投稿
                  $q3->where('user_id', $user->id);
              });
        });
    }

    //検索時の取得範囲
    public function scopeVisibleAll($query, $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('visibility', 0)
              ->orWhere(function($q2) use ($user) {
                  // 相互フォローのみ
                  $q2->where('visibility', 1)
                     ->whereIn('user_id', $user->mutualFollowUserIds());
              })
              ->orWhere(function($q3) use ($user) {
                  // 自分の投稿
                  $q3->where('user_id', $user->id);
              });
        });
    }
}
