@extends('layouts.app')

@php
    /** @var \Illuminate\Support\MessageBag $errors */
    /** @var App\Models\Server $server */
    /** @var App\Models\Game $game */
    /** @var App\Models\Country $country */
    /** @var App\Models\City $city */
    /** @var App\Models\DLC $dlc */
@endphp

@section('content')
    <div class="card card-inverse red text-center hidden-sm-up">
        <div class="card-block">
            <h3 class="card-title">ВНИМАНИЕ!</h3>
            <p class="card-text text-white">Похоже, что ты пытаешься создать конвой через телефон. Обращаем твое внимание на то, что форма создания конвоя может работать некорректно. Пожалуйста,
                                            воспользуйся формой с компьютера.</p>
        </div>
    </div>

    <form role="form" method="POST" action="{{ route('convoy.new.post', [], false) }}" id="convoy_form">
        {{ csrf_field() }}
        <div class="jumbotron custom-jumbotron no-background">
            <div class="container user-jumbotron-text text-center">
                <div class="row">
                    <div class="col-md-12 text-center">

                        <span class="title">
                            <h3 class="display-3">
                                Создание конвоя
                            </h3>
                            <p class="lead text-center">
                                {!! trans('convoys.help.summary', ['link' => route('rules', [], false)]) !!}<br><br>{!! trans('convoys.help.draft.notice') !!}
                            </p>

                            <hr>

                            <div class="{{ $errors->has('title') ? ' has-danger' : '' }}">
                                <div class="col-xs-12">
                                    <input id="title" type="text" class="form-control" name="title" value="{{ (null !== old('title')) ? old('title') : '' }}"
                                           placeholder="{{ trans('convoys.help.title.name') }}">

                                    @if ($errors->has('title'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </div>
                                    @endif

                                    <span class="form-text text-muted">
                                        {{ trans('convoys.help.title.instruction') }}<br>
                                    </span>
                                </div>
                            </div>
                        </span>
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
                                <label for="meeting_datetime" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.meeting_datetime.instruction', ['timezone' => Auth::user()->timezone]) }}">
                                    {{ trans('convoys.help.meeting_datetime.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="meeting_datetime" type="text" class="form-control datetimepicker" name="meeting_datetime">
                                </div>

                                @if ($errors->has('meeting_datetime'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('meeting_datetime') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('server_id') ? ' has-danger' : '' }}">
                                <label for="server_id" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.server_id.instruction') }}">
                                    {{ trans('convoys.help.server_id.title') }}
                                </label>

                                <div class="col-md-9">
                                    <select class="form-control" name="server_id" id="server_id" style="width: 100%" data-placeholder="{{ trans('convoys.help.server_id.placeholder') }}" required>
                                        @foreach ($games->reverse() as $game)
                                            <option></option>
                                            <optgroup label="{{ $game->name }}" data-id="{{ $game->shortname }}">
                                                @foreach ($game->servers as $server)
                                                    <option value="{{ $server->id }}"
                                                            {{ old('server_id') === (string) $server->id ? 'selected' : '' }}
                                                            title="{{ $game->shortname }}, {{ $server->name }}">{{ $server->name }} {{ $server->online ? '' : ' (не в сети)' }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($errors->has('server_id'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('server_id') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('start_town_id') ? ' has-danger' : '' }}">
                                <label for="start_town_id" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.start_town_id.instruction') }}">
                                    {{ trans('convoys.help.start_town_id.title') }}
                                </label>

                                <div class="col-md-9">
                                    <select class="form-control" name="start_town_id" id="start_town_id"
                                            style="width: 100%" data-placeholder="{{ trans('convoys.help.start_town_id.placeholder') }}"
                                            {{ (null !== old('server_id')) ? '' : 'disabled' }} required>
                                        @foreach ($countries as $country)
                                            <option></option>
                                            <optgroup label="{{ $country->name }}"
                                                      class="game_{{ $country->game->shortname }}">
                                                @foreach ($country->cities as $city)
                                                    <option value="{{ $city->id }}"
                                                            {{ old('start_town_id') === (string) $city->id ? 'selected' : '' }} data-dlc="{{ $city->dlc_id }}">{{ Auth::user()->getOption('rus_names') ? $city->name :
                                                            transliterator_transliterate('Any-Latin; Latin-ASCII;', $city->name) }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($errors->has('start_town_id'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('start_town_id') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('start_place') ? ' has-danger' : '' }}">
                                <label for="start_place" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.start_place.instruction') }}">
                                    {{ trans('convoys.help.start_place.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="start_place" type="text" class="form-control" name="start_place" value="{{ (null !== old('start_place')) ? old('start_place') : '' }}"
                                           required>
                                </div>

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

            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header text-md-right text-white bg-primary">
                        {{ trans('convoys.help.leaving_title') }}
                    </div>

                    <div class="card-block">
                        <div class="form-horizontal">
                            <div class="form-group row {{ $errors->has('leaving_datetime') ? ' has-danger' : '' }}">
                                <label for="leaving_datetime" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.leaving_datetime.instruction', ['timezone' => Auth::user()->timezone]) }}">
                                    {{ trans('convoys.help.leaving_datetime.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="leaving_datetime" type="text" class="form-control datetimepicker" name="leaving_datetime" required>
                                </div>

                                @if ($errors->has('leaving_datetime'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('leaving_datetime') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('finish_town_id') ? ' has-danger' : '' }}">
                                <label for="finish_town_id" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.finish_town_id.instruction') }}">
                                    {{ trans('convoys.help.finish_town_id.title') }}
                                </label>

                                <div class="col-md-9">
                                    <select class="form-control" name="finish_town_id" id="finish_town_id" style="width: 100%" {{ (null !== old('server_id')) ? '' : 'disabled' }}
                                    data-placeholder="{{ trans('convoys.help.finish_town_id.placeholder') }}" required>
                                        @foreach ($countries as $country)
                                            <option></option>
                                            <optgroup label="{{ $country->name }}">
                                                @foreach ($country->cities as $city)
                                                    <option class="game_{{ $country->game->shortname }}"
                                                            value="{{ $city->id }}" {{ old('finish_town_id') === (string) $city->id ? 'selected' : '' }} data-dlc="{{ $city->dlc_id }}">{{ Auth::user()->getOption('rus_names') ? $city->name :
                                                            transliterator_transliterate('Any-Latin; Latin-ASCII;', $city->name) }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($errors->has('finish_town_id'))
                                    <span class="form-control-feedback">
                                        <strong>{{ $errors->first('finish_town_id') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('finish_place') ? ' has-danger' : '' }}">
                                <label for="finish_place" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.finish_place.instruction') }}">
                                    {{ trans('convoys.help.finish_place.title') }}
                                </label>

                                <div class="col-md-9">
                                    <input id="finish_place" type="text" class="form-control" name="finish_place"
                                           value="{{ old('finish_place') ?? '' }}" required>
                                </div>

                                @if ($errors->has('finish_place'))
                                    <span class="form-control-feedback">
                                        <strong>{{ $errors->first('finish_place') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group row {{ $errors->has('stops') ? ' has-danger' : '' }}">
                                <label for="stops" class="col-md-3 col-form-label" data-toggle="tooltip"
                                       data-title="{{ trans('convoys.help.stops.instruction') }}">
                                    <em>{{ trans('convoys.help.stops.title') }}</em>
                                </label>

                                <div class="col-md-9">
                                    <input id="stops" placeholder="{{ trans('convoys.help.empty') }}" type="text" class="form-control" name="stops"
                                           value="{{ old('stops') ?? '' }}">
                                </div>

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
                                                <input type="checkbox" name="dlc[{{ $dlc->id }}]" id="{{ $dlc->name }}">
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
                                           value="{{ old('route_length') ?? '0' }}">

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
                                    <input id="voice_description" type="text" class="form-control"
                                           name="voice_description"
                                           value="{{ old('voice_description') ?? '' }}"
                                           required>

                                    @if ($errors->has('voice_description'))
                                        <div class="form-control-feedback">
                                            <strong>{{ $errors->first('voice_description') }}</strong>
                                        </div>
                                    @endif

                                    <span class="form-text text-muted">
                                            <p>{!! trans('convoys.help.voice.instruction') !!}</p>
                                        </span>
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('map_url') ? ' has-danger' : '' }}">
                                <label for="map_url" class="col-md-3 col-form-label">
                                    <em>{{ trans('convoys.help.map_url.title') }}</em>
                                </label>

                                <div class="col-md-9">
                                    <input id="map_url" placeholder="{{ trans('convoys.help.empty') }}" type="text" class="form-control" name="map_url"
                                           value="{{ old('map_url') ?? '' }}">

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
                                    <label for="background_url" class="col-md-3 col-form-label">
                                        <em>{{ trans('convoys.help.background_url.title') }}</em>
                                    </label>

                                    <div class="col-md-9">
                                        <input id="background_url" placeholder="{{ trans('convoys.help.empty') }}" type="text" class="form-control" name="background_url"
                                               value="{{ old('background_url') ?? '' }}">

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

                            @if ($errors->has('description'))
                                <div class="red-text">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </div>
                            @endif

                            <textarea id="description" name="description"
                                      class="form-control">{{ (null !== old('description')) ? old('description') : '' }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button id="confirm" class="btn btn-primary btn-block btn-lg">
                            {!! trans('convoys.help.button.create', ['icon' => 'plus']) !!}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"></script>

@if (Auth::user()->can('privileged', \App\Models\Convoy::class))
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.5/tinymce.min.js"></script>
@endif

<script type="text/javascript">
    $(document).ready(function () {
        function scrollToInvalid () {
            var navHeight = 70;
            var invalid_el = $('input:invalid, select:invalid').first().offset().top - navHeight;

            if (invalid_el > (window.pageYOffset - navHeight) && invalid_el < (window.pageYOffset + window.innerHeight - navHeight)) {
                return true;
            } else {
                $('html, body').scrollTop(invalid_el);
            }
        }

        $('input').on('invalid', scrollToInvalid);

        $('input#meeting_datetime').bootstrapMaterialDatePicker({
            format:      'DD.MM.Y HH:mm',
            currentDate: moment('{{ (null !== old('meeting_datetime')) ? timePicker(old('meeting_datetime')) : $now->format('Y-m-d\TH:i:s') }}'),
            minDate:     moment({hour: 0, minutes: 0, seconds: 0}),
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

        $('input#leaving_datetime').bootstrapMaterialDatePicker({
            format:      'DD.MM.Y HH:mm',
            currentDate: moment('{{ (null !== old('leaving_datetime')) ? timePicker(old('leaving_datetime')) : $now->addMinutes(30)->format('Y-m-d\TH:i:s') }}'),
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

        $('form#convoy_form').on('submit', function (event) {
            event.preventDefault();

            swal({
                title:               "Проверь всё дважды!",
                html:                "Ты почти создал конвой. После сохранения конвой попадет в черновики, также ты <strong>больше не сможешь поменять сервер и города</strong>.",
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

        $.fn.oldChosen = $.fn.chosen;
        $.fn.chosen = function (options) {
            var select               = $(this)
                , is_creating_chosen = !!options;

            if (is_creating_chosen && select.css('position') === 'absolute') {
                select.removeAttr('style')
            }

            var ret = select.oldChosen(options);

            if (is_creating_chosen && select.css('display') === 'none') {
                select.attr('style', 'display:visible; position:absolute; clip:rect(0,0,0,0)');
                select.attr('tabindex', -1);
            }
            return ret
        };
        //*/

        $('select#server_id, select#start_town_id, select#finish_town_id').chosen({
            no_results_text:                 'Ничего не найдено',
            display_disabled_options:        false,
            include_group_label_in_selected: true,
            width:                           '100%',
            search_contains:                 true,
        }).trigger('chosen:updated');

        $('select#server_id').on('change', function (event) {
            event.preventDefault();
            var game = $('select#server_id option:selected').parent().data('id');
            var game_class = '.game_' + game;
            var nogame_class = '.game_' + (game === 'ETS2' ? 'ATS' : 'ETS2');
            ``
            $(game_class).prop('disabled', false);
            $(nogame_class).prop('disabled', true);

            $('select#start_town_id').prop('disabled', false).trigger('chosen:updated');
            $('select#finish_town_id').prop('disabled', false).trigger('chosen:updated');

            if (game === 'ATS') {
                $('div#dlcs').slideUp('slow');
                $('input[id^="dlc_"').prop('checked', false);
            } else {
                $('div#dlcs').slideDown('slow');
            }
        });

        $('select#start_town_id, select#finish_town_id').on('change', function (event) {
            event.preventDefault();
            var title = $('select#start_town_id option:selected').text().trim() + ' - ' + $('select#finish_town_id option:selected').text().trim();
            $('input#title').attr('placeholder', title);
        });

        $('select#server_id, select#start_town_id, select#finish_town_id').on('chosen:showing_dropdown', function () {
            $(this).next('.chosen-container').children('.chosen-drop').slideDown(250);
        }).on('chosen:hiding_dropdown', function () {
            $(this).next('.chosen-container').children('.chosen-drop').slideUp(250);
        });
    });
</script>

@if (Auth::user()->can('privileged', \App\Models\Convoy::class))
    @include('snippets.editor')
@else
    <script type="text/javascript">
        $('textarea').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        }).on('focus', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }).on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
@endif
@endpush

@push('stylesheets')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush


