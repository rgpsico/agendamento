<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class GoogleCalendarController extends Controller
{
    public function authenticate(Request $request)
    {
        if ($request->has('code')) {
            $client = new \Google_Client();
            $client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
            $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI'));
            $client->authenticate($request->input('code'));
            $token = $client->getAccessToken();
            session(['google_calendar_token' => $token]);
            return redirect()->route('google.calendar.events');
        } else {
            $client = new \Google_Client();
            $client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
            $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI'));
            $client->addScope(\Google_Service_Calendar::CALENDAR);
            $authUrl = $client->createAuthUrl();
            return redirect()->away($authUrl);
        }
    }

    public function listEvents()
    {
        $events = Event::get();
        return view('google.calendar.events', compact('events'));
    }

    public function createEvent(Request $request)
    {
        Event::create([
            'name' => 'A new event',
            'startDateTime' => Carbon::now(),
            'endDateTime' => Carbon::now(),
        ]);

        return 'Event created!';
    }


    public function getAllEvents()
    {
        $events = Event::get();

        return view('events.index', ['events' => $events]);
    }


    public function getEventsByCalendarId($calendarId)
    {
        $events = Event::get(null, [], $calendarId);
        return view('events.index', ['events' => $events]);
    }
}
