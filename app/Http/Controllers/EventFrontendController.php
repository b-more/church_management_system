<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventFrontendController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('status', '!=', 'Cancelled')
            ->where('end_date', '>=', now()->format('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        $pastEvents = Event::where('status', '!=', 'Cancelled')
            ->where('end_date', '<', now()->format('Y-m-d'))
            ->orderBy('start_date', 'desc')
            ->limit(3)
            ->get();

        return view('events.index', compact('upcomingEvents', 'pastEvents'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        // Get registration stats
        $stats = [
            'totalRegistrations' => $event->registrations()->count(),
            'confirmedRegistrations' => $event->registrations()->where('status', 'Confirmed')->count(),
            'registrationPercentage' => $event->registration_percentage,
            'daysLeft' => $event->registration_deadline ? now()->diffInDays($event->registration_deadline, false) : null,
        ];

        return view('events.show', compact('event', 'stats'));
    }

    public function register($id)
    {
        $event = Event::findOrFail($id);

        // Check if registration is still open
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Registration for this event has closed.');
        }

        return view('events.register', compact('event'));
    }

    public function storeRegistration(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Check if registration is still open
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Registration for this event has closed.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'attendance_status' => 'required|string|in:Confirmed attending,Coming but not sure',
            'special_requirements' => 'nullable|string',
        ]);

        // Split full name into first and last name for database
        $nameParts = explode(' ', $validated['full_name'], 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Check for existing member or create new one
        $member = DB::table('members')
            ->where('phone', $validated['phone'])
            ->first();

        if (!$member) {
            // Create a new member record with basic details
            $memberId = DB::table('members')->insertGetId([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $validated['phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $memberId = $member->id;
        }

        // Create registration
        $registration = new EventRegistration();
        $registration->event_id = $event->id;
        $registration->member_id = $memberId;
        $registration->registration_number = 'REG-' . Str::upper(Str::random(8));
        $registration->status = $validated['attendance_status'] == 'Confirmed attending' ? 'Confirmed' : 'Pending';
        $registration->registered_at = now();
        $registration->special_requirements = $validated['special_requirements'] ?? null;
        $registration->save();

        return redirect()->route('events.registration.confirmation', ['id' => $event->id, 'registration' => $registration->id])
            ->with('success', 'You have successfully registered for this event!');
    }

    public function showConfirmation($id, $registrationId)
    {
        $event = Event::findOrFail($id);
        $registration = EventRegistration::findOrFail($registrationId);

        if ($registration->event_id != $event->id) {
            abort(404);
        }

        return view('events.confirmation', compact('event', 'registration'));
    }
}
