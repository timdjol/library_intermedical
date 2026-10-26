<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Support\StoredFile;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()->latest()->paginate(20);

        return view('agent.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::orderBy('title')->get();

        return view('agent.posts.form', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        $post = Post::createFromRequest($request);
        Event::record('Добавлена книга '.$post->title);
        session()->flash('success', 'Книга '.$post->title.' добавлена');

        return redirect()->route('agent-posts.index');
    }

    public function show(Post $agent_post)
    {
        $this->authorizeOwner($agent_post);
        $agent_post->load('categories');
        Event::record('Просмотрена книга '.$agent_post->title);

        return view('agent.posts.show', compact('agent_post'));
    }

    public function edit(Post $agent_post)
    {
        $this->authorizeOwner($agent_post);
        $categories = Category::orderBy('title')->get();
        $agent_post->load('categories');

        return view('agent.posts.form', compact('agent_post', 'categories'));
    }

    public function update(PostUpdateRequest $request, Post $agent_post)
    {
        $this->authorizeOwner($agent_post);
        $previousTitle = $agent_post->title;
        $agent_post->updateFromRequest($request);
        Event::record('Изменена книга '.$previousTitle.' на '.$agent_post->title);
        session()->flash('success', 'Книга '.$agent_post->title.' обновлена');

        return redirect()->route('agent-posts.index');
    }

    public function destroy(Post $agent_post)
    {
        $this->authorizeOwner($agent_post);
        StoredFile::delete($agent_post->image);
        $title = $agent_post->title;
        $agent_post->delete();
        Event::record('Удалена книга '.$title);
        session()->flash('success', 'Книга '.$title.' удалена');

        return redirect()->route('agent-posts.index');
    }

    private function authorizeOwner(Post $post): void
    {
        abort_unless($post->user_id === Auth::id(), 403);
    }
}
