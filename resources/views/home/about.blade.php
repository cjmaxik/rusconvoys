@extends('layouts.app')

@section('content')
    <div class="container white-text">
        <div class="text-center">
            <h1 class="display-3">
                <b><i class="fa fa-truck fa-fw"></i> {{ config('app.name') }}</b>
            </h1>
            <br>
            <p>На&nbsp;данном сайте ты&nbsp;можешь найти предстоящие конвои в&nbsp;TruckersMP -<br>неофициальном мультиплеере для Euro Truck Simulator 2&nbsp;и&nbsp;American Truck Simulator</p>
        </div>

        <hr>

        {{--<div class="embed-responsive embed-responsive-16by9">--}}
        {{--<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/l0EG8lv_hL0" allowfullscreen></iframe>--}}
        {{--</div>--}}

        {{--<hr>--}}

        <div class="text-center">
            <h2 class="display-4">
                <strong>{{ config('app.name') }}</strong> - это:
            </h2>
            <br>

            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <i class="fa fa-users fa-5x" aria-hidden="true"></i>
                    <br>
                    <br>
                    <h3>{{ trans_choice('convoys.users_count', $users_count) }}</h3>
                </div>
                <div class="col-xs-12 col-md-4">
                    <i class="fa fa-truck fa-5x" aria-hidden="true"></i>
                    <br>
                    <br>
                    <h3>{{ trans_choice('convoys.convoys_count', $convoys_count) }}</h3>
                </div>
                <div class="col-xs-12 col-md-4">
                    <i class="fa fa-comments fa-5x" aria-hidden="true"></i>
                    <br>
                    <br>
                    <h3>{{ trans_choice('convoys.comments_count', $comments_count) }}</h3>
                </div>
            </div>
        </div>

        <hr>

        <section class="section extra-margins">
            <h1 class="section-heading text-center">Особенности проекта</h1>

            <div class="row">
                <div class="col-md-5 mb-r">
                    <div class="view overlay hm-white-slight">
                        <img src="{{ url('/pics/about/1.png') }}">
                    </div>
                </div>

                <div class="col-md-7 mb-r">
                    <a href="" class="amber-text"><h5><i class="fa fa-diamond"></i>Современно</h5></a>
                    <h4>Material Design&nbsp;&mdash; для твоего удобства!</h4>
                    <p>Вдохновленный системой дизайна Material от&nbsp;Google, дизайн сайта является современным, отзывчивым и&nbsp;просто удобным для повседневного использования.</p>
                    <p><strong>Запутаться невозможно!</strong></p>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-7 mb-r">
                    <h5 class="amber-text"><i class="fa fa-coffee"></i>Просто</h5>
                    <h4>Найти конвой&nbsp;&mdash; просто как никогда!</h4>
                    <p>Проект аккумулирует в&nbsp;себе все необходимые свойства для легкого поиска конвоя. Забудь о&nbsp;бесконечных походах на&nbsp;форумы, в&nbsp;группы и&nbsp;голосовые чаты.</p>
                    <p><strong>Всё нужное&nbsp;&mdash; здесь и&nbsp;сейчас!</strong></p>
                </div>

                <div class="col-md-5 mb-r">
                    <div class="view overlay hm-white-slight">
                        <img src="{{ url('/pics/about/2.png') }}">
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-5 mb-r">
                    <div class="view overlay hm-white-slight">
                        <img src="{{ url('/pics/about/3.png') }}">
                    </div>
                </div>

                <div class="col-md-7 mb-r">
                    <h5 class="amber-text"><i class="fa fa-suitcase"></i>Удобно</h5>
                    <h4>Ты&nbsp;&mdash; создатель конвоя? Тебе очень повезло!</h4>
                    <p>Чтобы создать красивую и&nbsp;удобную страницу конвоя, больше не&nbsp;нужно часами сидеть в&nbsp;фоторедакторе и&nbsp;делать коллаж для соцсети, а&nbsp;также вспоминать, какая именно информация нужна участникам. Страница конвоя скажет всё за&nbsp;тебя!</p>
                    <p><strong>Конвои&nbsp;&mdash; двигатель TruckersMP!</strong></p>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-7 mb-r">
                    <h5 class="amber-text"><i class="fa fa-server"></i>Приятно</h5>
                    <h4>Самая классная аудитория Рунета!</h4>
                    <p>На&nbsp;сайте собирается дружелюбная и&nbsp;отзывчивая аудитория. Администрация сайта всегда поддержит и&nbsp;поможет с&nbsp;любым вопросом. Здесь принято быть вежливым и&nbsp;открытым!</p>
                    <p><strong>Люди&nbsp;&mdash; на&nbsp;вес золота!</strong></p>
                </div>

                <div class="col-md-5 mb-r">
                    <div class="view overlay hm-white-slight">
                        <img src="{{ url('/pics/about/4.png') }}">
                    </div>
                </div>
            </div>
        </section>

        <hr>

        @if (Auth::guest())
            <div class="row">
                <div class="col">
                    <div class="white-text text-center">
                        <p class="display-4">Ну&nbsp;что, заинтересован?</p>
                        <br>
                        <a href="/login" id="login-button"
                           class="nav-item nav-link bg-success hoverable login-button waves-effect waves-light text-white">
                            <i class="fa fa-steam fa-fw left" aria-hidden="true"></i> Заходи на&nbsp;сайт прямо сейчас!
                        </a>
                        <br>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-right">
            <p>
                <em>* Примерные данные, актуальны на&nbsp;{{ $timedate }}</em>
            </p>
        </div>
    </div>
@endsection
