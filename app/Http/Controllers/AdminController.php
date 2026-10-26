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
        return view('admin.dashboard', [
            'postsCount' => Post::count(),
            'categoriesCount' => Category::count(),
            'usersCount' => User::count(),
            'eventsCount' => Event::count(),
        ]);
    }
}
