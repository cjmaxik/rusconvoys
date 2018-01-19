@php
    if ($convoy->background_url_safe) {
        $back_url = $convoy->background_url_safe;
    } elseif ($convoy->background_url) {
        $back_url = $convoy->background_url;
    } elseif ($convoy->map_url_safe) {
        $back_url = $convoy->map_url_safe;
    } elseif ($convoy->map_url) {
        $back_url = $convoy->map_url;
    } else {
        $back_url = url('/pics/008.jpg');
    }
@endphp

<div class="card convoy-card">
    <div class="card-img view overlay lazy loading" data-src="{{ $back_url }}" alt="{{ $convoy->title }}">
        <a href="{{ route('convoy_show', ['slug' => $convoy->slug]) }}">
            <div class="mask waves-effect waves-light"></div>
        </a>
    </div>

    <div class="card-block {{ $convoy->status !== 'open' ? 'yellow' : '' }}">
        <h4 class="card-title">
            <a href="{{ route('convoy_show', ['slug' => $convoy->slug]) }}" title="{{ $convoy->getNormTitle() }}">
               {{ $convoy->getNormTitle() }} @if (count($convoy->dlcs)) <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Необходимы DLC"></i> @endif
            </a>
        </h4>
    </div>

    <div class="card-footer convoy-description {{ $convoy->status !== 'open' ? 'yellow' : '' }}">
        <span class="hidden-md-down">
            Маршрут: <b>{{ $convoy->getRoute() }}</b><br>Игра <b>{{ $convoy->server->game->name }}</b>, cервер <b>{{ $convoy->server->name }}</b><br>{{ trans_choice('convoys.participations_count', $convoy->participations_count) }}
        </span>

        <span class="hidden-lg-up">
            Маршрут: <b>{{ $convoy->getRoute() }}</b><br>Игра <b>{{ $convoy->server->game->shortname }}</b>, cервер <b>{{ $convoy->server->shortname }}</b><br>{{ trans_choice('convoys.participations_count', $convoy->participations_count) }}
        </span>
        <br>
    </div>

    <div class="card-footer text-muted {{ $convoy->status !== 'open' ? 'yellow' : '' }}">
        <small class="pull-left">
            @include('user.partials.tagUsername', ['user' => $convoy->user])
        </small>

        <small class="pull-right">
            @include('snippets.dateTime', ['date' => $convoy->dateLoc('leaving_datetime'), 'no_date' => true])
        </small>
    </div>
</div>

@if (!$loop->last)
    <br>
@endif
