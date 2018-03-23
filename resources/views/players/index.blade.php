@extends('layouts.layout')

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-4">Search a player</h1>

            <form action="{{ route('search') }}" method="post">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="sr-only" for="player">Player's name or BattleTag</label>
                    <input type="text" class="form-control" id="player" name="player" aria-describedby="playerHelp" placeholder="Enter a player's name" value="Inflambio#2979">
                    <small id="playerHelp" class="form-text text-muted">
                        Search for players using their game's names or their battletags<br>
                        Example : Err0r404 or Err0r404#2244
                    </small>
                </div>
                
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa ion-search"></i>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection