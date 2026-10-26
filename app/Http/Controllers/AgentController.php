<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

class AgentController extends Controller
{
    public function console()
    {
        $posts = Post::all();
        $categories = Category::all();
        return view('agent.console', compact('posts', 'categories'));
    }
}
