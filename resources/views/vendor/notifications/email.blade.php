@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Ой-ой-ой!
@else
# Привет!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@if (isset($actionText))
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endif

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

<!-- Salutation -->
@if (! empty($salutation))
{{ $salutation }}
@else
С уважением, <br>администрация сайта "{{ config('app.name') }}".
@endif

<!-- Subcopy -->
@if (isset($actionText))
@component('mail::subcopy')
Если вы не видите кнопку "{{ $actionText }}", скопируйте и перейдите по данной ссылке: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endif
@endcomponent
