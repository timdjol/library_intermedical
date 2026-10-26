<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
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
        $posts = auth()->user()->posts()->isActive()->orderBy('created_at', 'DESC')->paginate(20);
        return view('agent.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('agent.posts.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $request['code'] = Str::slug($request->title);
        $params = $request->all();
        $post = Post::create($params);

        unset($params['title_event']);
        $title_event = 'Добавлена книга ' . $post->title;
        $params['title_event'] = $title_event;
        Event::create($params);
        $post->categories()->attach($request->categories_id);

        session()->flash('success', 'Книга ' . $request->title . ' добавлена');
        return redirect()->route('agent-posts.index');
    }

    public function show(Post $agent_post)
    {
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Просмотрена книга ' . $agent_post->title,
            ]
        );
        return view('agent.posts.show', compact('agent_post'));
    }

    public function edit(Post $agent_post)
    {
        $categories = Category::get();
        return view('agent.posts.form', compact('agent_post', 'categories'));
    }

    public function update(PostUpdateRequest $request, Post $agent_post)
    {
        $request['code'] = Str::slug($request->title);
        $params = $request->all();

        unset($params['image']);
        if($request->has('image')){
            if($agent_post->image != null) {
                Storage::delete($agent_post->image);
            }
            $path = $request->file('image')->store('posts');
            $params['image'] = $path;
        }


        unset($params['title_event']);
        $title_event = 'Изменена книга ' . $params["old_title"] . ' на ' . $request->title;
        $params['title_event'] = $title_event;
        $agent_post->update($params);
        $agent_post->categories()->sync($request->categories_id);
        Event::create($params);

        session()->flash('success', 'Книга ' . $agent_post->title . ' обновлена');
        return redirect()->route('agent-posts.index');
    }


    public function destroy(Post $agent_post)
    {
        $agent_post->delete();
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Удалена книга ' . $agent_post->title,
            ]
        );
        session()->flash('success', 'Книга ' . $agent_post->title . ' удалена');
        return redirect()->route('agent-posts.index');
    }

}
