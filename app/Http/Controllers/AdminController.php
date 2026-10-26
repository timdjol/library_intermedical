<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $posts = Post::all();
        $categories = Category::all();
        $users = User::all();
        $events = Event::all();
        return view('admin.dashboard', compact('users', 'posts', 'categories', 'events'));
    }
}
