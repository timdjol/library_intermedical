<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('title')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $documents = $category->documents()->isActive()->orderBy('title')->get();

        return view('agent.categories.show', compact('category', 'documents'));
    }
}
