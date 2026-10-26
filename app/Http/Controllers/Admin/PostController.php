<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('admin.posts.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $request['code'] = Str::slug($request->title);
        $params = $request->all();
        unset($params['image']);
        if($request->has('image')){
            $path = $request->file('image')->store('posts');
            $params['image'] = $path;
        }
        $post = Post::create($params);
        $post->categories()->attach($request->categories_id);

        unset($params['title_event']);
        $title_event = 'Добавлена книга ' . $post->title;
        $params['title_event'] = $title_event;
        Event::create($params);

        session()->flash('success', 'Книга ' . $request->title . ' добавлена');
        return redirect()->route('admin-posts.index');
    }

    public function show(Post $admin_post)
    {
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Просмотрена книга ' . $admin_post->title,
            ]
        );
        return view('admin.posts.show', compact('admin_post'));
    }

    public function edit(Post $admin_post)
    {
        $categories = Category::get();
        return view('admin.posts.form', compact('admin_post', 'categories'));
    }

    public function update(PostUpdateRequest $request, Post $admin_post)
    {
        $request['code'] = Str::slug($request->title);
        $params = $request->all();

        unset($params['image']);
        if($request->has('image')){
            if($admin_post->image != null) {
                Storage::delete($admin_post->image);
            }
            $path = $request->file('image')->store('posts');
            $params['image'] = $path;
        }

        unset($params['title_event']);
        $title_event = 'Изменена книга ' . $params["old_title"] . ' на ' . $request->title;
        $params['title_event'] = $title_event;
        $admin_post->update($params);
        $admin_post->categories()->sync($request->categories_id);
        Event::create($params);

        session()->flash('success', 'Книга ' . $admin_post->title . ' обновлена');
        return redirect()->route('admin-posts.index');
    }


    public function destroy(Post $admin_post)
    {
        $admin_post->delete();
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Удалена книга ' . $admin_post->title,
            ]
        );
        session()->flash('success', 'Книга ' . $admin_post->title . ' удалена');
        return redirect()->route('admin-posts.index');
    }

}
