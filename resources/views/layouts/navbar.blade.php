<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'HOTS Info') }}</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Request::is('heroes*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('heroes') }}">Heroes</a>
                </li>

                <li class="nav-item {{ Request::is('players*') ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('players') }}">Players</a>
                </li>
            </ul>

            <form class="form-inline" action="{{ route('search') }}" method="post">
                {{ csrf_field() }}

                <input class="form-control mr-sm-2" type="search" id="player" name="player" placeholder="Search a player" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">
                    <i class="fa ion-search mr-1"></i>
                    Search
                </button>
            </form>
        </div>
    </div>
</nav>