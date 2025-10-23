<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Storage;

class Event1controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $events = Event::with('organizer')->latest()->paginate(15);
        return view('BackOffice.Events.index', compact('events'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('BackOffice.Events.create');
    }

    /**
     * Store event
     */
    public function store(EventRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $data['status'] = $request->has('publish_now') ? 'published' : 'draft';

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Événement créé avec succès');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('BackOffice.Events.edit', compact('event'));
    }

    /**
     * Update event
     */
    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour avec succès');
    }

    /**
     * Delete event
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Événement supprimé');
    }

    /**
     * Show participants for an event
     */
    public function participants($id)
    {
        $event = Event::with('participants.user')->findOrFail($id);
        return view('BackOffice.Events.participant', compact('event'));
    }

    /**
     * Remove a participant from event
     */
    public function removeParticipant($eventId, $participantId)
    {
        $participant = Participant::where('event_id', $eventId)->where('id', $participantId)->firstOrFail();
        $participant->delete();

        return redirect()->back()->with('success', 'Participant supprimé avec succès');
    }
}