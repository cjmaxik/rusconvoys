@if (isset($is_page))
    <a href="{{ route('convoy_show', ['slug' => $notification->data['slug']]) }}#commentsForConvoy">
        Новый комментарий к конвою "{{ $notification->data['title'] }}"

        <span class="badge badge-primary pull-right">
			@include('snippets.dateTime', ['date' => timeLoc($notification->created_at), 'no_date' => true])
		</span>
    </a>

    @if (!$loop->last)
        <hr class="small">
    @endif
@else
    <a href="{{ route('convoy_show', ['slug' => $notification->data['slug']]) }}" class="dropdown-item notification">
        Новый комментарий к конвою "{{ $notification->data['title'] }}#commentsForConvoy"
        <br>
        <span class="badge badge-primary pull-right">
			@include('snippets.dateTime', ['date' => timeLoc($notification->created_at), 'no_date' => true])
		</span>
    </a>

    @if (!$loop->last)
        <hr class="small equal">
    @endif
@endif
