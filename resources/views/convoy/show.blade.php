@extends('layouts.app')

@php
    /** @var \App\Models\Convoy $convoy */
    /** @var \App\Models\Participation $participation */

    switch ($convoy->status) {
        case 'open':
        case 'voting':
            $state = 'primary';
            break;

        case 'meeting':
            $state = 'warning';
            break;

        case 'on_way':
            $state = 'danger';
            break;

        case 'closed':
        case 'draft':
        case 'cancelled':
            $state = 'default';
            break;
    }

    if ($convoy->trashed()) {
        $state = 'default';
    }
@endphp

@section('content')
    @include('convoy.partials.jumbotron', ['state' => $state])

    <div class="row equal">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header text-white bg-{{ $state }}">Сбор конвоя</div>

                <div class="card-block">
                    <p>
                        <span class="badge badge-full badge-{{ $state }}">Дата/время</span>
                        <b>@include('snippets.dateTime', ['date' => $convoy->dateLoc('meeting_datetime'), 'ago' => true])</b>
                    </p>

                    <p>
                        <span class="badge badge-full badge-{{ $state }}">Место сбора</span>
                        <b>{{ $convoy->start_town->getWithCountry() }}</b> ({{ $convoy->start_place }})
                    </p>

                    <p>
                        <span class="badge badge-full badge-{{ $state }}">Игра/сервер</span> {{ $convoy->server->game->name }}, <b>{{ $convoy->server->name }}</b>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <br class="hidden-lg-up">
            <div class="card">
                <div class="card-header text-right text-white bg-{{ $state }}">Движение конвоя</div>

                <div class="card-block">
                    <p>
                        <span class="badge badge-full badge-{{ $state }}">Дата/время</span>
                        <b>@include('snippets.dateTime', ['date' => $convoy->dateLoc('leaving_datetime'), 'ago' => true])</b>
                    </p>
                    <p>
                        <span class="badge badge-full badge-{{ $state }}">Пункт назначения</span>
                        <b>{{ $convoy->finish_town->getWithCountry() }}</b> ({{ $convoy->finish_place }})
                    </p>
                    <p>
                        <span class="badge badge-full badge-{{ $state }}">Остановки по пути</span>
                        @if ($convoy->stops)
                            {{ $convoy->stops }}
                        @else
                            нет
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col">
            <div class="card">
                @if (array_search($convoy->status, ['draft', 'open']) !== false)
                    @can('update', $convoy)
                        <div class="card-footer text-right text-white bg-{{ $state }}">
                            <a href="{{ route('convoy.edit.show', ['id' => $convoy->id], false) }}">
                                <i class="fa fa-fw fa-pencil"></i> Редактировать {{ $convoy->status === 'draft' ? 'и опубликовать' : '' }}
                            </a>
                            <span> | </span>
                            <a href="#" id="cancel_convoy">
                                <i class="fa fa-fw fa-trash"></i> Отменить
                            </a>
                            @can ('pin', $convoy)
                                <span> | </span>
                                <a href="{{ route('convoy.pin', ['id' => $convoy->id], false) }}">
                                    <i class="fa fa-fw {{ !$convoy->pinned ? 'fa-star' : 'fa-star-o' }}"></i> {{ !$convoy->pinned ? 'Закрепить' : 'Открепить' }}
                                </a>
                            @endcan
                        </div>
                    @endcan
                @endif

                <div class="card-block">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <p>
                                <span class="badge badge-full badge-{{ $state }}">Связь</span><br>
                            </p>
                            <p class="autolink">
                                <strong>{{ $convoy->voice_description }}</strong>
                            </p>

                            <hr>
                            <p>
                                <span class="badge badge-full badge-{{ $state }}">Карта конвоя</span>
                            </p>

                            @if ($convoy->map_url_safe or $convoy->map_url)
                                <div class="card">
                                    <a href="{{ $convoy->map_url_safe or $convoy->map_url }}" data-lightbox="image-1" data-toggle="lightbox" data-title='Карта конвоя "{{ $convoy->getNormTitle() }}"'>
                                        <div class="view overlay hm-black-strong">
                                            <img class="card-img-top img-fluid lazy" src="{{ url('pics/loader_small.gif') }}" data-src="{{ $convoy->map_url_safe or $convoy->map_url }}"
                                                 alt='Карта конвоя "{{ $convoy->getNormTitle() }}"'>
                                            <div class="mask flex-center">
                                                <p class="white-text">Открыть на полный экран</p>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="card-footer">
                                        <a href="{{ $convoy->map_url }}" class="card-link" target="_blank">
                                            Прямая ссылка <i class="fa fa-external-link"></i>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <p>
                                    <i>отсутствует</i>
                                </p>
                            @endif
                        </div>


                        <div class="col-lg-8 col-sm-12">
                            <hr class="hidden-lg-up">

                            @if ($convoy->route_length > 0)
                                <p>
                                    <span class="badge badge-full badge-{{ $state }}">Длина маршрута</span> <strong>{{ trans_choice('convoys.route_length', $convoy->route_length) }}</strong>
                                </p>
                                <hr>
                            @endif

                            <p>
                                <span class="badge badge-full badge-{{ $state }}">Описание конвоя</span>
                            </p>

                            @if (count($convoy_dlcs))
                                <div class="alert alert-warning text-center z-depth-1" role="alert">
                                    @php
                                        if (count($convoy_dlcs) === 2) {
                                            $convoy_dlcs = implode(' и ', $convoy_dlcs);
                                        } else if (count($convoy_dlcs) === 3) {
                                            $convoy_dlcs = $convoy_dlcs[0] . ', ' . $convoy_dlcs[1] . ' и ' . $convoy_dlcs[2];
                                        } else if (count($convoy_dlcs) === 1) {
                                            $convoy_dlcs = $convoy_dlcs[0];
                                        }
                                    @endphp
                                    Для участия необходимо наличие DLC <strong>{{ $convoy_dlcs }}</strong>
                                </div>
                            @endif

                            <div class="convoy-description autolink">
                                @if ($convoy->user->can('privileged', \App\Models\Convoy::class))
                                    {!! Purifier::clean($convoy->description) !!}
                                @else
                                    {!! Purifier::clean(strip_tags(nl2br($convoy->description), '<br>')) !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <div class="row">
                        <div class="col text-left">
                            <div class="ya-share2" data-services="vkontakte,telegram,whatsapp,skype,gplus,twitter,odnoklassniki,moimir,facebook,collections,blogger,pocket,surfingbird,tumblr,viber"
                                 data-limit="10" data-counter=""></div>
                        </div>

                        @php
                            $atc_title = str_contains($convoy->getNormTitle(), ['конвой', 'Конвой']) ? $convoy->getNormTitle() : 'Конвой "' . $convoy->getNormTitle() . '"';
                        @endphp

                        <div class="col text-right">
                            <span class="addtocalendar atc-style-menu-wb">
                                <var class="atc_event">
                                    <var class="atc_date_start">{{ $convoy->meeting_datetime }}</var>
                                    <var class="atc_date_end">{{ $convoy->meeting_datetime }}</var>
                                    <var class="atc_timezone">{{ config('app.timezone') }}</var>
                                    <var class="atc_title">{{ $atc_title }} | {{ $convoy->getServerName(true) }}</var>
                                    <var class="atc_description">Сервер: {{ $convoy->getServerName(true) }}. Подробности: {{ route('convoy_show', $convoy->slug) }}</var>
                                    <var class="atc_location">{{ $convoy->start_town->getWithCountry() }} ({{ $convoy->start_place }})</var>
                                    <var class="atc_organizer">[{{ $convoy->user->tag }}] {{ $convoy->user->nickname }}</var>
                                    <var class="atc_ical_filename">rusconvoys_{{ $convoy->id }}</var>
                                </var>
                            </span>
                        </div>
                    </div>
                </div>

                @include('snippets.atFooters', ['model' => $convoy])
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-center text-white bg-{{ $state }}">
                    Участие в конвое
                </div>

                <div class="card-block text-center">
                    @if (Auth::id() !== $convoy->user_id)
                        @if (in_array($convoy->status, ['open', 'meeting']))
                            @if (!Auth::check())
                                <p class="text-fluid">Чтобы зарегистрироваться на конвой, <a
                                            href="{{ route('login', [], false) }}">войди в свою учетную запись</a></p>
                            @else
                                <div class="btn-group btn-group-lg btn-group-justified" role="group">
                                    <a href="#" class="btn btn-success btn-uppercase participate" id="data-yep" data-type="yep" data-convoy="{{ $convoy->id }}"
                                       style="{{ $user_participate == 'yep' ? 'display: none;' : '' }}">
                                        <i class="fa fa-check-circle fa-fw"></i> Участвую!
                                    </a>

                                    <a href="#" class="btn btn-info btn-uppercase participate" id="data-nope" data-type="nope" data-convoy="{{ $convoy->id }}"
                                       style="{{ $user_participate == 'nope' ? 'display: none;' : '' }}">
                                        <i class="fa fa-times-circle fa-fw"></i> Передумал...
                                    </a>

                                    <a href="#" class="btn btn-warning btn-uppercase participate" id="data-thinking" data-type="thinking" data-convoy="{{ $convoy->id }}"
                                       style="{{ $user_participate == 'thinking' ? 'display: none;' : '' }}">
                                        <i class="fa fa-question-circle fa-fw"></i> Подумаю...
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-fluid">Регистрация на конвой закрыта!</p>
                        @endif
                        <hr>
                    @endif

                    @if (count($participations))
                        @if (isset($participations['yep']))
                            <h5>Подтвердили участие:</h5>
                            <p>
                                @foreach ($participations['yep'] as $participation)
                                    @include('user.partials.tagUsername', ['user' => $participation->user()->first()])

                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>
                        @endif

                        @if (isset($participations['yep']) and isset($participations['thinking']))
                            <hr>
                        @endif

                        @if (isset($participations['thinking']))
                            <h5>В раздумьях:</h5>
                            <p>@foreach ($participations['thinking'] as $participation)
                                    @include('user.partials.tagUsername', ['user' => $participation->user()->first()])

                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>
                        @endif
                    @else
                        <br>
                        <p class="text-muted">Еще никто не зарегистрировался на конвой.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="card" id="commentsForConvoy">
        <div class="card-header text-center text-white bg-{{ $state }}">
            {{ trans_choice('convoys.comments_count', $convoy->comments->count()) }}
        </div>

        <div class="card-block">
            @if (Auth::check())
                @if ((Auth::id() === $convoy->user_id or Auth::user()->isGroup(config('roles.admins'))) or (in_array($user_participate, ['yep', 'thinking'])))
                    @if (Auth::user()->can('see_deleted', App\Models\Comment::class))
                        @include('convoy.partials.comments', ['comments' => $convoy->comments()->with('user')->withTrashed()->get()])
                    @else
                        @include('convoy.partials.comments', ['comments' => $convoy->comments()->with('user')->get()])
                    @endif
                @else
                    <p class="text-fluid text-center">Комментарии видят только участники конвоя!</p>
                @endif
            @else
                <p class="text-fluid text-center">Чтобы общаться с участниками конвоя, <a href="{{ route('login', [], false) }}">войди в свою учетную запись</a></p>
            @endif
        </div>

        @if (Auth::check())
            @if ((Auth::id() === $convoy->user_id or Auth::user()->isGroup(config('roles.admins'))) or (in_array($user_participate, ['yep', 'thinking'])))
                @include('convoy.partials.newComment')
            @endif
        @endif
    </div>

    @if (app()->isLocal())
        @if (Auth::check() and Auth::user()->isRole('admin'))
            <hr>
            {{ dump($convoy) }}
            <hr>
            {{ dump($convoy->participations) }}
        @endif
    @endif
@stop

@push('routes')
var URL_convoy_participationPost = '{{ route('convoy_participationPost', [], false) }}';
var URL_convoy_cancel_post = '{{ route('convoy.cancel.post', [], false) }}';
var convoy_id = {{ $convoy->id }};
@endpush

@push('scripts')
<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.9.0/js/lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autolinker/1.4.3/Autolinker.min.js"></script>

<script type="text/javascript">
    var autolink = new Autolinker({
        truncate:  {
            length:   32,
            location: 'smart'
        },
        replaceFn: function (match) {
            var tag = match.buildTag();
            tag.setAttr('noreferrer nofollow noopener');
            tag.setInnerHtml(match.getAnchorText() + ' <small><i class="fa fa-external-link" aria-hidden="true"></i></small>')

            return tag;
        }
    })

    $(".autolink").each(function () {
        $(this).html(
            autolink.link($(this).html())
        );
    });
</script>

<script type="text/javascript">(function () {
        if (window.addtocalendar)if (typeof window.addtocalendar.start == "function")return;
        if (window.ifaddtocalendar == undefined) {
            window.ifaddtocalendar = 1;
            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
            s.type = 'text/javascript';
            s.charset = 'UTF-8';
            s.async = true;
            s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://addtocalendar.com/atc/1.5/atc.min.js';
            var h = d[g]('body')[0];
            h.appendChild(s);
        }
    })();
</script>
@endpush

@push('stylesheets')
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/lightbox2/2.9.0/css/lightbox.min.css">
<link href="//addtocalendar.com/atc/1.5/atc-style-menu-wb.css" rel="stylesheet" type="text/css">
@endpush