@php
    /** @var \App\Models\Country $country */
    /** @var \App\Models\City $city */
    /** @var \App\Models\DLC $dlc */
@endphp

@extends('layouts.app')

@section('content')
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="{{ route('index') }}">Главная</a>
        <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Панель управления</a>
        <span class="breadcrumb-item active">
            <i class="fa fa-table fa-fw left"></i> Константы
        </span>
    </nav>

    <h2 class="text-center">
        <kbd>Ctrl</kbd> + <kbd>F</kbd>, чтобы найти нужную страну
    </h2>

    <hr>

    @foreach ($countries->chunk(4) as $chunk)
        <div class="row">
            @foreach ($chunk as $country)
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <div class="list-group">
                        <div class="list-group-item justify-content-between active">
                            <span>{{ $country->name }}</span>
                            <span>{{ $country->rus_name }}</span>
                            <span class="badge white text-primary badge-pill">{{ $country->id }}</span>
                        </div>

                        @foreach ($country->cities as $city)
                            <div class="list-group-item justify-content-between">
                                <span>{{ $city->name }}</span>
                                <span>{{ $city->rus_name }}</span>
                            </div>
                        @endforeach

                        <div class="list-group-item list-group-item-warning">
                            <button class="btn btn-primary btn-xs btn-block" data-toggle="modal" data-target="#addCityModal"
                                    data-country-id="{{ $country->id }}">
                                Новый город
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <br>
    @endforeach

    <div class="modal fade" id="addCityModal" tabindex="-1" role="dialog" aria-labelledby="addCityLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCityLabel">Добавление города</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="country_id" class="form-control-label">Страна:</label>
                        <select id="country_id" class="custom-select">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->rus_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-control-label">Оригинальное название <code><strong>SII:</strong> city_data.city_name</code>:</label>
                        <input type="text" class="form-control" id="name">
                    </div>

                    <div class="form-group">
                        <label for="rus_name" class="form-control-label">Название на русском:</label>
                        <input type="text" class="form-control" id="rus_name">
                    </div>

                    <div class="form-group">
                        <label for="dlc_id" class="form-control-label">DLC:</label>
                        <select id="dlc_id" class="custom-select">
                            @foreach ($dlcs as $dlc)
                                <option value="{{ $dlc->id }}">{{ $dlc->screen_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" id="addCityButton">Добавить</button>
                </div>
            </div>
        </div>
    </div>
@stop

@push('routes')
var URL_addCity = '{{ route('admin.constant.addCity', [], false) }}';
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#addCityModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var country_id = button.data('country-id');
            var modal = $(this);

            modal.find('select#country_id').val(country_id);
        })

        $('button#addCityButton').click(function (e) {
            var country_id = $('select#country_id').val();
            var name = $('input#name').val();
            var rus_name = $('input#rus_name').val();
            var dlc_id = $('select#dlc_id').val();

            if (!confirm('Проверь еще раз все данные!')) {
                return;
            }

            $.ajax({
                url:     URL_addCity,
                type:    'POST',
                data:    {
                    country_id: country_id,
                    name:       name,
                    rus_name:   rus_name,
                    dlc_id:     dlc_id,
                    _token:     window.Laravel.csrfToken
                },
                success: function (data) {
                    $('#addCityModal').modal('hide');

                    if (data === 'OK') {
                        swal({
                            title:               'Ура!',
                            html:                "Город добавлен!<hr>Хочешь добавить еще один или обновим страницу?",
                            type:                'warning',
                            showLoaderOnConfirm: true,
                            showCancelButton:    true,
                            showLoaderOnConfirm: true,
                            confirmButtonColor:  "#DD6B55",
                            confirmButtonText:   "Обновим",
                            cancelButtonText:    "Добавим еще!",
                            confirmButtonClass:  'btn btn-primary',
                            cancelButtonClass:   'btn btn-danger',
                            customClass:         'modal-content',
                            buttonsStyling:      false,
                        }).then(function () {
                            window.location.reload();
                        })
                    }
                }
            });
        });
    });
</script>
@endpush