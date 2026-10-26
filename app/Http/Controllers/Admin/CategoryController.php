<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('children')->get();
        return view('admin.categories.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $request['code'] = Str::slug($request->title);
        $params = $request->all();
        $category = Category::create($params);

        unset($params['title_event']);
        $title_event = 'Добавлена категория ' . $category->title;
        $params['title_event'] = $title_event;
        Event::create($params);

        session()->flash('success', 'Добавлена категория ' . $request->title);

        return redirect()->route('admin-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $admin_category)
    {
        $categories = Category::all();
        return view('admin.categories.form', compact('admin_category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $admin_category)
    {
        $params = $request->all();

        unset($params['title_event']);
        $title_event = 'Изменена категория ' . $params["old_title"] . ' на ' . $request->title;
        $params['title_event'] = $title_event;
        $admin_category->update($params);
        Event::create($params);

        session()->flash('success', 'Категория ' . $request->title . ' обновлена');
        return redirect()->route('admin-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $admin_category)
    {
        $admin_category->delete();
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Удалена категория ' . $admin_category->title,
            ]
        );
        session()->flash('success', 'Категория ' . $admin_category->title . ' удалена');
        return redirect()->route('admin-categories.index');
    }
}
