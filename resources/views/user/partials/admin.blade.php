@php
    /** @var \App\Models\User $user */
@endphp

<input type="hidden" id="userid" value="{{ $user->id }}">

<div class="row admin-buttons">
    <div class="col text-center">
        <div class="btn-group" role="button">
            <a data-toggle="modal" href='#modal-role' class="btn btn-success btn-lg">
                Выдать роль{{ ($user->isRole('banned')) ? ' и разбанить' : '' }}
            </a>
            <a data-toggle="modal" href='#modal-change' class="btn btn-warning btn-lg">
                Изменить данные
            </a>
            @unless ($user->isRole('banned'))
                <a data-toggle="modal" href='#modal-ban' class="btn btn-danger btn-lg">
                    Забанить
                </a>
            @endunless
        </div>
    </div>
</div>

<br>

<div class="modal fade" id="modal-role">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h4 class="modal-title">Выдать роль</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <div class="md-form form-group">
                        <select class="form-control form-control-lg text-center" id="role_choose">
                            @foreach (HttpOz\Roles\Models\Role::whereNotIn('slug', ['banned', $user->role->slug])->get() as $role)
                                @if ($role->slug === 'admin')
                                    @continue
                                @endif
                                <option value="{{ $role->slug }}" {{ $role->slug === $user->most_role ? 'selected disabled' : '' }}>
                                    {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md-form form-group">
                        <button type="button" id="role_change" class="btn btn-danger">Do it!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ban">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h4 class="modal-title">Забанить</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <div class="md-form form-group">
                        <input class="form-control" id="ban_message" type="text" length="50">
                        <label for="ban_message">Причина</label>
                    </div>
                    <div class="md-form form-group">
                        <button type="button" id="ban_user" class="btn btn-danger">Do it!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-change">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h4 class="modal-title">Изменить данные</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <div class="md-form form-group">
                        <input class="form-control" id="tag" type="text" length="50" value="{{ $user->tag }}">
                        <label for="tag">Тэг</label>
                    </div>
                    <div class="md-form form-group">
                        <input class="form-control" id="nickname" type="text" length="50" value="{{ $user->nickname }}">
                        <label for="nickname">Ник</label>
                    </div>
                    <div class="md-form form-group">
                        <button type="button" id="change_user" class="btn btn-danger">Do it!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('routes')
var URL_admin_role_change = '{{ route('admin.role_change', [], false) }}';
var URL_admin_ban_user = '{{ route('admin.ban_user', [], false) }}';
var URL_admin_change_user = '{{ route('admin.change_user', [], false) }}';
@endpush

@push('scripts')
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#role_change').on('click', function (event) {
            event.preventDefault();

            var user_id = $('#userid').val();
            var new_role = $('#role_choose').val();

            $.ajax({
                url:     URL_admin_role_change,
                type:    'POST',
                data:    {
                    new_role: new_role,
                    user_id:  user_id,
                    _token:   window.Laravel.csrfToken
                },
                success: function (data) {
                    window.location.reload();
                }
            })
        });

        $('#ban_user').on('click', function (event) {
            event.preventDefault();

            var user_id = $('#userid').val();
            var message = $('#ban_message').val();

            $.ajax({
                url:     URL_admin_ban_user,
                type:    'POST',
                data:    {
                    message: message,
                    user_id: user_id,
                    _token:  window.Laravel.csrfToken
                },
                success: function (data) {
                    window.location.reload();
                }
            })
        });

        $('#change_user').on('click', function (event) {
            event.preventDefault();

            var user_id = $('#userid').val();
            var nickname = $('#nickname').val();
            var tag = $('#tag').val();

            $.ajax({
                url:     URL_admin_change_user,
                type:    'POST',
                data:    {
                    user_id: user_id,
                    nickname: nickname,
                    tag: tag,
                    _token:  window.Laravel.csrfToken
                },
                success: function (data) {
                    window.location.href = '/profile/' + data;
                }
            })
        });
    });
</script>
@endpush
