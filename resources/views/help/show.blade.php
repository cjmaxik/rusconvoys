@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col">
            <div class="jumbotron text-white brand-gradient">
                <div class="container text-center">
                    <h1>Помощь по сайту <b>"{{ config('app.name') }}"</b></h1><br><i>Последнее обновление: <b>24.03.2017 г.</b></i>
                </div>
            </div>

            <div class="card card-outline text-center">
                <div class="card-block">
                    <p class="card-text">
                        Здесь находятся ответы на часто задаваемые вопросы по сайту. Раздел постоянно поплняется.<br>
                        Если вы не нашли ответа на свой вопрос, обратитесь к нам <a href="https://vk.com/rusconvoys" target="_blank" rel="noreferrer nofollow noopener">в личных сообщениях группы
                                                                                                                                                                        ВКонтакте</a> или на E-mail
                        <kbd>wtf (at) rusconvoys.ru</kbd>
                    </p>
                </div>
            </div>

            <hr>

            @php
                $faq = [
                    [
                        'title' => 'Общие вопросы',
                        'questions' => [
                            ['title' => 'Как зарегистрироваться на сайте?', 'text' => '1.1'],
                        ],
                    ],
                    [
                        'title' => 'Конвои',
                        'questions' => [
                            ['title' => 'Как создать конвой?', 'text' => '2.1'],
                            ['title' => 'Почему мой конвой не видят другие? Как опубликовать конвой?', 'text' => '2.2'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($faq as $segment)
                <div class="card card-default">
                    <div class="card-header {{ $loop->iteration % 2 == 0 ? 'brand-gradient' : 'brand-gradient-rev' }} text-white text-center">
                        {{ $loop->iteration }}. {{ $segment['title'] }}
                    </div>
                </div>

                <br>

                <ul class="collapsible popout" data-collapsible="accordion">
                    @foreach ($segment['questions'] as $question)
                        <li>
                            <div class="collapsible-header hoverable">
                                <span class="title">
                                    <b>{{ $loop->parent->iteration }}.{{ $loop->iteration }}.</b> {{ $question['title'] }}
                                </span>
                            </div>

                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="col-md faq">
                                        @include('help.questions.' . $question['text'])
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach
        </div>
    </div>
@endsection
