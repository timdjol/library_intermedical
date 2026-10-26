<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.documents.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentRequest $request)
    {
        $params = $request->all();
        if($request->has('path')){
            $path = $request->file('path')->store('documents');
            $params['path'] = $path;
        }
        $document = Document::create($params);
        $document->categories()->attach($request->categories_id);

        unset($params['title_event']);
        $title_event = 'Добавлен документ ' . $document->title;
        $params['title_event'] = $title_event;
        Event::create($params);

        session()->flash('success', 'Документ ' . $request->title . ' добавлен');
        return redirect()->route('admin-documents.index');
    }

    public function show(Document $admin_document)
    {
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Просмотрен документ ' . $admin_document->title,
            ]
        );
        return view('admin.documents.show', compact('admin_document'));
    }

    public function edit(Document $admin_document)
    {
        $categories = Category::all();
        return view('admin.documents.form', compact('admin_document', 'categories'));
    }

    public function update(DocumentUpdateRequest $request, Document $admin_document)
    {
        $params = $request->all();
        unset($params['path']);
        if($request->has('path')){
            if($admin_document->path != null) {
                Storage::delete($admin_document->path);
            }
            $path = $request->file('path')->store('documents');
            $params['path'] = $path;
        }

        unset($params['title_event']);
        $title_event = 'Изменен документ ' . $params["old_title"] . ' на ' . $request->title;
        $params['title_event'] = $title_event;
        $admin_document->update($params);
        $admin_document->categories()->sync($request->categories_id);
        Event::create($params);

        session()->flash('success', 'Документ ' . $admin_document->title . ' обновлен');
        return redirect()->route('admin-documents.index');
    }


    public function destroy(Document $admin_document)
    {
        $admin_document->delete();
        Event::create(
            [
                'user_ip' => request()->getClientIp(),
                'user_id' => Auth::id(),
                'title_event' => 'Удален документ ' . $admin_document->title,
            ]
        );
        session()->flash('success', 'Документ ' . $admin_document->title . ' удален');
        return redirect()->route('admin-documents.index');
    }

}
