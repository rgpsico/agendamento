<!DOCTYPE html>
<html>
<head>
    <title>Eventos do Calendário</title>
</head>
<body>
    <h1>Eventos do Calendário</h1>
    @if($events->isEmpty())
        <p>Não há eventos.</p>
    @else
        <ul>
            @foreach($events as $event)
                <li>
                    {{ $event->name }}: {{ $event->startDateTime }} - {{ $event->endDateTime }}
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
