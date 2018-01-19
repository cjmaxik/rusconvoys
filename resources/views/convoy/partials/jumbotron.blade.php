@php
    /** @var \App\Models\Convoy $convoy */
@endphp

<div class="jumbotron text-center custom-jumbotron text-white">
    <div class="container user-jumbotron-text">
        <div class="col-md-12 text-center ">

            <span class="title">
                @if ($convoy->pinned)
                    <i class="fa fa-star fa-3x" data-toggle="tooltip" data-placement="bottom" title="Данный конвой отмечен администрацией"></i>
                @endif

                @if (!$convoy->title)
                    <h1 class="display-3 hidden-sm-down">{{ $convoy->start_town->name }} - {{ $convoy->finish_town->name }}</h1>
                    <h1 class="display-5 hidden-md-up">{{ $convoy->start_town->name }} - {{ $convoy->finish_town->name }}</h1>
                @else
                    <h1 class="display-3 hidden-sm-down"><strong>{{ $convoy->title }}</strong></h1>
                    <h1 class="display-5 hidden-md-up"><strong>{{ $convoy->title }}</strong></h1>
                    <h3>{{ $convoy->start_town->name }} - {{ $convoy->finish_town->name }}</h3>
                @endif
            </span>

            <p class="lead">
                <span class="hidden-sm-down">Организатор: </span>@include('user.partials.tagUsername', ['user' => $convoy->user, 'rating' => true])
            </p>
            <p class="lead">
                {{ trans_choice('convoys.participations_count', $convoy->participations_count) }}
            </p>

            @if (in_array($convoy->status, ['draft', 'cancelled']))
                <h4>
                    <span class="badge badge-full badge-{{ $state }}">
                        @if ($convoy->status === 'cancelled')
                            {{ trans('convoys.status.'.$convoy->status) }}<br><em>{{ $convoy->cancelled_message }}</em>
                        @elseif ($convoy->status === 'draft')
                            {{ trans('convoys.status.'.$convoy->status) }}
                        @endif
                    </span>
                </h4>
            @else
                <br>

                @php
                    $steps = [
                        0 => [
                            'status' => 'open',
                            'color' => 'primary'
                        ],
                        1 => [
                            'status' => 'meeting',
                            'color' => 'warning'
                        ],
                        2 => [
                            'status' => 'on_way',
                            'color' => 'danger'
                        ],
                        3 => [
                            'status' => 'voting',
                            'color' => 'primary'
                        ],
                        4 => [
                            'status' => 'closed',
                            'color' => 'default'
                        ],
                    ];

                    $current = array_search($convoy->status, array_column($steps, 'status'));
                @endphp

                <div class="row stepper">
                    @foreach ($steps as $key => $step)
                        @include('convoy.partials.stepper', [
                            'current' => $current,
                            'id' => $key,
                            'step' => $step
                        ])
                    @endforeach
                </div>

            @endif
        </div>
    </div>
</div>


