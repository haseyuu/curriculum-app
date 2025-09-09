<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post;
use App\Image;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * 投稿を保存する
     */
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'comment'    => 'nullable|string|max:500|required_without:images',
            'images'     => 'nullable|array|max:4|required_without:comment',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'reserve'    => 'nullable|date',
            'visibility' => 'required|in:0,1,2',
            'reply_id'   => 'nullable|exists:posts,id',
        ]);

        // 投稿の作成
        $post = Post::create([
            'user_id'    => auth()->id(),
            'comment'    => $validated['comment'],
            'reserve'    => $validated['reserve'] ?? null,
            'visibility' => $validated['visibility'],
            'reply_id'   => $validated['reply_id'] ?? null,
        ]);

        // 画像保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('posts', 'public'); // storage/app/public/posts に保存
                Image::create([
                    'post_id' => $post->id,
                    'image'   => $path,
                ]);
            }
        }

        return response()->json([
            'message' => '投稿が作成されました',
            'post' => $post->load('images'),
        ], 201);
    }
}