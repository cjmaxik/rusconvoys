<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    {!! SEO::generate() !!}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="Конвои по-русски">
    <meta name="application-name" content="Конвои по-русски">
    <meta name="msapplication-TileColor" content="#ff8800">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#ff8800">

    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">

    @stack('stylesheets')

    <script type="text/javascript">
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        @stack('routes')
    </script>

    <script src="https://use.fontawesome.com/4cde20f30c.js"></script>

    @if (config('app.env') !== 'local')
        @include('layouts.partials.vk_retarget')
        @include('layouts.partials.chatbro')
    @endif

</head>

@php
    if (!isset($background)) {
        $background = null;
    }

    if ($background === 'white') {
        $background_custom = 'white';
        $background = null;
    } elseif ($background === 'gradient') {
        $background_custom = 'brand-gradient';
        $background = null;
    }

    if (!Auth::guest()) {
        if (Auth::user()->getOption('fluid')) {
            $container_type = '-fluid';
        };

        if (Auth::user()->getOption('navbar')) {
            $navbar_type = 'static';
            $body_type = '';
        };

        if (Auth::user()->getOption('disabled_background')) {
            $background = null;
        }
    }
@endphp

<body class="{{ $body_type or 'fixed' }} {{ $background_custom or '' }}">

@if ($background)
    <img src="{{ $background }}" class="bg">
@endif

@include('layouts.nav')

<div class="container{{ $container_type or '' }}">
    <p class="text-right">
        <span class="badge badge-default badge-normal">
            Часовой пояс: <strong>{{ Auth::check() ? Auth::user()->timezone : config('app.timezone') }}</strong>
        </span>

        @if (config('app.stop_cron'))
            <span class="badge badge-danger">
                <strong>CRON</strong>
            </span>
        @endif

        @if (config('app.stop_jobs'))
            <span class="badge badge-danger">
                <strong>JOBS</strong>
            </span>
        @endif
    </p>

    <div class="alert card-danger text-center z-depth-1 mb-1 fade show" role="alert">
        <span class="white-text mb-0">
            <strong>Проект "Конвои по-русски" будет закрыт 31 августа 2017 года.</strong><br> Спасибо за то, что были с нами.</a>
        </span>
    </div>

    @yield('content')
</div>

@include('layouts.partials.footer')

{{--Scripts--}}
<script src="{{ elixir('js/app.js') }}"></script>
@stack('scripts')
<script type="text/javascript">
    NProgress.start();
</script>

@include('layouts.partials.alert')

@if (config('app.env') !== 'local')
    @include('layouts.partials.ga')
    @include('layouts.partials.yandex')
@endif

</body>
</html>
