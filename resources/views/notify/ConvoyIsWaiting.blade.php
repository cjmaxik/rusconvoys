@if (isset($is_page))
	<a href="{{ route('convoy_show', ['slug' => $notification->data['slug']]) }}">
		{{ $notification->data['title'] }} состоится совсем скоро!

		<span class="badge badge-primary pull-right">
			@include('snippets.dateTime', ['date' => timeLoc($notification->created_at), 'no_date' => true])
		</span>
	</a>

	@if (!$loop->last)
		<hr class="small">
	@endif
@else
	<a href="{{ route('convoy_show', ['slug' => $notification->data['slug']]) }}" class="dropdown-item notification">
		{{ $notification->data['title'] }} состоится совсем скоро!
		<br>
		<span class="badge badge-primary pull-right">
			@include('snippets.dateTime', ['date' => timeLoc($notification->created_at), 'no_date' => true])
		</span>
	</a>

	@if (!$loop->last)
		<hr class="small equal">
	@endif
@endif
