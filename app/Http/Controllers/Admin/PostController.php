<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Support\StoredFile;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::orderBy('title')->get();

        return view('admin.posts.form', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        $post = Post::createFromRequest($request);
        Event::record('Добавлена книга '.$post->title);
        session()->flash('success', 'Книга '.$post->title.' добавлена');

        return redirect()->route('admin-posts.index');
    }

    public function show(Post $admin_post)
    {
        $admin_post->load('categories');
        Event::record('Просмотрена книга '.$admin_post->title);

        return view('admin.posts.show', compact('admin_post'));
    }

    public function edit(Post $admin_post)
    {
        $categories = Category::orderBy('title')->get();
        $admin_post->load('categories');

        return view('admin.posts.form', compact('admin_post', 'categories'));
    }

    public function update(PostUpdateRequest $request, Post $admin_post)
    {
        $previousTitle = $admin_post->title;
        $admin_post->updateFromRequest($request);
        Event::record('Изменена книга '.$previousTitle.' на '.$admin_post->title);
        session()->flash('success', 'Книга '.$admin_post->title.' обновлена');

        return redirect()->route('admin-posts.index');
    }

    public function destroy(Post $admin_post)
    {
        StoredFile::delete($admin_post->image);
        $title = $admin_post->title;
        $admin_post->delete();
        Event::record('Удалена книга '.$title);
        session()->flash('success', 'Книга '.$title.' удалена');

        return redirect()->route('admin-posts.index');
    }
}
