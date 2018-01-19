@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ Auth::user()->unreadNotifications->count() ? 'active' : '' }}" data-toggle="tab" href="#unread" role="tab">Непрочитанные</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Auth::user()->unreadNotifications->count() ? '' : 'active' }}" data-toggle="tab" href="#all" role="tab">Все</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade {{ Auth::user()->unreadNotifications->count() ? 'active show' : '' }}" id="unread" role="tabpanel">
            <div class="content">
                @forelse (Auth::user()->unreadNotifications->take(30) as $notification)
                    @include('notify.'.class_basename($notification->type), ['is_page' => true])
                @empty
                    <div class="text-center">
                        <i class="fa fa-thumbs-o-up fa-6" aria-hidden="true"></i>
                        <p class="display-4">Ты уже обо всём знаешь :)</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade {{ Auth::user()->unreadNotifications->count() ? '' : 'active show' }}" id="all" role="tabpanel">
            <div class="content">
                @forelse (Auth::user()->notifications->take(30) as $notification)
                    @include('notify.'.class_basename($notification->type), ['is_page' => true])
                @empty
                    <div class="text-center">
                        <i class="fa fa-thumbs-o-up fa-6" aria-hidden="true"></i>
                        <p class="display-4">Ты уже обо всём знаешь :)</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if (Auth::user()->unreadNotifications->count())
        @php
            Auth::user()->unreadNotifications()->update(['read_at' => Date::now()]);
        @endphp
    @endif

    <div class="text-right">
        <p>
            <em>Показаны последние 30 уведомлений</em>
        </p>
    </div>
@stop
