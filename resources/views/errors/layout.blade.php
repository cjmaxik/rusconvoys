<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>{{ $error }} | {{ config('app.name') }}</title>

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

    <link href="https://fonts.googleapis.com/css?family=Roboto:100&amp;subset=cyrillic" rel="stylesheet"
          type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            background-color: {{ $background_color or '#CC0000' }};
            margin:      0;
            padding:     0;
            width:       100%;
            color: {{ $text_color or '#ff4444' }};
            display:     table;
            font-weight: 100;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            text-align:     center;
            display:        table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display:    inline-block;
            width:      95%;
        }

        @media (min-width: 769px) {
            .content {
                width: 60%;
            }
        }

        .title {
            font-size: 5em;
        }

        .lead {
            font-style: italic;
            color: {{ $text_color or '#ff4444' }};
            font-size:  2em;
        }

        .lead > a {
            color: {{ $text_color or '#ff4444' }};
        }
    </style>
</head>

<body>
<div class="container">
    <div class="content">
        <div class="title">
            «{{ $titles[mt_rand(0, count($titles) - 1)] }}»
        </div>

        <p class="lead">
            {{ $back_message or $error }}
            <br><br>
            @if (!isset($no_link))
                <a href="{{ url('/') }}">
                    <small>Вернуться на главную</small>
                </a>
            @endif

            @if (isset($social))
                <br>
                <a href="https://vk.com/rusconvoys" target="_blank" rel="noreferrer nofollow noopener">
                    <small>Группа ВКонтакте</small>
                </a>
            @endif
        </p>
    </div>
</div>


@if (config('app.env') !== 'local')
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-41586854-10', 'auto');
        ga('send', 'pageview');
    </script>
@endif
</body>
</html>
