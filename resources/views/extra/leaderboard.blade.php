@extends('layouts.app')

@section('content')
    <h1>Leaderboard</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Games Won</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaderboard as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->games_won }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection