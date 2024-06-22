<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;

class GoogleCalendarController extends Controller
{
    protected $googleCalendar;

    public function __construct(GoogleCalendarService $googleCalendar)
    {
        $this->googleCalendar = $googleCalendar;
    }

    public function authenticate(Request $request)
    {
        if ($request->has('code')) {
            $this->googleCalendar->authenticate($request->input('code'));
            return redirect()->route('google.calendar.events');
        } else {
            $authUrl = $this->googleCalendar->authenticate();
            return redirect()->away($authUrl);
        }
    }

    public function listEvents()
    {
        $events = $this->googleCalendar->listEvents();
        return view('google.calendar.events', compact('events'));
    }

    public function createEvent(Request $request)
    {
        $eventData = [
            'summary' => $request->input('summary'),
            'start' => ['dateTime' => $request->input('start')],
            'end' => ['dateTime' => $request->input('end')],
        ];
        $event = $this->googleCalendar->createEvent('primary', $eventData);
        return redirect()->route('google.calendar.events');
    }
}
