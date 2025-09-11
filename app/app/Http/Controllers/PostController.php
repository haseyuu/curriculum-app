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
    public function store(Request $request){
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

    public function edit(Post $post){
        // 自分の投稿か確認
        $this->authorize('update', $post);

        return view('post_form', ['post' => $post]);
    }

    public function update(Request $request, Post $post){
        $this->authorize('update', $post);

        $request->validate([
            'comment' => 'required|string|max:200',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'visibility' => 'required|in:0,1,2',
            'existing_images' => 'array', // 既存画像保持用
            'existing_images.*' => 'integer|exists:images,id'
        ]);

        $post->update([
            'comment' => $request->comment,
            'visibility' => $request->visibility,
        ]);

        // --- 既存画像の削除 ---
        $existingIds = $request->input('existing_images', []); // フォームで残った画像ID
        $post->images()->whereNotIn('id', $existingIds)->each(function($img){
            // ストレージから削除
            \Storage::delete('public/'.$img->image);
            $img->delete();
        });

        // --- 新規画像の保存 ---
        if($request->hasFile('images')){
            foreach($request->file('images') as $file){
                $path = $file->store('public/posts');
                $post->images()->create([
                    'image' => str_replace('public/', '', $path)
                ]);
            }
        }

        return redirect()->route('users.page', $post->user->user_id)
                        ->with('success', '投稿を更新しました');
    }

    public function delete(Post $post){
        // 投稿の所有者かチェック
        if (auth()->id() !== $post->user_id) {
            abort(403, '権限がありません');
        }

        // 画像削除（必要なら）
        foreach ($post->images as $image) {
            Storage::delete('public/' . $image->image);
        }

        $post->delete();

        // Ajax なら JSON を返す
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', '投稿を削除しました');
    }
}