@php
    /** @var \App\Models\Convoy $convoy */

    if ($archive) {
        $no_date = false;
    } else {
        $no_date = true;
    }
@endphp

<div class="table-responsive">
    <table class="table table-sm table-striped table-hover">
        <thead class="thead-default">
            <tr>
                <th>Название конвоя</th>
                <th>Маршрут</th>
                <th>
                    <i class="fa fa-users"></i>
                </th>
                <th>Организатор</th>
                <th>Сбор</th>
                <th>Отправление</th>
                <th>Сервер</th>
                <th>Статус</th>
            </tr>
        </thead>

        <tbody class="text-center">
            @foreach($convoys as $convoy)
                <tr class="{{ ($convoy->pinned) ? 'yellow' : '' }} hoverable">
                    <td {{ $convoy->title ? '' : 'colspan=2' }}>
                        <a href="{{ route('convoy_show', ['slug' => $convoy->slug], false) }}">
                            @if ($convoy->pinned)
                                <i class="fa fa-star"></i>
                            @endif
                            {{ $convoy->getNormTitle() }}
                        </a>

                        @if (count($convoy->dlcs))
                            <span>
                                <i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Необходимы DLC"></i>
                            </span>
                        @endif
                    </td>

                    @if ($convoy->title)
                        <td>
                            {{ $convoy->getRoute() }}
                        </td>
                    @endif

                    <td>
                        {{ $convoy->participations_count }}
                    </td>

                    <td>
                        @include('user.partials.tagUsername', ['user' => $convoy->user])
                    </td>

                    <td>
                        @include('snippets.dateTime', ['date' => $convoy->dateLoc('meeting_datetime'), 'no_date' => $no_date])
                    </td>
                    <td>
                        @include('snippets.dateTime', ['date' => $convoy->dateLoc('leaving_datetime'), 'no_date' => $no_date])
                    </td>

                    <td>
                        {{ $convoy->getServerName(true) }}
                    </td>

                    <td>
                        {{ trans('convoys.status.'.$convoy->status.'_short') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="row">
    <div class="col-md-6">
        {{ $convoys->links() }}
    </div>

    <div class="col-md-6 text-right">
        Показано {{ $convoys->firstItem() }} - {{ $convoys->lastItem() }} из {{ $convoys->total() }}
    </div>
</div>
