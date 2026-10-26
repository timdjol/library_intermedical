<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::isActive()->where('user_id', '!=', Auth::id())->orderBy('created_at', 'DESC')->paginate(20);
        return view('agent.articles.index', compact('posts'));
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
        return view('agent.articles.show', compact('agent_post'));
    }
}
