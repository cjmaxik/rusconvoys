@if (!in_array($convoy->status, ['closed', 'draft', 'cancelled']))
    <div class="card-footer">
        <form role="form" method="POST" id="comments_newPost" action="{{ route('comments_newPost', [], false) }}">
            {{ csrf_field() }}

            <div class="media comment gray">
                <a class="media-left hidden-sm-down" href="#">
                    <img class="rounded-circle" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->nickname }}">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">
                        @include('user.partials.tagUsername', ['user' => Auth::user()])
                    </h4>

                    <div class="md-form">
                        <input type="text" name="convoy_id" id="convoy_id" value="{{ $convoy->id }}" hidden>
                        <textarea type="text" name="comment_text" id="text" class="md-textarea" maxlength="200" minlength="10" required></textarea>
                        <label for="convoy_id" class="lower">Твой комментарий (минимум 10 символов)</label>

                        @if ($errors->has('comment_text'))
                            <div class="form-control-feedback">
                                <strong>{{ $errors->first('comment_text') }}</strong>
                            </div>
                        @endif
                    </div>

                    <div class="text-right" style="display: none;">
                        <button class="btn btn-primary" id="comments_new" type="submit">
                            Отправить <i class="fa fa-paper-plane right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('routes')
    URL_comment_deletePost = '{{ route('comment_deletePost', [], false) }}';
    @endpush
@endif
