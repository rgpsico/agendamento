<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar Events</title>
</head>
<body>
    <h1>Google Calendar Events</h1>
    @if (count($events) > 0)
        <ul>
            @foreach ($events as $event)
                <li>{{ $event->getSummary() }} ({{ $event->getStart()->getDateTime() }} - {{ $event->getEnd()->getDateTime() }})</li>
            @endforeach
        </ul>
    @else
        <p>No events found.</p>
    @endif
    <form action="{{ route('google.calendar.createEvent') }}" method="post">
        @csrf
        <input type="text" name="summary" placeholder="Event Summary">
        <input type="datetime-local" name="start">
        <input type="datetime-local" name="end">
        <button type="submit">Create Event</button>
    </form>
</body>
</html>
