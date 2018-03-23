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
        </div>
    </div>
</nav>