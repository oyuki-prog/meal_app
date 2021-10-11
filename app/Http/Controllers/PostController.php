<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $posts = Post::with('user')->with('likes')->latest()->Paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = new Post($request->all());
        $post->user_id = $request->user()->id;

        $file = $request->file('image');
        $post->image = self::createFileName($file);

        DB::beginTransaction();
        try {
            $post->save();
            if (!Storage::putFileAs('/public/images/posts', $file, $post->image)) {
                throw new \Exception('画像ファイルの保存に失敗しました。');
            }
            $request->session()->regenerateToken();
            //成功したらコミット
            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            //失敗したらロールバック
            DB::rollBack();

            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('notice', '記事を登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load('user')->load('likes');
        $like = null;
        foreach ($post->likes as $postLike) {
            if ($postLike->user_id == Auth::id()) {
                $like = Like::find($postLike->id);
                break;
            }
        }

        return view('posts.show', compact('post', 'like'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return redirect()
                ->route('posts.show', $post)
                ->withErrors('自分の記事以外は更新できません');
        }

        $file = $request->file('image');
        if (!empty($file)) {
            $delete_file_path = $post->image_path;
            $post->image = self::createFileName($file);
        }
        $post->fill($request->all());

        DB::beginTransaction();
        try {
            $post->save();
            if (!empty($file)) {
                if (!Storage::putFileAs('/public/images/posts/', $file, $post->image)) {
                    throw new \Exception('画像ファイルの保存に失敗しました。');
                }
                if (!Storage::delete($delete_file_path)) {
                    Storage::delete($post->image_path);
                    throw new \Exception('画像ファイルの削除に失敗しました。');
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect()->route('posts.show', $post)
            ->with('notice', '記事を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        DB::beginTransaction();
        try {
            $post->delete();

            if (!Storage::delete($post->image_path)) {
                throw new \Exception('画像ファイルの削除に失敗しました。');
            }

            DB::commit();
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('posts.index')
            ->with('notice', '記事を削除しました');
    }

    public static function createFileName($file)
    {
        return date('YmdHis') . '_' . $file->getClientOriginalName();
    }
}
