@php
    /** @var Jenssegers\Date\Date $date */
@endphp

@spaceless
@php
    if ($date->year != Jenssegers\Date\Date::now()->year) {
        $formattedDate = $date->format(trans('app.datetime_year_format'));
    } else {
        $formattedDate = $date->format(trans('app.datetime_format'));
    }
@endphp

@if(isset($home))
    @if($date->diffInHours(Jenssegers\Date\Date::now()) <= 24)
        @php
            $no_date = true;
            $ago = true;
        @endphp
    @endif
@endif

@if (!isset($no_date) or $no_date == false)
    {{ $formattedDate }}
@else
    @php
        $ago = true
    @endphp
@endif

@if (isset($ago))
    @if (isset($no_date))
        <span class="date-tooltip" data-toggle="tooltip" data-placement="top" title="{{ $text or '' }} {{ $formattedDate }}">&asymp; {{ $date->diffForHumans() }}</span>
    @else
        <small>(&asymp; {{ $date->diffForHumans() }})</small>
    @endif
@endif
@endspaceless
