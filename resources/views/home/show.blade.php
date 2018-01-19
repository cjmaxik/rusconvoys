@php
    /** @var \App\Models\Convoy $convoy */
    /** @var \App\Models\Convoy $loop */
@endphp

@extends('layouts.app')

@section('content')
    @if (Auth::guest())
        <div class="container">
            <div class="white-text text-center">
                <h1 class="display-3 home-brand">
                    <b><i class="fa fa-truck fa-fw animate-truck"></i> {{ config('app.name') }}</b>
                </h1>
                <br>
                <p>На&nbsp;данном сайте ты&nbsp;можешь найти предстоящие конвои в&nbsp;TruckersMP -<br>неофициальном мультиплеере для Euro Truck Simulator 2&nbsp;и&nbsp;American Truck Simulator</p>
            </div>
        </div>

        <hr>
    @endif

    @if ($pinned->count())
        <h3 class="white-text text-center">
            @if (Auth::guest())
                Вот некоторые интересные конвои:
            @else
                Закрепленные конвои
            @endif
        </h3>

        <br>

        <div class="text-center">
            @php
                if (Auth::guest()) {
                    $pinned = $pinned->take(6);
                }
            @endphp

            @foreach ($pinned->chunk(2) as $chunk)
                <div class="row">
                    @foreach ($chunk as $convoy)
                        <div class="col-xs-12 {{ ($chunk->count() === 1) ? 'col-md-6 offset-md-3' : 'col-md' }} flex-cards">
                            @include('home.partials.convoyCard', ['convoy' => $convoy])
                        </div>
                    @endforeach
                </div>

                @if (!$loop->last)
                    <br>
                @endif
            @endforeach
        </div>
    @endif

    @if (Auth::guest())
        <div class="container">
            <div class="white-text text-center">
                <h3>Хочешь увидеть больше конвоев или создать свой?</h3>
                <br>
                <a href="/login" id="login-button" class="nav-item nav-link bg-success hoverable login-button waves-effect waves-light text-white">
                    <i class="fa fa-steam fa-fw left" aria-hidden="true"></i> Заходи на&nbsp;сайт прямо сейчас!
                </a>
                <br>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                @if ($convoys_count === 0)
                    <h3 class="white-text text-center">
                        Увы, нет ни одного конвоя...
                    </h3>
                @else
                    @if ($convoys->count() !== 0)
                        @if ($pinned->count() !== 0)
                            <hr>
                            <h3 class="white-text text-center">
                                Другие конвои
                            </h3>
                        @else
                            <h3 class="white-text text-center">
                                Все конвои
                            </h3>
                        @endif
                    @endif

                    <br>

                    @include('home.partials.homeTable', [
                        'convoys' => $convoys,
                        'meeting' => true
                    ])

                    <hr>
                @endif

                @can('create', App\Models\Convoy::class)
                    <p class="white-text text-center">
                        <a class="btn btn-warning btn-block btn-lg" href="{{ route('convoy.new.show', [], false) }}">
                            <i class="fa fa-plus fa-fw"></i> <strong>Создать конвой?</strong>
                        </a>
                    </p>
                @endcan
            </div>
        </div>
    @endif

@endsection