<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function destroy(Event $admin_event)
    {
        $admin_event->delete();
        session()->flash('success', 'Событие ' . $admin_event->title_event . ' удалено из системы');
        return redirect()->route('admin-events.index');
    }

}
