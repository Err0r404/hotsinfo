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

    <div class="row mb-5">
        <div class="col">
            <div class="table-responsive">
                <table id="heroes" class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Hero</th>
                        <th>Winrate</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($heroes as $key => $hero)
                        <tr>
                            <td class="align-middle">
                                <a href="{{ url('heroes/'.$hero->id) }}">
                                    <img class="rounded-circle" src="{{ URL::asset('/images/heroes/'.$hero->slug.'.jpg') }}" alt="{{ $hero->name }} hero from Heroes Of The Storm" style="max-width: 45px;">
                                </a>
                            </td>
                            <td class="align-middle">
                                <a class="text-dark" href="{{ url('heroes/'.$hero->id) }}">{{ $hero->name }}</a>
                            </td>
                            <td class="align-middle text-center">
                                <span class="d-block @if($hero->winrate >= 50) text-success @else text-danger @endif">{{ $hero->winrate }}%</span>
                                <small>{{ $hero->games }} games</small>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col">
            <div class="table-responsive">
                <table id="maps" class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Map</th>
                        <th>Winrate</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($maps as $key => $map)
                        <tr>
                            <td class="align-middle">
                                <a href="{{ url('maps/'.$map->id) }}">
                                    <img class="img-fluid" src="{{ URL::asset('/images/maps/'.$map->slug.'.jpg') }}" alt="{{ $map->name }} hero from Heroes Of The Storm" style="max-height: 45px;">
                                </a>
                            </td>
                            <td class="align-middle">
                                <a class="text-dark" href="{{ url('maps/'.$map->id) }}">{{ $map->name }}</a>
                            </td>
                            <td class="align-middle text-center">
                                <span class="d-block @if($map->winrate >= 50) text-success @else text-danger @endif">{{ $map->winrate }}%</span>
                                <small>{{ $map->games }} games</small>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col"></div>
    </div>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table id="games" class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th>Game</th>
                        <th>Talents</th>
                        <th colspan="2">Players</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td class="text-center align-middle text-white" style="background: url('{{ URL::asset('/images/maps/'.str_slug($game->map).'.jpg') }}'); background-size: cover;">
                                <a href="{{ url('maps/'.$game->map_id) }}" class="mb-1 text-white font-weight-bold text-shadow">
                                    {{ $game->map }}
                                </a>
                                <br>

                                @if($game->win)
                                    <span class="badge badge-success text-uppercase mb-2">Victory</span>
                                @else
                                    <span class="badge badge-danger text-uppercase mb-2">Defeat</span>
                                @endif
                                <br>

                                <small class="text-shadow">{{ $game->readable_length }}</small>
                                <br>

                                <small class="text-shadow" data-container="body" data-toggle="tooltip" data-placement="top" title="{{ $game->date }}">
                                    {{ $game->ago }}
                                </small>
                            </td>
                            <td class="align-middle text-center">
                                <ul class="list-inline">
                                    @foreach($game->talents as $talent)
                                        <li class="list-inline-item">
                                            <span data-container="body" data-toggle="tooltip" data-placement="top" title="<span class='text-warning'>{{ $talent->name }}</span><br>{{ $talent->description }}" data-html="true">
                                                <img src="//via.placeholder.com/45x45" alt="{{ $talent->name }}">
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($game->teammates as $teammate)
                                        @if($teammate->team === 0)
                                            <li>
                                                <span data-container="body" data-toggle="tooltip" data-placement="top" title="{{ $teammate->hero }}">
                                                    <img class="rounded-circle img-fluid mr-1" src="{{ URL::asset('/images/heroes/'.str_slug($teammate->hero).'.jpg') }}" alt="Portrait of {{ $teammate->hero }} from Heroes Of The Storm" style="max-width: 25px">
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
                                                    <img class="rounded-circle img-fluid mr-1" src="{{ URL::asset('/images/heroes/'.str_slug($teammate->hero).'.jpg') }}" alt="Portrait of {{ $teammate->hero }} from Heroes Of The Storm" style="max-width: 25px">
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

@section('script')
    <script type="text/javascript">
        $(function () {
            var defaultSettings = {
                perPage        : 5,
                insertAfter    : '',
                limitPagination: 0,
                firstLast      : false,
                prevText       : 'Previous',
                nextText       : 'Next',
                ulClass        : 'pagination justify-content-end',
                liClass        : 'page-item',
                activeClass    : 'active',
                disabledClass  : 'disabled'
            };

            defaultSettings.insertAfter = '#heroes';
            $('#heroes tbody').paginathing(defaultSettings);

            defaultSettings.insertAfter = '#maps';
            $('#maps tbody').paginathing(defaultSettings);
        });
    </script>
@endsection
