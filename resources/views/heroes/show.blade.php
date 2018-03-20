@extends('layouts.layout')

@section('title')
    {{ $hero->name }}
@stop

@section('container')
{{--
    <div class="row">
        <div class="col">
            @php
                echo "<pre>";
                print_r($hero);
                echo "</pre>";

                echo "<pre>";
                print_r($talents);
                echo "</pre>";
            @endphp
        </div>
    </div>
--}}

    <div class="row mb-5">
        <div class="col-2">
            <img class="img-fluid w-100" src="{{ URL::asset('/images/heroes/'.$hero->slug.'.jpg') }}" alt="Portrait of {{ $hero->name }} from Heroes Of The Storm">
        </div>

        <div class="col-10">
            <h1>{{ $hero->name }}</h1>
            <h2>
                <a href=""></a>
                {{ $hero->role }}
            </h2>
            <p class="lead mb-2">{{ $hero->readable_games }} games played</p>
            <p class="lead mb-0 @if($hero->winrate >= 50) text-success @else text-danger @endif">{{ $hero->winrate }}% of winrate</p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Talent</th>
                        <th scope="col">Pick</th>
                        <th scope="col">Popularity</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($talents as $level => $talentsList)
                        <tr class="table-dark">
                            <td colspan="4" class="border-top-0 lead">
                                Level {{ $level }}
                            </td>
                        </tr>
                        @foreach($talentsList as $key => $talent)
                            <tr>
                                <td>
                                    <img class="rounded" src="//via.placeholder.com/45x45" alt="{{ $talent->name }}">
                                </td>
                                <td class="align-middle">
                                    <span  data-toggle="tooltip" data-placement="top" data-container="body" title="{{ $talent->description }}">
                                        {{ $talent->name }}
                                    </span>
                                </td>
                                <td class="align-middle">{{ $talent->pick }}</td>
                                <td class="align-middle text-center">
                                    {{ $talent->popularity }}%
                                    <div class="progress" style="height: 2px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $talent->popularity }}%" aria-valuenow="{{ $talent->popularity }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection