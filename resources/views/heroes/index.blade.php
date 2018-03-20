@extends('layouts.layout')

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">Heroes</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Hero</th>
                        <th>Pick</th>
                        <th class="w-25">Winrate</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($heroes as $key => $hero)
                        @if ($hero->games > 1)
                            <tr id="">
                                <td>
                                    <a href="{{ url('heroes/'.$hero->id) }}">
                                        <img class="rounded-circle" src="{{ URL::asset('/images/heroes/'.$hero->slug.'.jpg') }}" alt="{{ $hero->name }} hero from Heroes Of The Storm" style="max-width: 45px;">
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ url('heroes/'.$hero->id) }}">{{ $hero->name }}</a>
                                </td>
                                <td class="align-middle">
                                    <span data-toggle="tooltip" data-placement="top" data-container="body" title="{{ $hero->games }}">
                                        {{ $hero->readable_games }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    {{ $hero->winrate }}%
                                    <div class="progress" style="height: 2px">
                                        <div class="progress-bar @if($hero->winrate >= 50) bg-success @else bg-danger @endif" role="progressbar" style="width: {{ $hero->winrate }}%;" aria-valuenow="{{ $hero->winrate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

{{--
    <div class="row">
        <div class="col">
            <ul class="list list-inline row">
                @foreach($heroes as $key => $hero)
                    <li class="list-inline-item col-lg-2 col-sm-3 col-6 mr-0 mb-4">
                        <a href="{{ url('heroes/'.$hero->id) }}" class="text-dark">
                            <div class="card">
                                <img class="card-img-top" src="{{ URL::asset('/images/heroes/'.$hero->slug.'.jpg') }}" alt="{{ $hero->name }} hero from Heroes Of The Storm">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-0"><span class="name">{{ $hero->name }}</span></h6>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
--}}
@endsection