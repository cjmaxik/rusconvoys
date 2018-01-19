@if (isset($is_page))
    <a href="#">
        {{ $notification->data['title'] }} отменен!

        <span class="badge badge-primary pull-right">
			@include('snippets.dateTime', ['date' => timeLoc($notification->created_at), 'no_date' => true])
		</span>
    </a>

    @if (!$loop->last)
        <hr class="small">
    @endif
@else
    <a href="#" class="dropdown-item notification">
        {{ $notification->data['title'] }} отменен с причиной "{{ $notification->data['message'] }}"
        <br>
        <span class="badge badge-primary pull-right">
			@include('snippets.dateTime', ['date' => timeLoc($notification->created_at), 'no_date' => true])
		</span>
    </a>

    @if (!$loop->last)
        <hr class="small equal">
    @endif
@endif
