@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('favicon-16x16.png') }}" alt="Icon"> {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.<br><br>
            <strong>Немного официоза:</strong> вы получили данное письмо потому, что указали свой E-mail на сайте "{{ config('app.name') }}". <br>Если это сделали не вы, обратитесь в нашу поддержку.
        @endcomponent
    @endslot
@endcomponent
