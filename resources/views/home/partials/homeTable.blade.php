<ul class="collapsible popout" data-collapsible="accordion">
    @foreach($convoys as $convoy)
        <li>
            <div class="collapsible-header {{ ($convoy->pinned) ? 'pinned' : '' }} hoverable">
                @if ($convoy->pinned)
                    <i class="fa fa-star fa-fw"></i>
                @endif

                <span class="title">
                    <strong>{{ $convoy->getNormTitle() }}</strong> | {{ $convoy->getServerName(true) }}
                </span>

                <span class="participations pull-right">
                    <i class="fa fa-users"></i> {{ $convoy->participations_count }}
                </span>
            </div>

            <div class="collapsible-body">
                <div class="row">
                    <div class="col-md">
                        <p>
                            <strong>Организатор: </strong> @include('user.partials.tagUsername', ['user' => $convoy->user])
                        </p>

                        @if ($convoy->title)
                            <p>
                                <strong>Маршрут: </strong> {{ $convoy->getRoute() }}
                            </p>
                        @endif

                        <p>
                            @if (isset($meeting))
                                <strong>Сбор: </strong> @include('snippets.dateTime', ['date' => $convoy->dateLoc('leaving_datetime'), 'no_date' => true])
                            @else
                                <strong>Отправление: </strong> @include('snippets.dateTime', ['date' => $convoy->dateLoc('meeting_datetime'), 'no_date' => true])
                            @endif
                        </p>

                        <p>
                            <strong>Сервер: </strong> {{ $convoy->getServerName() }}
                        </p>

                        @if ($convoy->start_town->dlc->name !== 'base' or $convoy->finish_town->dlc->name !== 'base')
                            <p>
                                <strong>Необходимы DLC</strong>
                            </p>
                        @endif

                        <p>
                            <strong>Статус: </strong> {{ trans('convoys.status.'.$convoy->status) }}
                        </p>
                    </div>

                    @if ($convoy->map_url)
                        <div class="col-md hidden-xs-down">
                            <img class="rounded img-fluid" src="{{ $convoy->map_url_safe or $convoy->map_url }}"
                                 alt='Карта конвоя "{{ $convoy->getNormTitle() }}"'>
                        </div>
                    @endif
                </div>

                <br>

                <a href="{{ route('convoy_show', ['slug' => $convoy->slug], false) }}"
                   class="btn btn-danger btn-block btn-lg waves-effect waves-light">
                    Информация о конвое
                </a>
            </div>
        </li>
    @endforeach
</ul>
