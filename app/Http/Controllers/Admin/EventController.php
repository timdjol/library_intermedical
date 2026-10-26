<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->latest()->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function destroy(Event $admin_event)
    {
        $title = $admin_event->title_event;
        $admin_event->delete();
        session()->flash('success', 'Событие '.$title.' удалено из системы');

        return redirect()->route('admin-events.index');
    }
}
