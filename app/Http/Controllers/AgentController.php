<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

class AgentController extends Controller
{
    public function console()
    {
        return view('agent.console', [
            'postsCount' => Post::count(),
            'categoriesCount' => Category::count(),
        ]);
    }
}
