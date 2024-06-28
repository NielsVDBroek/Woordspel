@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leaderboard</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Games Won</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->games_won }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection