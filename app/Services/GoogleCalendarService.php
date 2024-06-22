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

    public function createEvent($calendarId = 'primary', $eventData)
    {
        $service = new Google_Service_Calendar($this->client);
        $event = new Google_Service_Calendar_Event($eventData);
        $event = $service->events->insert($calendarId, $event);
        return $event;
    }
}
