@php
    /** @var App\Models\Convoy $convoy */
    /** @var \Illuminate\Support\MessageBag $errors */
@endphp

@extends('layouts.app')

@section('content')
    <form role="form" method="POST" id="convoy_form" action="{{ route('convoy.edit.post', [], false) }}">
        {{ csrf_field() }}
        <div class="jumbotron text-center custom-jumbotron text-white">
            <div class="container user-jumbotron-text text-center">
                <div class="row">
                    <div class="col text-center">
                        <div class="title">
                            <h3 class="display-3">Редактирование конвоя №{{ $convoy->id }}</h3>
                            <p class="lead text-center">
                                {!! trans('convoys.help.summary', ['link' => route('rules', [], false)]) !!}
                            </p>
                            <input type="text" name="id" value="{{ $convoy->id }}" hidden>
                            <hr>

                            <div class="{{ $errors->has('title') ? ' has-danger' : '' }}">
                                <div class="col">
                                    <input id="title" type="text" class="form-control text-center {{ ($convoy->background_url) ? 'text-white' : '' }}" name="title"
                                           value="{{ old('title') ?? $convoy->title }}" placeholder="{{ trans('convoys.help.title.name') }}">

                                    @if ($errors->has('title'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </div>
                                    @endif

                                    <span class="form-text text-muted">
                                        {{ trans('convoys.help.title.instruction') }}
                                    </span>

                                    <br>

                                    @if ($convoy->status === 'draft')
                                        @php
                                            $checkbox_state = null !== old('draft');
                                            if (!$checkbox_state) {
                                                if ($convoy->status === 'draft') {
                                                    $checkbox_state = true;
                                                }
                                            }
                                        @endphp

                                        <input type="checkbox" name="draft" id="draft" {{ $checkbox_state ? 'checked=checked' : '' }}>
                                        <label for="draft">
                                            <strong>{!! trans('convoys.help.draft.title') !!}</strong>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row equal">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        {{ trans('convoys.help.meeting_title') }}
                    </div>

                    <div class="card-block">
                        <div class="form-horizontal">
                            <div class="form-group row {{ $errors->has('meeting_datetime') ? ' has-danger' : '' }}">
                                <label for="meeting_datetime" class="col-md-3 col-form-label"
                                       data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.meeting_datetime.instruction', ['timezone' => Auth::user()->timezone]) }}">
                                    {{ trans('convoys.help.meeting_datetime.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="meeting_datetime" type="text" class="form-control datetimepicker" name="meeting_datetime" required>
                                </div>

                                @if ($errors->has('meeting_datetime'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('meeting_datetime') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('server_id') ? ' has-danger' : '' }}">
                                <label for="server_id" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.only_admin') }}">
                                    {{ trans('convoys.help.server_id.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="server_name" type="text" class="form-control" name="server_name" value="{{ $convoy->server->getWithGame() }}" required disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="start_town_id" class="col-md-3 col-form-label"
                                       data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.only_admin') }}">
                                    {{ trans('convoys.help.start_town_id.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="start_town_name" type="text" class="form-control" name="start_town_name" value="{{ $convoy->start_town->getWithCountry() }}" required disabled>
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('start_place') ? ' has-danger' : '' }}">
                                <label for="start_place" class="col-md-3 col-form-label"
                                       data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.start_place.instruction') }}">
                                    {{ trans('convoys.help.start_place.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="start_place" type="text" class="form-control"
                                           name="start_place"
                                           value="{{ old('start_place') ?? $convoy->start_place }}">

                                    @if ($errors->has('start_place'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('start_place') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <br class="hidden-lg-up">
                <div class="card">
                    <div class="card-header text-md-right text-white bg-primary">
                        {{ trans('convoys.help.leaving_title') }}
                    </div>

                    <div class="card-block">
                        <div class="form-horizontal">
                            <div class="form-group row {{ $errors->has('leaving_datetime') ? ' has-danger' : '' }}">
                                <label for="leaving_datetime" class="col-md-3 col-form-label"
                                       data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.leaving_datetime.instruction', ['timezone' => Auth::user()->timezone]) }}">
                                    {{ trans('convoys.help.leaving_datetime.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="leaving_datetime" type="text" class="form-control datetimepicker" name="leaving_datetime" required>

                                    @if ($errors->has('leaving_datetime'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('leaving_datetime') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('finish_town_id') ? ' has-danger' : '' }}">
                                <label for="finish_town_id" class="col-md-3 col-form-label"
                                       data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.only_admin') }}">
                                    {{ trans('convoys.help.finish_town_id.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="finish_town_name" type="text" class="form-control" name="finish_town_name" value="{{ $convoy->finish_town->getWithCountry() }}" required disabled>
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('finish_place') ? ' has-danger' : '' }}">
                                <label for="finish_place" class="col-md-3 col-form-label"
                                       data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.finish_place.instruction') }}">
                                    {{ trans('convoys.help.finish_place.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="finish_place" type="text" class="form-control" name="finish_place" value="{{ old('finish_place') ?? $convoy->finish_place }}" required>

                                    @if ($errors->has('finish_place'))
                                        <span class="form-control-feedback">
                                            <strong>{{ $errors->first('finish_place') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('stops') ? ' has-danger' : '' }}">
                                <label for="stops" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.stops.instruction') }}">
                                    <em>{{ trans('convoys.help.stops.title') }}</em>
                                </label>

                                <div class="col-md-9">
                                    <input id="stops" placeholder="{{ trans('convoys.help.empty') }}" type="text" class="form-control" name="stops" value="{{ old('stops') ?? $convoy->stops }}">

                                    @if ($errors->has('stops'))
                                        <span class="form-control-feedback">
                                            <strong>{{ $errors->first('stops') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="form-horizontal">
                            <div class="form-group row" id="dlcs">
                                <label for="meeting_datetime" class="col-md-3 col-form-label">
                                    {{ trans('convoys.help.dlcs.title') }}
                                </label>

                                <div class="col-md-9">
                                    <div class="row">
                                        @foreach ($dlcs as $dlc)
                                            @if ($dlc->name === 'base')
                                                @continue
                                            @endif
                                            <div class="col-md">
                                                <input type="checkbox" name="dlc[{{ $dlc->id }}]" id="{{ $dlc->name }}" {{ in_array($dlc->id, $convoy->dlcs) ? 'checked=checked' : '' }}">
                                                <label for="{{ $dlc->name }}">
                                                    <strong>{{ $dlc->screen_name }}</strong>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <span class="form-text text-muted">
                                        <p>{{ trans('convoys.help.dlcs.instruction') }}</p>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('route_length') ? ' has-danger' : '' }}">
                                <label for="meeting_datetime" class="col-md-3 col-form-label">
                                    <em>{{ trans('convoys.help.route_length.title') }}</em>
                                </label>

                                <div class="col-md-9">
                                    <input id="route_length" type="number" class="form-control"
                                           name="route_length"
                                           min="0"
                                           max="99999"
                                           value="{{ old('route_length') ?? $convoy->route_length }}">

                                    @if ($errors->has('route_length'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('route_length') }}</strong>
                                        </div>
                                    @endif

                                    <span class="form-text text-muted">
                                        <p>{!! trans('convoys.help.route_length.instruction') !!}</p>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('voice_description') ? ' has-danger' : '' }}">
                                <label for="meeting_datetime" class="col-md-3 col-form-label">
                                    {{ trans('convoys.help.voice.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="voice_description" type="text" class="form-control" name="voice_description" value="{{ old('voice_description') ?? $convoy->voice_description }}">

                                    @if ($errors->has('voice_description'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('voice_description') }}</strong>
                                        </div>
                                    @endif

                                    <span class="form-text text-muted">
                                        <p>
                                            {!! trans('convoys.help.voice.instruction') !!}
                                        </p>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('map_url') ? ' has-danger' : '' }}">
                                <label for="map_url" class="col-md-3 col-form-label">
                                    <em>{{ trans('convoys.help.map_url.title') }}</em>
                                </label>

                                <div class="col-md-9">
                                    <input id="map_url" placeholder="{{ trans('convoys.help.empty') }}" type="text" class="form-control" name="map_url"
                                           value="{{ old('map_url') ?? $convoy->map_url }}">

                                    @if ($errors->has('map_url'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('map_url') }}</strong>
                                        </div>
                                    @endif

                                    <span class="form-text text-muted">
                                        <p>
                                            {{ trans('convoys.help.map_url.instruction') }}<br /><strong>{{ trans('convoys.help.urlmime') }}</strong>
                                        </p>
                                    </span>
                                </div>
                            </div>

                            @if (Auth::user()->can('privileged', \App\Models\Convoy::class))
                                <div class="form-group row {{ $errors->has('background_url') ? ' has-danger' : '' }}">
                                    <label for="background_url" placeholder="{{ trans('convoys.help.empty') }}" class="col-md-3 col-form-label">
                                        <em>{{ trans('convoys.help.background_url.title') }}</em>
                                    </label>

                                    <div class="col-md-9">
                                        <input id="background_url" type="text" class="form-control" name="background_url" value="{{ old('background_url') ?? $convoy->background_url }}">

                                        @if ($errors->has('background_url'))
                                            <div class="form-control-feedback">
                                                <strong>{{ $errors->first('background_url') }}</strong>
                                            </div>
                                        @endif

                                        <span class="form-text text-muted">
                                            <p>
                                                {!! trans('convoys.help.background_url.instruction') !!}<br /><strong>{{ trans('convoys.help.urlmime') }}</strong>
                                            </p>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <div class="col-sm-12">
                            <label for="description" class="col-form-label">
                                {{ trans('convoys.help.description.title') }}
                            </label>

                            @php
                                $description = Purifier::clean(
                                    $errors->has('description') ? $errors->first('description') : trim($convoy->description)
                                );
                            @endphp

                            <textarea id="description" name="description" class="form-control" style="height: 300px;">{{ $description }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            {!! trans('convoys.help.button.save', ['icon' => 'save']) !!}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if (Auth::check() and config('app.env') == 'local' and Auth::user()->isRole('admin'))
        <hr>
        {{ dump($convoy->getAttributes()) }}
    @endif
@endsection

@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.0/moment-with-locales.min.js"></script>

@if ($convoy->user->can('privileged', \App\Models\Convoy::class))
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.5/tinymce.min.js"></script>
@endif

<script type="text/javascript">
    $(document).ready(function () {
        $('input#meeting_datetime').bootstrapMaterialDatePicker({
            format:      'DD.MM.Y HH:mm',
            currentDate: moment('{{ (null !== old('meeting_datetime')) ? timePicker(old('meeting_datetime')) : timePicker($convoy->meeting_datetime) }}'),
            minDate:     moment({hour: 0, minutes: 0, seconds: 0}),
            cancelText:  'Отмена',
            okText:      'Далее',
            clearText:   'Очистить',
            nowText:     'Сейчас',
            lang:        'ru',
            weekStart:   1,
        }).on('open', function () {
            var selector = 'div#' + $(this).data('dtp');
            $(selector).removeClass('animated fadeOut').addClass('animated fadeIn');
            $(selector).find('.dtp-content').removeClass('animated zoomOut').addClass('animated zoomIn');
        });

        $('input#leaving_datetime').bootstrapMaterialDatePicker({
            format:      'DD.MM.Y HH:mm',
            currentDate: moment('{{ (null !== old('leaving_datetime')) ? timePicker(old('leaving_datetime')) : timePicker($convoy->leaving_datetime) }}'),
            minDate:     moment({hour: 0, minutes: 0, seconds: 0}).add('30', 'minutes'),
            cancelText:  'Отмена',
            clearText:   'Очистить',
            nowText:     'Сейчас',
            lang:        'ru',
            weekStart:   1,
        }).on('open', function () {
            var selector = 'div#' + $(this).data('dtp');
            $(selector).removeClass('animated fadeOut').addClass('animated fadeIn');
            $(selector).find('.dtp-content').removeClass('animated zoomOut').addClass('animated zoomIn');
        });

        @if ($convoy->status === 'draft')
            $('form#convoy_form').on('submit', function (event) {
            if ($('input#draft').prop('checked') !== false) {
                return;
            }

            event.preventDefault();

            swal({
                title:               "Проверь всё дважды!",
                text:                "Ты почти опубликовал конвой. После публикации ты не сможешь вернуть конвой в черновики, однако по-прежнему сможешь редактировать информацию.",
                type:                "warning",
                showCancelButton:    true,
                showLoaderOnConfirm: true,
                confirmButtonColor:  "#DD6B55",
                confirmButtonText:   "Всё готово!",
                cancelButtonText:    "Еще раз проверю...",
                confirmButtonClass:  'btn btn-primary',
                cancelButtonClass:   'btn btn-danger',
                buttonsStyling:      false,
                preConfirm:          function (meme) {
                    return new Promise(function (resolve, reject) {
                        $('form#convoy_form').unbind("submit").submit();
                        setTimeout(function () {
                            resolve();
                        }, 20000);
                    })
                }
            }).then(function (meme) {
                swal({text: "Хм... Что-то слишком долго. Обнови страницу и попробуй еще раз."});
            });
        });
        @endif
    });
</script>

@if ($convoy->user->can('privileged', \App\Models\Convoy::class))
    @include('snippets.editor')
@endif
@endpush

@push('stylesheets')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush

