@php
    if ($id === $current) {
        $class = 'active';
    } elseif ($id > $current) {
        $class = '';
    } else {
        $class = 'done';
    }
@endphp

<div class="col-md {{ $class }} step">
    @if ($class === 'active')
        <h4 class="hidden-xs-down">
            <span class="badge badge-full badge-{{ $step['color'] }}">
                {{ trans('convoys.status.' . $step['status']) }}
            </span>
        </h4>
        <span class="hidden-sm-up badge badge-full badge-{{ $step['color'] }}">
            {{ trans('convoys.status.' . $step['status']) }}
        </span>
    @else
        {{ trans('convoys.status.' . $step['status'] . '_short') }}
    @endif
</div>
