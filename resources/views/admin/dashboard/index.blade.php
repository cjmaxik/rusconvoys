@php
    /** @var \App\Models\Convoy $convoy */
@endphp

@extends('layouts.app')

@section('content')
    <h2 class="text-center">
        <i class="fa fa-dashboard fa-fw left"></i> Панель управления
    </h2>

    <hr>

    <div class="row">
        <div class="btn-group">
            <a href="{{ route('admin.constant.index') }}" class="btn btn-secondary">
                Константы
            </a>

            <a href="{{ route('admin.opcache.clear') }}" class="btn btn-default">
                Чистка Opcache
            </a>

            <a href="{{ route('admin.opcache.status') }}" class="btn btn-default">
                Статус Opcache
            </a>
        </div>
    </div>

    <hr>

    <div class="text-center">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <a href="{{ route('admin.dashboard.all_users', [], false) }}">
                    <i class="fa fa-users fa-5x" aria-hidden="true"></i>
                    <br>
                    <br>
                    <h3>
                        {{ trans_choice('convoys.users_count', $users_count) }}
                    </h3>
                </a>
            </div>
            <div class="col-xs-12 col-md-4">
                <a href="{{ route('admin.dashboard.all_convoys', [], false) }}">
                    <i class="fa fa-truck fa-5x" aria-hidden="true"></i>
                    <br>
                    <br>
                    <h3>{{ trans_choice('convoys.convoys_count', $convoys_count) }}</h3>
                </a>
            </div>
            <div class="col-xs-12 col-md-4">
                <a href="{{ route('admin.dashboard.all_comments', [], false) }}">
                    <i class="fa fa-comments fa-5x" aria-hidden="true"></i>
                    <br>
                    <br>
                    <h3>{{ trans_choice('convoys.comments_count', $comments_count) }}</h3>
                </a>
            </div>
        </div>
    </div>

    <hr>

    <div class="container">
        <h2 class="text-center">
            Последние 10
        </h2>

        <div class="row">
            <div class="col-xs-12 col-md-4 text-center">
                <ul class="list-group">
                    @foreach ($last_users as $user)
                        <a href="{{ route('profile.id_redirect', $user->id) }}" target="_blank" class="list-group-item list-group-action">
                            {{ $user->nickname }}
                        </a>

                        @if ($loop->last)
                            <li class="list-group-item">...</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-xs-12 col-md-4">
                <ul class="list-group">
                    @foreach ($last_convoys as $convoy)
                        <a href="{{ route('convoy.id_redirect', $convoy->id) }}" target="_blank" class="list-group-item list-group-action">
                            {{ $convoy->getNormTitle() }}
                        </a>

                        @if ($loop->last)
                            <li class="list-group-item">...</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-xs-12 col-md-4">
                <ul class="list-group">
                    @foreach ($last_comments as $comment)
                        <a href="{{ route('convoy.id_redirect', $comment->convoy_id) }}" target="_blank" class="list-group-item list-group-action">
                            {{ str_limit($comment->text, 30) }}
                        </a>

                        @if ($loop->last)
                            <li class="list-group-item">...</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop