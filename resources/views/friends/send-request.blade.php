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
        <h3 class="text-lg font-semibold mb-4">{{ __('Send Friend Request') }}</h3>
<form action="{{ route('send.friend.request', ['recipientId' => $recipient->id]) }}" method="POST">
    @csrf
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
        {{ __('Send Friend Request to :recipient_name', ['recipient_name' => $recipient->name]) }}
    </button>
</form>
<hr class="my-6">

    </div>

</body>
</html>