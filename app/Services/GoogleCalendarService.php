<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CALENDAR_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CALENDAR_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setAccessType('offline');
    }

    public function authenticate($authCode = null)
    {
        if ($authCode) {
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
            if (isset($accessToken['error'])) {
                throw new \Exception(join(', ', $accessToken));
            }
            $this->client->setAccessToken($accessToken);
            session(['google_access_token' => $accessToken]);
        } else {
            $authUrl = $this->client->createAuthUrl();
            return $authUrl;
        }
    }

    public function getClient()
    {
        if (session()->has('google_access_token')) {
            $accessToken = session('google_access_token');
            if ($this->client->isAccessTokenExpired()) {
                $refreshToken = $this->client->getRefreshToken();
                $accessToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
                session(['google_access_token' => $accessToken]);
            } else {
                $this->client->setAccessToken($accessToken);
            }
        }
        return $this->client;
    }

    public function listEvents($calendarId = 'primary')
    {
        $service = new Google_Service_Calendar($this->client);
        $events = $service->events->listEvents($calendarId);
        return $events->getItems();
    }

    public function createEvent(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setRedirectUri(env('GOOGLE_CALENDAR_REDIRECT_URI'));
    
        // Verificar se o token de acesso está na sessão
        if (!$request->session()->has('google_calendar_access_token')) {
            return redirect()->route('google.calendar.auth');
        }
    
        $accessToken = $request->session()->get('google_calendar_access_token');
        $client->setAccessToken($accessToken);
    
        // Verificar se o token de acesso está expirado
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $request->session()->put('google_calendar_access_token', $client->getAccessToken());
        }
    
        $service = new Google_Service_Calendar($client);
    
        $event = new \Google_Service_Calendar_Event([
            'summary' => 'A new event',
            'start' => ['dateTime' => Carbon::now()->toRfc3339String()],
            'end' => ['dateTime' => Carbon::now()->addHour()->toRfc3339String()],
        ]);
    
        $createdEvent = $service->events->insert(env('GOOGLE_CALENDAR_ID'), $event);
    
        return response()->json(['message' => 'Event created!', 'event' => $createdEvent]);
    }
    
}
