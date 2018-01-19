@php
    /** @var \App\Models\User $user */
@endphp

@extends('layouts.app')

@section('content')
    @include('user.partials.jumbotron', ['user' => $user, 'no_pics' => true, 'no_links' => true])

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white bg-primary">
                    <i class="fa fa-cog fa-fw"></i> Настройки профиля
                </div>

                <div class="card-block">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('user_changeSettings', [], false) }}">
                        {{ csrf_field() }}

                        <legend class="col-form-label text-center">Основные настройки</legend>
                        <br>

                        <div class="form-group row {{ $errors->has('nickname') ? ' has-danger' : '' }}">
                            <label for="nickname" class="col-md-2 control-label">Никнейм</label>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <input id="nickname" type="text" class="form-control" name="nickname" value="{{ old('nickname') ?? $user->nickname }}" required>
                                </div>

                                @if ($errors->has('nickname'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </div>
                                @endif

                                <span class="form-text text-muted">
                                    <p>Твой никнейм на данном сайте. <strong>Соблюдай <a href="{{ route('rules', [], false) }}">правила сайта</a>!</strong></p>
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="col-md-2 control-label">E-Mail адрес</label>

                            <div class="col-md-9">
                                <div class="checkbox">
                                    <input type="checkbox" id="subscribe" name="subscribe" {{ old('subscribe') ? 'checked' : $subscribed }}>
                                    <label for="subscribe">Присылайте мне уведомления на E-mail</label>
                                </div>

                                <div id="subscribe_input" style="{{ (!$user->email or null !== old('email')) ? 'display:none;' : '' }}">
                                    <div class="input-group">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? $user->email }}">
                                        <span class="input-group-addon" data-toggle="tooltip" title="Только администратор и вы можете видеть это поле">
                                            <i class="fa fa-eye-slash text-danger"></i>
                                        </span>
                                    </div>
                                </div>

                                @if ($errors->has('email'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row {{ $errors->has('tag') ? ' has-danger' : '' }}">
                            <label for="tag" class="col-md-2 control-label">Тэг</label>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group tag-color-group">
                                            <span class="input-group-addon tag-color-select">
                                                <select class="{{ $user->tag_color or 'default' }}" id="tag_color" data-toggle="tooltip" title="Выбери цвет тэга">
                                                    <option value=""></option>
                                                    @foreach ($tag_styles as $style)
                                                        <option class="{{ $style }}" value="{{ $style }}">
                                                            {{ $style }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </span>

                                            <input id="tag" type="text" maxlength="15" class="form-control {{ $user->tag_color or 'default' }} tag-color-input" name="tag"
                                                   value="{{ old('tag') ?? $user->tag }}" placeholder="Твой крутой тэг">

                                            <input type="text" name="tag_color" id="real_tag_color" value="{{ $user->tag_color }}" hidden>
                                        </div>
                                    </div>
                                </div>

                                @if ($errors->has('tag'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('tag') }}</strong>
                                    </div>
                                @endif

                                @if ($errors->has('tag_color'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('tag_color') }}</strong>
                                    </div>
                                @endif

                                <span class="form-text text-muted">
                                    <p>
                                        Твой тэг и его цвет. Можешь писать что угодно (только уложись в 15 символов). Конечно, это может быть и название ВТК.
                                        <strong>Соблюдай <a href="{{ route('rules', [], false) }}">правила сайта</a>!</strong>
                                    </p>
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="option[is_steam_avatar]" class="col-md-2 control-label">Аватар/фон</label>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        @include('user.partials.selects', [
                                            'option' => 'is_steam_avatar',
                                            'label' => 'Steam',
                                            'left_label' => 'TruckersMP'
                                        ])
                                    </div>
                                </div>

                                <span class="form-text text-muted">
                                    <p>
                                        Выбери, какой именно аватар будет использоваться на данном сайте: Steam или TruckersMP.
                                    </p>
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row {{ $errors->has('timezone') ? ' has-danger' : '' }}">
                            <label for="timezone" class="col-md-2 control-label">Часовой пояс</label>

                            <div class="col-md-9">
                                <select class="form-control custom-select" name="timezone" id="timezone" style="width: 100%">
                                    @foreach ($timezones as $region => $tz)
                                        <optgroup label="{{ $region }}">
                                            @foreach ($tz as $value => $timezone)
                                                <option value="{{ $value }}" {{ ($value == $user->timezone) ? 'selected' : '' }}>{{ $timezone }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>

                                @if ($errors->has('timezone'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('timezone') }}</strong>
                                    </div>
                                @endif

                                <span class="form-text text-muted">
                                    <p>Часовой пояс, в котором ты живешь. Время конвоев будет переведено в выбранный тобой часовой пояс.
                                        <strong>Время на сервере: {{ $now->format('H:i') }} (часовой пояс {{ config('app.timezone') }})</strong>
                                    </p>
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row {{ $errors->has('about') ? ' has-danger' : '' }}">
                            <label for="about" class="col-md-2 control-label">О себе</label>

                            <div class="col-md-9">
                                <span class="form-text text-muted">
                                    <p>
                                        Данная информация будет отображена в профиле. <strong>Соблюдай <a href="{{ route('rules', [], false) }}">правила сайта</a>!</strong>
                                    </p>
                                </span>
                                <br>

                                <div class="input-group">
                                    <textarea name="about" id="about" class="form-control">{!! Purifier::clean(old('about') ?? $user->about) !!}</textarea>
                                </div>

                                @if ($errors->has('about'))
                                    <div class="form-control-feedback">
                                        <strong>{{ $errors->first('about') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <legend class="col-form-label text-center">Внешний вид сайта</legend>
                        <br>

                        <div class="row text-center">
                            <div class="col-md">
                                @include('user.partials.selects', [
                                    'option' => 'fluid',
                                    'label' => 'Сайт на всю ширину экрана',
                                    'tooltip' => 'ВНИМАНИЕ! Может работать некорректно либо "ломать" интерфейс!'
                                ])
                            </div>

                            <div class="col-md">
                                @include('user.partials.selects', [
                                    'option' => 'navbar',
                                    'label' => 'Статичное меню навигации',
                                    'tooltip' => 'Навигация не будет "прилипать" к верхнему краю окна'
                                ])
                            </div>

                            <div class="col-md">
                                @include('user.partials.selects', [
                                    'option' => 'rus_names',
                                    'label' => 'Страны и города на русском',
                                    'tooltip' => 'Frederikshavn => Фредериксхавн, и так далее'
                                ])
                            </div>

                            <div class="col-md">
                                @include('user.partials.selects', [
                                    'option' => 'disabled_background',
                                    'label' => 'Отключить фон сайта',
                                    'tooltip' => 'Включи, если сайт или скроллинг тормозят'
                                ])
                            </div>
                        </div>

                        <br>

                        <div class="col-md">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fa fa-save fa-fw"></i> Сохранить настройки
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        @include('user.partials.info', [
            'user' => $user,
            'title' => 'Твои данные <i>(изменить нельзя)</i>',
            'no_about' => true
        ])
    </div>

    <br>

    @if (Auth::user()->is_admin and config('app.env') == 'local')
        <div class="row">
            <div class="col-md">
                <div class="card card-secondary">
                    <div class="card-block">
                        {{ dump($user->getAttributes()) }}
                        {{ dump($user->getRoles()) }}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"></script>

@if ($user->isRole(config('roles.privileged')))
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.5/tinymce.min.js"></script>
@endif

<script type="text/javascript">
    $(document).ready(function () {
        $('select#timezone').chosen({
            placeholder_text_single:         'Выбери значение',
            no_results_text:                 'Ничего не найдено',
            display_disabled_options:        false,
            include_group_label_in_selected: true,
            width:                           '100%'
        });

        $('select#timezone').on('chosen:showing_dropdown', function () {
            $(this).next('.chosen-container').children('.chosen-drop').slideDown(250);
        }).on('chosen:hiding_dropdown', function () {
            $(this).next('.chosen-container').children('.chosen-drop').slideUp(250);
        });

        $('select#tag_color').change(function () {
            $('input#real_tag_color').val($('select#tag_color').val());
            $('select#tag_color').prop('selectedIndex', 0);
        });

        $('input#subscribe').change(function () {
            if (this.checked) {
                $('div#subscribe_input').slideDown(300);
            } else {
                $('div#subscribe_input').slideUp(300);
            }
        })
    });
</script>

@if ($user->isRole(config('roles.privileged')))
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
