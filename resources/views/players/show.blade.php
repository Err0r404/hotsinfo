@extends('layouts.layout')

@section('title')
    {{ $player->battletag }}
@stop

@section('container')
    {{--
    <div class="row">
        <div class="col">
            @php
                echo "<pre>";
                print_r($enemies);
                echo "</pre>";
            @endphp
        </div>
    </div>
    --}}

    <div class="row mb-5">
        <div class="col-2">
            <img class="img-fluid w-100" src="//via.placeholder.com/150x150" alt="Portrait of {{ $player->battletag }} from Heroes Of The Storm">
        </div>

        <div class="col-10">
            <h1>{{ $player->battletag }}</h1>
            <p class="lead mb-2">{{ $player->readable_games }} games played</p>
            <p class="lead mb-2 @if($player->winrate >= 50) text-success @else text-danger @endif">{{ $player->winrate }}% of winrate</p>
            <p class="lead mb-0">{{ $player->readable_length }} played</p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table id="games" class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th>Map</th>
                        <th>Result</th>
                        <th>Length</th>
                        <th>Date</th>
                        <th colspan="2">Players</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td>
                                <a href="{{ url('maps/'.$game->map_id) }}" class="text-dark">
                                    {{ $game->map }}
                                </a>
                            </td>
                            <td>
                                @if($game->win)
                                    <span class="badge badge-success">Victory</span>
                                @else
                                    <span class="badge badge-danger">Defeat</span>
                                @endif
                            </td>
                            <td>
                                {{ $game->readable_length }}</td>
                            <td>
                                <span data-container="body" data-toggle="tooltip" data-placement="top" title="{{ $game->date }}">
                                    {{ $game->ago }}
                                </span>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($game->teammates as $teammate)
                                        @if($teammate->team === 0)
                                            <li>
                                                <span data-container="body" data-toggle="tooltip" data-placement="top" title="{{ $teammate->hero }}">
                                                    <img class="rounded-circle img-fluid mr-1" src="{{ URL::asset('/images/heroes/'.$teammate->hero_slug.'.jpg') }}" alt="Portrait of {{ $teammate->hero }} from Heroes Of The Storm" style="max-width: 25px">
                                                </span>

                                                @if($teammate->silenced == 1)
                                                    <i class="fa ion-android-microphone-off" data-container="body" data-toggle="tooltip" data-placement="top" title="Silenced"></i>
                                                @endif

                                                <a href="{{ url('players/'.$teammate->player_id) }}" class="@if($teammate->player_id == $player->id) text-warning @else text-dark @endif">
                                                    {{ $teammate->battletag }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($game->teammates as $teammate)
                                        @if($teammate->team === 1)
                                            <li>
                                                <span data-container="body" data-toggle="tooltip" data-placement="top" title="{{ $teammate->hero }}">
                                                    <img class="rounded-circle img-fluid mr-1" src="{{ URL::asset('/images/heroes/'.$teammate->hero_slug.'.jpg') }}" alt="Portrait of {{ $teammate->hero }} from Heroes Of The Storm" style="max-width: 25px">
                                                </span>

                                                @if($teammate->silenced == 1)
                                                    <i class="fa ion-android-microphone-off" data-container="body" data-toggle="tooltip" data-placement="top" title="Silenced"></i>
                                                @endif

                                                <a href="{{ url('players/'.$teammate->player_id) }}" class="@if($teammate->player_id == $player->id) text-warning @else text-dark @endif">
                                                    {{ $teammate->battletag }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <nav aria-label="Games navigation">
                {{ $games->links() }}
            </nav>
        </div>
    </div>
@endsection
