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

    protected $dates = [
        'reserve',
    ];

    protected static function booted()
    {
        static::deleting(function ($post) {
            foreach ($post->images as $image) {
                Storage::delete('public/' . $image->image);
                $image->delete();
            }
        });
    }

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
        $followIds = $user->follows()->pluck('follow_id');
        $mutualIds = $user->mutualFollowUserIds();

        return $query->where(function($q) use ($user, $followIds, $mutualIds) {

            // 自分の投稿は reserve に関係なく表示
            $q->where('user_id', $user->id);

            // フォロー中ユーザーの投稿
            $q->orWhere(function($q2) use ($followIds, $mutualIds) {
                $q2->whereIn('user_id', $followIds)
                ->where(function($q3) use ($mutualIds) {
                    $q3->where('visibility', 0) // 全体公開
                        ->orWhere(function($q4) use ($mutualIds) {
                            $q4->where('visibility', 1) // 相互フォロー
                                ->whereIn('user_id', $mutualIds);
                        });
                })
                ->where(function($q5) {
                    // reserve が null または過ぎた投稿のみ
                    $q5->whereNull('reserve')
                       ->orWhereRaw('reserve <= NOW()');
                });
            });
        });
    }

    //検索時の取得範囲
    public function scopeVisibleAll($query, $user)
    {
        $mutualIds = $user->mutualFollowUserIds();

        return $query->where(function($q) use ($user, $mutualIds) {

            // 投稿者本人の投稿はすべて表示
            $q->where('user_id', $user->id);

            // 相互フォローのみ投稿
            $q->orWhere(function($q2) use ($mutualIds) {
                $q2->where('visibility', 1)
                ->whereIn('user_id', $mutualIds);
            });

            // 全体公開の投稿（誰でも見れる）
            $q->orWhere('visibility', 0);

        })->where(function($q) {
            // reserve が null または過ぎた投稿のみ
            $q->whereNull('reserve')
            ->orWhereRaw('reserve <= NOW()');
        });
        // return $query->where(function($q) use ($user) {
        //     $q->where('visibility', 0)
        //       ->orWhere(function($q2) use ($user) {
        //           // 相互フォローのみ
        //           $q2->where('visibility', 1)
        //              ->whereIn('user_id', $user->mutualFollowUserIds());
        //       })
        //       ->orWhere(function($q3) use ($user) {
        //           // 自分の投稿
        //           $q3->where('user_id', $user->id);
        //       });
        // });
    }
}
