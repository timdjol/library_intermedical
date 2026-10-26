<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Event;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('title')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('title')->get();

        return view('admin.categories.form', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $title = $request->string('title')->toString();
        $category = Category::create([
            'title' => $title,
            'code' => Category::uniqueCode($title),
            'parent_id' => (int) $request->input('parent_id', 0),
        ]);

        Event::record('Добавлена категория '.$category->title);
        session()->flash('success', 'Добавлена категория '.$category->title);

        return redirect()->route('admin-categories.index');
    }

    public function edit(Category $admin_category)
    {
        $categories = Category::orderBy('title')->get();

        return view('admin.categories.form', compact('admin_category', 'categories'));
    }

    public function update(CategoryRequest $request, Category $admin_category)
    {
        $previousTitle = $admin_category->title;
        $title = $request->string('title')->toString();
        $admin_category->update([
            'title' => $title,
            'code' => Category::uniqueCode($title, $admin_category->id),
            'parent_id' => (int) $request->input('parent_id', 0),
        ]);

        Event::record('Изменена категория '.$previousTitle.' на '.$title);
        session()->flash('success', 'Категория '.$title.' обновлена');

        return redirect()->route('admin-categories.index');
    }

    public function destroy(Category $admin_category)
    {
        $title = $admin_category->title;
        $admin_category->delete();
        Event::record('Удалена категория '.$title);
        session()->flash('success', 'Категория '.$title.' удалена');

        return redirect()->route('admin-categories.index');
    }
}
