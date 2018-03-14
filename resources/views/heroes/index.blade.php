@extends('layouts.layout')

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">Heroes</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th></th>
                    <th>Hero</th>
                    <th>Games</th>
                    <th>Winrate</th>
                </tr>
                </thead>

                <tbody>
                @foreach($heroes as $key => $hero)
                    <tr id="">
                        <td>
                            <img class="rounded-circle" src="{{ URL::asset('/images/heroes/'.$hero->slug.'.jpg') }}"
                                 alt="{{ $hero->name }} hero from Heroes Of The Storm" style="max-width: 45px;">
                        </td>
                        <td class="align-middle">
                            <a href="{{ url('heroes/'.$hero->id) }}">{{ $hero->name }}</a>
                        </td>
                        <td class="align-middle">Coming soon…</td>
                        <td class="align-middle">Coming soon…</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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