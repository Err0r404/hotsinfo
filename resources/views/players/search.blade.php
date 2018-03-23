@extends('layouts.layout')

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-4">Results for <i class="fa fa-angle-double-left mr-1" aria-hidden="true"></i>{{ $player }}<i class="fa fa-angle-double-right ml-1" aria-hidden="true"></i></h1>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Battle Tag</th>
                        <th class="text-right">Last update</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($players as $key => $player)
                        <tr>
                            <td>
                                <a href="{{ url('players/'.$player->id) }}">
                                    {{ $player->battletag }}
                                </a>
                            </td>
                            <td class="text-right">{{ $player->updated_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection