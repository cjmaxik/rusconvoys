<nav class="navbar navbar-toggleable-md {{ $navbar_type or 'fixed' }}-top navbar-inverse brand-gradient-rev bg-faded">
    <div class="container">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarToggler">
        <span class="navbar-toggler-icon"></span>
    </button>

        <a class="navbar-brand" href="{{ route('index', [], false) }}">
            <strong>
                <i class="fa fa-truck fa-fw animate-truck"></i> {{ config('app.name') }}
                @if (config('app.env') === 'local')
                    <span class="badge red hoverable">{{  config('app.build') }}</span>
                @endif
            </strong>
        </a>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <div class="navbar-nav mr-auto">
                <a class="nav-item {{ if_route('convoy_all') ? 'active' : '' }} nav-link" href="{{ route('convoy_all', [], false) }}">Все конвои</a>
                <a class="nav-item {{ if_route('rules') ? 'active' : '' }} nav-link" href="{{ route('rules', [], false) }}">Правила</a>
                <a class="nav-item {{ if_route('about') ? 'active' : '' }} nav-link" href="{{ route('about', [], false) }}">О проекте</a>
                <a class="nav-item {{ if_route('help') ? 'active' : '' }} nav-link" href="{{ route('help', [], false) }}">Помощь</a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        Полезное
                    </a>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" target="_blank" href="https://vk.com/rusconvoys" rel="noreferrer nofollow noopener">
                            <i class="fa fa-vk fa-fw left"></i> Сообщество ВКонтакте
                        </a>

                        <a class="dropdown-item" target="_blank" href="https://discord.rusconvoys.ru" rel="noreferrer nofollow noopener">
                            <i class="fa fa-volume-control-phone fa-fw left"></i> Сервер Discord
                        </a>

                        <a class="dropdown-item" target="_blank" href="https://t.me/rusconvoys" rel="noreferrer nofollow noopener">
                            <i class="fa fa-paper-plane fa-fw left"></i> Канал в Телеграм
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" target="_blank" href="https://bigmp.ru" rel="noreferrer nofollow noopener">
                            <i class="fa fa-vk fa-fw left"></i> Большой брат
                        </a>

                        <a class="dropdown-item" target="_blank" href="https://helper.mp" rel="noreferrer nofollow noopener">
                            <i class="fa fa-search fa-fw left"></i> Помогатор
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" target="_blank" href="https://truckersmp.com" rel="noreferrer nofollow noopener">
                            <i class="fa fa-truck fa-fw left"></i> TruckersMP
                        </a>
                    </div>
                </div>
            </div>

            <div class="navbar-nav">
                @if (Auth::guest())
                    @if (config('app.env') === 'local')
                        <a class="nav-item nav-link" href="{{ route('testassert', [], false) }}">Log in as 1</a>
                    @endif
                    <a href="{{ route('login', [], false) }}" id="login-button" class="nav-item nav-link bg-success hoverable login-button">
                        <i class="fa fa-steam fa-fw left"></i> Вход | Регистрация
                    </a>
                @else
                    @if (Auth::user()->unreadNotifications->count())
                        <a href="{{ route('notifications_show') }}" class="nav-item nav-link" data-toggle="tooltip" data-placement="bottom" title="Есть непрочитанные
                        уведомления">
                            <i class="fa fa-bell red-text faa-ring faa-slow animated"></i>
                        </a>
                    @endif

                    @if (Auth::user()->drafts_count)
                        <a href="{{ route('user_convoys', ['section' => 'drafts'], false) }}" class="nav-item nav-link" data-toggle="tooltip" data-placement="bottom" title="Есть
                        неопубликованные конвои">
                            <i class="fa fa-exclamation-circle red-text faa-ring faa-slow animated"></i>
                        </a>
                    @endif

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            @include('user.partials.tagUsername', ['user' => Auth::user(), 'no_link' => true])
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            @can('create', App\Models\Convoy::class)
                                <a class="dropdown-item" href="{{ route('convoy.new.show', [], false) }}">
                                    <i class="fa fa-plus-circle fa-fw left"></i> Создать конвой
                                </a>
                            @endcan

                            <a class="dropdown-item" href="{{ route('user_convoys', [], false) }}">
                                <i class="fa fa-cubes fa-fw left"></i> Мои конвои
                            </a>

                            <a class="dropdown-item" href="{{ route('notifications_show', [], false) }}">
                                <i class="fa fa-bell fa-fw left"></i> Уведомления
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('profile_page', ['slug' => Auth::user()->slug], false) }}">
                                <i class="fa fa-user fa-fw left"></i> Профиль
                            </a>

                            <a class="dropdown-item" href="{{ route('user_settings', [], false) }}">
                                <i class="fa fa-cog fa-fw left"></i> Настройки
                            </a>

                            @if (Auth::user()->isGroup(config('roles.admins')))
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('admin.dashboard', [], false) }}">
                                    <i class="fa fa-dashboard fa-fw left"></i> Панель управления
                                </a>
                            @endif

                                <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out fa-fw left"></i> Выйти
                            </a>
                        </div>

                        <form id="logout-form" action="{{ route('logout', [], false) }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>

@if (App::isDownForMaintenance())
    <div class="card card-danger text-center z-depth-2" style="margin-bottom: 2rem !important;">
        <div class="card-block">
            <h3 class="white-text" style="margin-bottom: 0 !important;">Включена профилактика!!!</h3>
        </div>
    </div>
@endif