@php
    /** @var \App\Models\User $user */
@endphp

@extends('layouts.app')

@section('content')
    @include('user.partials.jumbotron', ['user' => $user])

    @if ($user->isRole('banned'))
        <div class="row">
            <div class="col">
                <div class="card card-danger text-center z-depth-2">
                    <div class="card-block">
                        <span class="white-text">
                            Пользователь был забанен {{ $user->ban->timestamp }}<br>
                            @if ($user->ban->message)
                            Причина: {{ $user->ban->message }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::check() and Auth::user()->isGroup('administration'))
        @include('user.partials.admin', ['user' => $user])
    @endif

    <div class="row">
        @include('user.partials.info', ['user' => $user])
    </div>

    <br>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-white bg-primary">
                    <i class="fa fa-list fa-fw"></i> История пользователя <em>(последние 5)</em>
                </div>

                <div class="card-block">
                    <div class="row">
                        <div class="col">
                            @if (!$user->histories->count())
                                <p class="text-center">
                                    <i class="fa fa-meh-o fa-5x"></i><br>
                                    Маловато будет...
                                </p>
                            @else
                                @foreach ($user->getHistory()->take(5) as $history)
                                    <h4 class="list-group-item-heading">
                                        <b><i class="fa fa-{{ trans('history.'.$history->type.'.icon') }} fa-fw"></i> {!! trans('history.'.$history->type.'.title') !!}</b>

                                        @if ($history->to)
                                            - {{ $history->from or 'Пусто' }} => {{ $history->to }}
                                        @endif
                                    </h4>
                                    <span class="list-group-item-text">
                                        @if ($history->message)
                                            Сообщение: <em>{{ $history->message }}</em>
                                            |
                                        @endif
                                        @include('snippets.dateTime', ['date' => $history->dateLoc('created_at')])
                                    </span>

                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <br>

    <div class="row equal">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-header text-white bg-primary">
                    <i class="fa fa-bullhorn fa-fw"></i> Конвои пользователя <em>(последние 10)</em>
                </div>

                <div class="card-block">
                    @if (!$user->getOwnConvoys()->count())
                        <p class="text-center">
                            <i class="fa fa-meh-o fa-5x"></i><br>
                            Маловато будет...
                        </p>
                    @else
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Конвой</th>
                                <th>Отбытие</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($user->getOwnConvoys()->take(10) as $convoy)
                                @php
                                    switch ($convoy->status) {
                                        case 'open':
                                            $style = 'primary';
                                            break;

                                        case 'meeting':
                                            $style = 'warning';
                                            break;

                                        case 'on_way':
                                            $style = 'danger';
                                            break;

                                        case 'voting':
                                            $style = 'primary';
                                            break;

                                        case 'closed':
                                            $style = 'default';
                                            break;

                                        default:
                                            $style = 'default';
                                            break;
                                    }
                                @endphp

                                <tr>
                                    <td>
                                        <a class="btn btn-{{ $style }} btn-xs btn-block" href="{{ route('convoy_show', ['slug' => $convoy->slug], false) }}">
                                            {{ $convoy->getNormTitle() }}
                                        </a>
                                    </td>

                                    <td class="align-middle">
                                             <span class="text-{{ $style }}">
                                                @include('snippets.dateTime', ['date' => $convoy->dateLoc('leaving_datetime')])
                                            </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-header text-white bg-primary">
                    <i class="fa fa-bookmark fa-fw"></i> Участие в конвоях <em>(последние 10)</em>
                </div>

                <div class="card-block">
                    @if (!$user->getConvoysParticipations()->count())
                        <p class="text-center">
                            <i class="fa fa-meh-o fa-5x"></i><br>
                            Маловато будет...
                        </p>
                    @else
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Конвой</th>
                                <th>Отбытие</th>
                            </tr>
                            </thead>

                            <tbody>
                            {{--{{dd($user->getConvoysParticipations()->take(10))}}--}}
                            @foreach ($user->getConvoysParticipations()->take(10) as $convoy)
                                @php
                                    switch ($convoy->status) {
                                        case 'open':
                                            $style = 'primary';
                                            break;

                                        case 'meeting':
                                            $style = 'warning';
                                            break;

                                        case 'on_way':
                                            $style = 'danger';
                                            break;

                                        case 'voting':
                                            $style = 'primary';
                                            break;

                                        case 'closed':
                                            $style = 'default';
                                            break;
                                    }
                                @endphp

                                <tr>
                                    <td>
                                        <a class="btn btn-{{ $style }} btn-xs btn-block" href="{{ route('convoy_show', ['slug' => $convoy->slug], false) }}">
                                            {{ $convoy->getNormTitle() }}
                                        </a>
                                    </td>

                                    <td class="align-middle">
                                             <span class="text-{{ $style }}">
                                                @include('snippets.dateTime', ['date' => $convoy->dateLoc('leaving_datetime')])
                                            </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->isGroup(config('roles.admins')) and app()->isLocal())
        <hr>
        <div class="row">
            <div class="col">
                <div class="card card-secondary">
                    <div class="card-block">
                        {{ dump($user) }}
                        {{ dump($user->getRoles()) }}
                        {{ dump($user->getOwnConvoys()) }}
                        {{ dump($user->getConvoysParticipations()) }}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
