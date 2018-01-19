@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
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
            © {{ date('Y') }} {{ config('app.name') }}. Все права защищены.<br><br>
            **Немного официоза:** вы получили данное письмо потому, что указали свой E-mail на сайте "{{ config('app.name') }}". Если это сделали не вы, обратитесь в нашу поддержку.
        @endcomponent
    @endslot
@endcomponent
