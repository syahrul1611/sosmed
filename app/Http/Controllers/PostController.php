<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function like(Request $request)
    {
        $post_id = Post::where('slug',$request['slug'])->first()->id;
        $user_id = Auth::user()->id;
        if (Like::where('post_id',$post_id)->exists()) {
            if (Like::where('post_id',$post_id)->where('user_id',$user_id)->exists()) {
                Like::where('post_id',$post_id)->where('user_id',$user_id)->delete();
                $data = [
                    'count' => Like::where('post_id',$post_id)->count(),
                    'like' => null
                ];
                return $data;
            }
            Like::create([
                'user_id' => $user_id,
                'post_id' => $post_id
            ]);
            $data = [
                'count' => Like::where('post_id',$post_id)->count(),
                'like' => true
            ];
            return $data;
        }
        Like::create([
            'user_id' => $user_id,
            'post_id' => $post_id
        ]);
        $data = [
            'count' => Like::where('post_id',$post_id)->count(),
            'like' => true
        ];
        return $data;
    }

    public function comment(Request $request)
    {
        $post_id = Post::where('slug',$request['slug'])->first()->id;
        $user_id = Auth::user()->id;
        $comment = $request->validate([
            'comment' => 'required'
        ]);
        $comment['user_id'] = $user_id;
        $comment['post_id'] = $post_id;
        Comment::create($comment);
        $time = \Carbon\Carbon::now()->diffForHumans();
        $data = ['username' => Auth::user()->username, 'name' => Auth::user()->name, 'image' => Auth::user()->image, 'comment' => $comment['comment'], 'time' => $time];
        return $data;
    }

    public function store(Request $request)
    {
        function generateRandomString($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }

        $validateData = $request->validate([
            'image' => 'required|image|file|max:2048',
            'caption' => 'required|max:1000',
        ]);

        $validateData['user_id'] = Auth::user()->id;
        $validateData['image'] = $request->file('image')->store('img-post');
        $validateData['slug'] = generateRandomString();

        Post::create($validateData);

        return redirect('/')->with('uploaded', 'Postingan berhasil diunggah');
    }

    public function delete(Request $request)
    {
        $post = Post::where('slug',$request->slug)->first();
        if ($post->user_id == Auth::user()->id) {
            $post->delete();
        }
        return redirect('/dashboard-'.Auth::user()->username)->with('deleted', 'Postingan berhasil dihapus');
    }

    public function edit(Request $request)
    {
        $post = Post::where('slug',$request->slug)->first();
        if ($post->user_id == Auth::user()->id) {
            $rules = [
                'image' => 'image|file|max:2048',
                'caption' => 'required|max:1000',
            ];
            $validateData = $request->validate($rules);
            if($request->file('image')) {
                if($request->oldImage) {
                    Storage::delete($request->oldImage);
                }
                $validateData['image'] = $request->file('image')->store('img-post');
            }
            $post->update($validateData);
        }
        return redirect('/dashboard-'.Auth::user()->username)->with('postEdit', 'Postingan berhasil diubah');
    }
}
