<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woordspel</title>
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    


</head>
<body>
    <header>
        @include('layouts.navigation')
    </header>
    <div>
        <h2>Incoming Friend Requests</h2>

        @if ($incomingRequests->isEmpty())
            <p>No incoming friend requests.</p>
        @else
            <ul>
                @foreach ($incomingRequests as $request)
                    <li>
                        From: {{ $request->sender->name }}
                        <form action="{{ route('accept.friend.request', ['friendshipId' => $request->id]) }}" method="POST">
                            @csrf
                            <button type="submit">Accept</button>
                        </form>
                        <form action="{{ route('decline.friend.request', ['friendshipId' => $request->id]) }}" method="POST">
                            @csrf
                            <button type="submit">Decline</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
</html>