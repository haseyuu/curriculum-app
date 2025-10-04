<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Post;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //投稿を保存する
    public function store(Request $request){
        // バリデーション
        $validated = $request->validate([
            'comment'    => 'nullable|string|max:500|required_without:images',
            'images'     => 'nullable|array|max:4|required_without:comment',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'reserve'    => 'nullable|date',
            'visibility' => 'required|in:0,1,2',
        ]);
        // 投稿の作成
        $post = Post::create([
            'user_id'    => auth()->id(),
            'comment'    => $validated['comment'],
            'reserve'    => $request->filled('reserve')
                        ? Carbon::createFromFormat('Y-m-d\TH:i', $request->reserve)
                        : null,
            'visibility' => $validated['visibility'],
        ]);

        // 画像保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('posts', 'public');
                Image::create([
                    'post_id' => $post->id,
                    'image'   => $path,
                ]);
            }
        }

        session()->forget('previousUrl');

        return redirect($request->input('previousUrl', '/'))
       ->with('success', '投稿を作成しました');
    }

    public function edit(Post $post,Request $request){
        // 自分の投稿か確認
        $this->authorize('update', $post);
        if (!session()->has('previousUrl')) {
            session(['previousUrl' => url()->previous()]);
        }

        return view('post_form', [
            'post'=>$post,
            'previousUrl' => session('previousUrl')
        ]);
    }
    
    public function create(Request $request){
        if (!session()->has('previousUrl')) {
            session(['previousUrl' => url()->previous()]);
        }

        return view('post_form', [
            'previousUrl' => session('previousUrl')
        ]);
    }

    public function update(Request $request, Post $post){
        $this->authorize('update', $post);

        $hasExistingImages = $post->images()
            ->whereNotIn('id', $request->input('deleted_images', []))
            ->exists();

        $rules = [
            'comment'    => 'nullable|string|max:500',
            'images'     => 'nullable|array|max:4',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'reserve'    => 'nullable|date',
            'visibility' => 'required|in:0,1,2',
        ];

        if (!$hasExistingImages) {
            $rules['comment'] .= '|required_without:images';
            $rules['images']  .= '|required_without:comment';
        }

        $validated = $request->validate($rules);

        $post->update([
            'comment' => $request->comment,
            'visibility' => $request->visibility,
            'reserve'    => $request->filled('reserve')
                        ? Carbon::createFromFormat('Y-m-d\TH:i', $request->reserve)
                        : null,
        ]);

        // 削除フラグのついた既存画像を消す
        if ($request->has('deleted_images')) {
            foreach ($request->deleted_images as $imageId) {
                $image = $post->images()->find($imageId);
                if ($image) {
                    Storage::delete('public/' . $image->image);
                    $image->delete();
                }
            }
        }

        // 新規画像を保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('posts', 'public');
                $post->images()->create(['image' => $path]);
            }
        }

        session()->forget('previousUrl');

        return redirect($request->input('previousUrl', '/'))
       ->with('success', '投稿を作成しました');
    }

    public function delete(Post $post){
        // 投稿の所有者かチェック
        if (auth()->id() !== $post->user_id) {
            abort(403, '権限がありません');
        }

        foreach ($post->images as $image) {
            Storage::delete('public/' . $image->image);
            $image->delete();
        }

        $post->delete();

        // Ajax なら JSON を返す
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', '投稿を削除しました');
    }

    public function destroy(Post $post){
        // 投稿の所有者かチェック
        if (auth()->id() !== $post->user_id) {
            abort(403, '権限がありません');
        }

        foreach ($post->images as $image) {
            Storage::delete('public/' . $image->image);
            $image->delete();
        }

        $post->delete();

        // Ajax なら JSON を返す
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', '投稿を削除しました');
    }
}