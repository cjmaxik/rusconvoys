@php
    /** @var \Yajra\Datatables\Html\Builder $html */
@endphp

@extends('layouts.app')

@section('content')
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="{{ route('index') }}">Главная</a>
        <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Панель управления</a>
        <span class="breadcrumb-item active">
            <i class="fa fa-{{ $icon or 'table'}} fa-fw left"></i> {{ $title or 'Таблица' }}
        </span>
    </nav>

    <h2 class="text-center">
        <i class="fa fa-{{ $icon or 'table'}} fa-fw left"></i> {{ $title or 'Таблица' }}
    </h2>

    <hr>

    <div class="container">
        {!! $html->table(['class' => 'table table-small table-hover', 'style' => 'width: 100%;'], true) !!}
    </div>
@stop

@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/dataTables.bootstrap4.min.js"></script>
{!! $html->scripts() !!}
@endpush