<div class="col-sm">
    <div class="card">
        <div class="card-header text-white bg-primary">
            <i class="fa fa-user fa-fw"></i> {!! $title or 'Информация о пользователе' !!}
        </div>

        @if (isset($user->about) and !isset($no_about) and $user->about)
            <div class="card-block">
                <div class="card-text">
                    @if ($user->can('privileged', \App\Models\Convoy::class))
                        {!! Purifier::clean($user->about) !!}
                    @else
                        <p class="text-center">
                            <i>{!! Purifier::clean(strip_tags($user->about)) !!}</i>
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <div class="card-block info-list z-depth-2">
            <div class="row text-center">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 info-list">
                    <div class="list-group">
                        <span class="list-group-item list-group-item-action flex-column">
                            <p class="list-group-item-text">SteamID</p>
                            <h4 class="list-group-item-heading text-fluid">{{ $user->steamid }}</h4>
                        </span>
                        <span class="list-group-item list-group-item-action flex-column">
                            <p class="list-group-item-text">Steam Username</p>
                            <h4 class="list-group-item-heading text-fluid">{{ $user->steam_username }}</h4>
                        </span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="list-group">
                        <span class="list-group-item list-group-item-action flex-column">
                            <p class="list-group-item-text">TruckersMP ID</p>
                            <h4 class="list-group-item-heading text-fluid">{{ $user->truckersmpid }}</h4>
                        </span>
                        <span class="list-group-item list-group-item-action flex-column">
                            <p class="list-group-item-text">TruckersMP Username</p>
                            <h4 class="list-group-item-heading text-fluid">{{ $user->truckersmp_username }}</h4>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @include('snippets.atFooters', ['model' => $user])
    </div>
</div>
