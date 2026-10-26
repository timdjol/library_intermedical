<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\Event;
use App\Support\StoredFile;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['categories', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        $categories = Category::orderBy('title')->get();

        return view('admin.documents.form', compact('categories'));
    }

    public function store(DocumentRequest $request)
    {
        $document = Document::createFromRequest($request);
        Event::record('Добавлен документ '.$document->title);
        session()->flash('success', 'Документ '.$document->title.' добавлен');

        return redirect()->route('admin-documents.index');
    }

    public function show(Document $admin_document)
    {
        Event::record('Просмотрен документ '.$admin_document->title);

        return view('admin.documents.show', compact('admin_document'));
    }

    public function edit(Document $admin_document)
    {
        $categories = Category::orderBy('title')->get();
        $admin_document->load('categories');

        return view('admin.documents.form', compact('admin_document', 'categories'));
    }

    public function update(DocumentUpdateRequest $request, Document $admin_document)
    {
        $previousTitle = $admin_document->title;
        $admin_document->updateFromRequest($request);
        Event::record('Изменен документ '.$previousTitle.' на '.$admin_document->title);
        session()->flash('success', 'Документ '.$admin_document->title.' обновлен');

        return redirect()->route('admin-documents.index');
    }

    public function destroy(Document $admin_document)
    {
        StoredFile::delete($admin_document->path);
        $title = $admin_document->title;
        $admin_document->categories()->detach();
        $admin_document->delete();
        Event::record('Удален документ '.$title);
        session()->flash('success', 'Документ '.$title.' удален');

        return redirect()->route('admin-documents.index');
    }
}
