<div class="jumbotron text-center custom-jumbotron text-white">
    <div class="container user-jumbotron-text">
        <div class="col-md">
            @if (!isset($no_pics))
                <div class="row">
                    <div class="col-xs-12 mx-auto d-block">
                        <img src="{{ url('pics/loader_small.gif') }}" data-src="{{ $user->avatar }}" width="{{ $pic_width or '184' }}px" height="{{ $pic_width or '184' }}px" class="img-thumbnail lazy">
                    </div>
                </div>
            @endif

            <br>

            <h4 class="hidden-sm-up">
                @include('user.partials.tagUsername', ['no_link' => true])
            </h4>

            <h1 class="hidden-xs-down">
                @include('user.partials.tagUsername', ['no_link' => true, 'rating' => true])
            </h1>

            @if (!isset($no_links))
                @spaceless
                <p>
                    Steam: <a href="https://steamcommunity.com/profiles/{{ $user->steamid }}" target="_blank" rel="noreferrer nofollow noopener">
                                <b>{{ $user->steam_username }} <i class="fa fa-external-link"></i></b>
                            </a>
                    <br/>
                    TruckersMP: <a href="https://truckersmp.com/user/{{ $user->truckersmpid }}" target="_blank" rel="noreferrer nofollow noopener">
                                    <b>{{ $user->truckersmp_username }} <i class="fa fa-external-link"></i></b>
                                </a>
                </p>
                @endspaceless
            @endif

            <p>
                <i>{{ $user->role->description }}</i>
            </p>
        </div>
    </div>
</div>
