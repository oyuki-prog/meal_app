<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request, Post $post, Like $like)
    {
        if ($request->user()->cannot('store', $like)) {
            return redirect()
                ->route('posts.show', $post);
        }
        $like = new Like();
        $like->post_id = $post->id;
        $like->user_id = Auth::id();
        try {
            $post->likes()->save($like);
            $request->session()->regenerateToken();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $post);
    }

    public function destroy(Request $request, Post $post, Like $like)
    {
        // if ($request->user()->cannot('destroy', $like)) {
        //     return redirect()
        //         ->route('posts.show', $post);
        // }

        try {
            if (!empty($like)) {
                $like->delete();
                $request->session()->regenerateToken();
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('posts.show', $post)->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $post);
    }
}
