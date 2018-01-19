@php
    /** @var \App\Models\Comment $comment */
    /** @var \App\Models\Convoy $convoy */
    /** @var \Illuminate\Support\MessageBag $errors */
@endphp

<template id="deleted-comment">
    <div class="text-center grey-text">
        <small><em>Комментарий удален</em></small>
    </div>
</template>

@forelse ($comments as $comment)
    <div class="media comment {{ $comment->deleted_at ? 'bg-deleted grey lighten-2 text-black' : ''}}" id="comment{{ $comment->id }}" name="comment{{ $comment->id }}">
        <div class="media-left hidden-sm-down">
            <img class="img-fluid rounded-circle z-depth-2" src="{{ $comment->user->avatar }}" alt="{{ $comment->user->nickname }}">
        </div>
        <div class="media-body">
            <h4 class="media-heading">
                @include('user.partials.tagUsername', ['user' => $comment->user, 'with_role' => true])
            </h4>

            <p class="comment_{{$comment->id}}_text autolink">{{ $comment->text }}</p>

            @php
                $dateTime = $comment->deleted_at ? $comment->dateLoc('deleted_at') : $comment->dateLoc('created_at');
            @endphp

            <div class="text-right">
                <small>
                    <em>
                        @include('snippets.dateTime', ['date' => $dateTime, 'no_date' => true])
                    </em>
                </small>

                @if (!$comment->deleted_at)
                    @can('update', $comment)
                        <button type="button" class="btn btn-warning btn-sm update-comment" data-id="{{ $comment->id }}" data-toggle="tooltip" data-title="Редактировать комментарий" id="commentEdit">
                            <i class="fa fa-pencil fa-fw"></i>
                        </button>
                    @endcan

                    @can('delete', $comment)
                        <button type="button" class="btn btn-danger btn-sm delete-comment" data-id="{{ $comment->id }}" data-toggle="tooltip" data-title="Удалить комментарий" id="commentButtonSumbit">
                            <i class="fa fa-times fa-fw"></i>
                        </button>
                    @endcan
                @endif
            </div>
        </div>
    </div>

    @if (!$loop->last)
        <hr class="small">
    @endif
@empty
    @if (!in_array($convoy->status, ['closed', 'draft', 'cancelled']))
        <p class="text-fluid text-center">Тут одиноко 😞 <em>Будешь первым?</em></a></p>
    @else
        <p class="text-fluid text-center">Тут всё равно было одиноко 😭</a></p>
    @endif
@endforelse

<div class="modal fade" id="modal-updateComment">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h4 class="modal-title">Редактировать комментарий</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <div class="md-form form-group">
                        <textarea class="form-control form-control-lg" id="updatedCommentText"></textarea>
                    </div>
                    <br>
                    <div class="md-form form-group">
                        <button type="button" id="updateCommentSubmit" class="btn btn-danger btn-block">
                            Обновить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if (Session::has('comment.id'))
    @push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var hash = '#comment{{ Session::get('comment.id') }}';

            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function () {
                window.location.hash = hash;
            });

            $('textarea').each(function () {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('focus', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            }).on('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
    @endpush
@endif
