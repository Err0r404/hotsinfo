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
                print_r($enemies);
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

    <div class="row mb-5">
        <div class="col">
            <h2>Talents</h2>

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
                                    <img class="rounded img-fluid" src="{{ URL::asset('/images/talents/'.str_slug($hero->name).'/'.str_slug($talent->name).'.png') }}" alt="{{ $talent->name }}" style="max-width: 45px;">
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

    <div class="row">
        <div class="col-md-6">
            <h3>Enemies</h3>

            @if (count($enemies) > 0)
                <div class="table-responsive">
                    <table id="enemies" class="table table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Hero</th>
                            <th>Game</th>
                            <th>Winrate against {{ $hero->name }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($enemies as $key => $enemy)
                            <tr>
                                <td>
                                    <a href="{{ url('heroes/'.$enemy->id) }}">
                                        <img class="rounded-circle" src="{{ URL::asset('/images/heroes/'.$enemy->slug.'.jpg') }}" alt="{{ $enemy->name }} hero from Heroes Of The Storm" style="max-width: 45px;">
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ url('heroes/'.$enemy->id) }}">
                                        {{ $enemy->name }}
                                    </a>
                                </td>
                                <td class="align-middle">
                                <span data-toggle="tooltip" data-placement="top" data-container="body" title="{{ $enemy->games }}">
                                    {{ $enemy->readable_games }}
                                </span>
                                </td>
                                <td class="align-middle text-center">
                                    {{ $enemy->winrate }}%
                                    <div class="progress" style="height: 2px">
                                        <div class="progress-bar @if($enemy->winrate >= 50) bg-success @else bg-danger @endif" role="progressbar" style="width: {{ $enemy->winrate }}%;" aria-valuenow="{{ $enemy->winrate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- Load enemies using ajax --}}
                <div class="table-responsive d-none">
                    <table id="enemies" class="table table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Hero</th>
                            <th>Game</th>
                            <th>Winrate against {{ $hero->name }}</th>
                        </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>

                <div class="text-center">
                    <i class="fa fa-spin fa-fw fa-2x ion-load-d"></i>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <h3>Allies</h3>

            @if (count($allies) > 0)
                <div class="table-responsive">
                    <table id="allies" class="table table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Hero</th>
                            <th>Game</th>
                            <th>Winrate with {{ $hero->name }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($allies as $key => $ally)
                            <tr>
                                <td>
                                    <a href="{{ url('heroes/'.$ally->id) }}">
                                        <img class="rounded-circle" src="{{ URL::asset('/images/heroes/'.$ally->slug.'.jpg') }}" alt="{{ $ally->name }} hero from Heroes Of The Storm" style="max-width: 45px;">
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ url('heroes/'.$ally->id) }}">
                                        {{ $ally->name }}
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <span data-toggle="tooltip" data-placement="top" data-container="body" title="{{ $ally->games }}">
                                        {{ $ally->readable_games }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    {{ $ally->winrate }}%
                                    <div class="progress" style="height: 2px">
                                        <div class="progress-bar @if($ally->winrate >= 50) bg-success @else bg-danger @endif" role="progressbar" style="width: {{ $ally->winrate }}%;" aria-valuenow="{{ $ally->winrate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{-- Load enemies using ajax --}}
                <div class="table-responsive d-none">
                    <table id="allies" class="table table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Hero</th>
                            <th>Game</th>
                            <th>Winrate against {{ $hero->name }}</th>
                        </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>

                <div class="text-center">
                    <i class="fa fa-spin fa-fw fa-2x ion-load-d"></i>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            // If enemies's list is empty load it by ajax
            if($("#enemies").find("tbody tr").length === 0){
                console.log("No enemy pre-loaded");

                $.ajax({
                    url     : "{{ url('heroes/'.$hero->id.'/enemies') }}",
                    dataType: "json"
                })
                    .done(function(data) {
                        console.log("success");
                        console.log(data);

                        data.forEach(function (enemy) {
                            let bgColor = "bg-success";
                            if(enemy.winrate < 50)
                                bgColor = "bg-danger";

                            let $tr = $("<tr/>").appendTo($("#enemies").find("tbody"));

                            let $td = $("<td/>", {class: "align-middle"}).appendTo($tr);
                            let $a  = $("<a/>", {href: "/heroes/"+enemy["id"]}).appendTo($td);
                            $("<img/>", {src: "/images/heroes/"+enemy["slug"]+".jpg"}).css({maxWidth:"45px"}).appendTo($a);

                            $td = $("<td/>", {class: "align-middle"}).appendTo($tr);
                            $("<a/>", {href: "/heroes/"+enemy["id"], html: enemy["name"]}).appendTo($td);

                            $td = $("<td/>", {class: "align-middle"}).appendTo($tr);
                            $("<span/>", {dataToggle:"tooltip", dataPlacement:"top", dataContainer:"body", title:enemy["games"], html: enemy["readable_games"]}).appendTo($td);

                            $td = $("<td/>", {class: "align-middle text-center", html:enemy["winrate"]+"%"}).appendTo($tr);
                            let $progress = $("<div/>", {class: "progress"}).css({height: "2px"}).appendTo($td);
                            $("<div/>", {class: "progress-bar "+bgColor, role:"progressbar",  ariaValuenow:enemy["winrate"], ariaValuemin:"0", ariaValuemax:"100"}).css({width: enemy["winrate"]+"%"}).appendTo($progress);
                        });

                        // Hide spinner
                        $("#enemies").parent(".d-none").next().addClass("d-none");

                        // Show table
                        $("#enemies").parent(".d-none").removeClass("d-none");

                        // Enable tooltip
                        $('[data-toggle="tooltip"]').tooltip();
                    })
                    .fail(function() {
                        console.error("fail");
                    });
            }
            else{
                console.log("Enemies pre-loaded");
            }

            // If enemies's list is empty load it by ajax
            if($("#allies").find("tbody tr").length === 0){
                console.log("No ally pre-loaded");

                $.ajax({
                    url     : "{{ url('heroes/'.$hero->id.'/allies') }}",
                    dataType: "json"
                })
                    .done(function(data) {
                        console.log("success");
                        console.log(data);

                        data.forEach(function (enemy) {
                            let bgColor = "bg-success";
                            if(enemy.winrate < 50)
                                bgColor = "bg-danger";

                            let $tr = $("<tr/>").appendTo($("#allies").find("tbody"));

                            let $td = $("<td/>", {class: "align-middle"}).appendTo($tr);
                            let $a  = $("<a/>", {href: "/heroes/"+enemy["id"]}).appendTo($td);
                            $("<img/>", {src: "/images/heroes/"+enemy["slug"]+".jpg"}).css({maxWidth:"45px"}).appendTo($a);

                            $td = $("<td/>", {class: "align-middle"}).appendTo($tr);
                            $("<a/>", {href: "/heroes/"+enemy["id"], html: enemy["name"]}).appendTo($td);

                            $td = $("<td/>", {class: "align-middle"}).appendTo($tr);
                            $("<span/>", {dataToggle:"tooltip", dataPlacement:"top", dataContainer:"body", title:enemy["games"], html: enemy["readable_games"]}).appendTo($td);

                            $td = $("<td/>", {class: "align-middle text-center", html:enemy["winrate"]+"%"}).appendTo($tr);
                            let $progress = $("<div/>", {class: "progress"}).css({height: "2px"}).appendTo($td);
                            $("<div/>", {class: "progress-bar "+bgColor, role:"progressbar",  ariaValuenow:enemy["winrate"], ariaValuemin:"0", ariaValuemax:"100"}).css({width: enemy["winrate"]+"%"}).appendTo($progress);
                        });

                        // Hide spinner
                        $("#allies").parent(".d-none").next().addClass("d-none");

                        // Show table
                        $("#allies").parent(".d-none").removeClass("d-none");

                        // Enable tooltip
                        $('[data-toggle="tooltip"]').tooltip();
                    })
                    .fail(function() {
                        console.error("fail");
                    });
            }
            else{
                console.log("Enemies pre-loaded");
            }
        });
    </script>
@endsection
