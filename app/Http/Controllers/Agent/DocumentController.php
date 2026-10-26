<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use App\Models\Event;

class DocumentController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->where('parent_id', 0)->orderBy('title')->get();

        return view('agent.documents.index', compact('categories'));
    }

    public function show(Document $agent_document)
    {
        abort_unless((int) $agent_document->status === 1, 404);

        Event::record('Просмотрен документ '.$agent_document->title);

        return view('agent.documents.show', compact('agent_document'));
    }
}
