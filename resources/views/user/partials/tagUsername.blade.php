@php
    /** @var \App\Models\User $user */
@endphp

@spaceless
<span class="role role-{{ $user->role->slug }}">
	@if ($user->tag)
        <span class="badge {{ $user->tag_color ?: 'blue' }} role-tag">{{ $user->tag }}</span>
    @endif

    @if (!isset($no_link)) <a href="{{ route('profile_page', ['slug' => $user->slug], false) }}" target="_blank"> @endif
        <span class="role-nickname"> {{ $user->nickname }} @if (isset($with_role) and !$user->isRole('user')) <small class="role-name">{{ $user->role->description }}</small> @endif </span>
    @if (!isset($no_link)) </a> @endif
</span>
@endspaceless

