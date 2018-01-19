@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (isset($title))
                <h1 class="display-5 text-center">{{ $title }}</h1>
            @endif

            @if (count($convoys))
                @include('convoy.partials.convoysTable', [
                    'convoys' => $convoys,
                    'archive' => isset($archive) ? $archive : false
                ])
            @else
                <p class="text-center">
                    Нет конвоев. <a class="btn btn-primary" href="{{ route('convoy.new.show', [], false) }}">
                        Создать?
                    </a> <a class="btn btn-secondary" href="{{ route('convoy_archive', [], false) }}">Архив конвоев</a>
                </p>
            @endif
        </div>
    </div>
@endsection
