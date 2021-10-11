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
        $like = new Like($request->all());

        try {
            $like->save();
            $request->session()->regenerateToken();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $request->post_id);
    }

    public function destroy(Request $request, Post $post, Like $like)
    {
        if ($request->user()->cannot('destroy', $like)) {
            return redirect()
                ->route('posts.show', $like);
        }

        try {
            Like::where('user_id', Auth::id())
                ->where('post_id', $request->post_id)
                ->delete();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $request->post_id);
    }
}
