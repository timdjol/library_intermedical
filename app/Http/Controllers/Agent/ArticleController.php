<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $posts = Post::isActive()
            ->where('user_id', '!=', Auth::id())
            ->latest()
            ->paginate(20);

        return view('agent.articles.index', compact('posts'));
    }

    public function show(Post $agent_article)
    {
        abort_unless((int) $agent_article->status === 1, 404);

        $agent_article->load('categories');
        Event::record('Просмотрена книга '.$agent_article->title);

        return view('agent.articles.show', ['agent_post' => $agent_article]);
    }
}
