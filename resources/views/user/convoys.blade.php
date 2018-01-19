@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $section === 'all' ? 'active' : '' }}" data-toggle="tab" href="#all">
                <i class="fa fa-bookmark fa-fw"></i> Участвую
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ $section === 'own' ? 'active' : '' }}" data-toggle="tab" href="#own">
                <i class="fa fa-bullhorn fa-fw"></i> Организую
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ $section === 'drafts' ? 'active' : '' }}" data-toggle="tab" href="#drafts">
                <i class="fa fa-cogs fa-fw"></i> Черновики
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade {{ $section === 'all' ? 'active show' : '' }}" id="all" role="tabpanel">
            @if (!Auth::user()->getConvoysParticipations()->count())
                <div class="text-center">
                    <i class="fa fa-meh-o fa-6" aria-hidden="true"></i>
                    <p class="display-4">Маловато будет...</p>
                </div>
            @else
                <div>
                    @include('user.partials.convoysTable', [
                        'convoys' => Auth::user()->getConvoysParticipations()->take(20),
                        'no_links' => true,
                        'meeting' => true
                    ])
                </div>
            @endif
        </div>

        <div class="tab-pane fade {{ $section === 'own' ? 'active show' : '' }}" id="own" role="tabpanel">
            @if (!Auth::user()->getOwnConvoys()->count())
                <div class="text-center">
                    <i class="fa fa-meh-o fa-6" aria-hidden="true"></i>
                    <p class="display-4">Маловато будет...</p>
                </div>
            @else
                <div>
                    @include('user.partials.convoysTable', [
                        'convoys' => Auth::user()->getOwnConvoys()->take(20),
                        'no_links' => true,
                        'meeting' => true
                    ])
                </div>
                @endforelse
        </div>

        <div class="tab-pane fade {{ $section === 'drafts' ? 'active show' : '' }}" id="drafts" role="tabpanel">
            @if (!Auth::user()->getDrafts()->count())
                <div class="text-center">
                    <i class="fa fa-meh-o fa-6" aria-hidden="true"></i>
                    <p class="display-4">Маловато будет...</p>
                </div>
            @else
                <div>
                    @include('user.partials.convoysTable', [
                        'convoys' => Auth::user()->getDrafts(),
                        'no_links' => true,
                        'meeting' => true
                    ])
                </div>
                @endforelse
        </div>
    </div>

    <div class="text-right">
        <p>
            <em>Показаны последние 20 конвоев</em>
        </p>
    </div>
@stop
