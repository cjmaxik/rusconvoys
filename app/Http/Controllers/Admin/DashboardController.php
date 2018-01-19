<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Convoy;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Carbon\Carbon;
use Datatables;
use Yajra\Datatables\Html\Builder;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends Controller
{
    use SEOToolsTrait;

    protected $htmlBuilder;

    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->seo()->setTitle('Панель управления');

        $users_count    = User::count();
        $convoys_count  = Convoy::withTrashed()->count();
        $comments_count = Comment::withTrashed()->count();
        
        $last_users = User::whereBetween('created_at', [
            Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()
        ])->orderByDesc('created_at')->limit(10)->get();

        $last_convoys = Convoy::whereBetween('created_at', [
            Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()
        ])->orderByDesc('created_at')->limit(10)->get();

        $last_comments = Comment::whereBetween('created_at', [
            Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()
        ])->orderByDesc('created_at')->limit(10)->get();

        $background = 'white';

        return view('admin.dashboard.index', compact(
            'users_count',
            'last_users',
            'convoys_count',
            'last_convoys',
            'comments_count',
            'last_comments',
            'background'
        ));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function all_users()
    {
        $title      = 'Пользователи';
        $icon       = 'users';
        $background = 'white';
        $this->seo()->setTitle($title . ' - Панель управления');

        if (request()->ajax()) {
            $model = User::orderByDesc('id');

            return Datatables::eloquent($model)
                ->addColumn('action', function ($user) {
                    return '<a href="' . route('profile.id_redirect', $user->id) . '" target="blank"><i class="fa fa-external-link"></i></a>';
                })
                ->editColumn('avatar', function ($user) {
                    return '<img src="' . $user->avatar . '" alt="' . $user->nickname . '" width="50" height="50" class="rounded mx-auto d-block">';
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
        }

        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'avatar', 'name' => 'avatar', 'title' => 'Аватар', 'searchable' => false],
            ['data' => 'nickname', 'name' => 'nickname', 'title' => 'Ник'],
            ['data' => 'tag', 'name' => 'tag', 'title' => 'Тэг'],
            ['data' => 'role.description', 'name' => 'role.description', 'title' => 'Роль', 'searchable' => false],
        ];

        $html = $this->buildHtml($columns);

        return view('admin.dashboard.table', compact('html', 'background', 'icon', 'title'));
    }

    /**
     * @param array $columns
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    private function buildHtml(array $columns)
    {
        return $this->htmlBuilder
            ->columns($columns)
            ->addAction(['title' => ''])
            ->parameters([
                'language'    => [
                    'url' => '//cdn.datatables.net/plug-ins/1.10.15/i18n/Russian.json',
                ],
                'processing'  => true,
                'serverSide'  => true,
            ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function all_comments()
    {
        $title      = 'Комментарии';
        $icon       = 'comment';
        $background = 'white';
        $this->seo()->setTitle($title . ' - Панель управления');

        if (request()->ajax()) {
            $model = Comment::with('user', 'convoy')
                ->orderByDesc('id')
                ->withTrashed();

            return Datatables::eloquent($model)
                ->addColumn('action', function (Comment $comment) {
                    return '<a href="' . route('convoy.id_redirect', $comment->convoy_id) . '" target="blank"><i class="fa fa-external-link"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'convoy.title', 'name' => 'convoy.title', 'title' => 'Конвой'],
            ['data' => 'user.nickname', 'name' => 'user.nickname', 'title' => 'Автор'],
            ['data' => 'text', 'name' => 'text', 'title' => 'Текст'],
        ];


        $html = $this->buildHtml($columns);

        return view('admin.dashboard.table', compact('html', 'background', 'icon', 'title'));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function all_convoys()
    {
        $title      = 'Конвои';
        $icon       = 'truck';
        $background = 'white';
        $this->seo()->setTitle($title . ' - Панель управления');

        if (request()->ajax()) {
            $model = Convoy::with('user', 'start_town', 'finish_town')
                ->orderByDesc('id')
                ->withTrashed();

            return Datatables::eloquent($model)
                ->addColumn('action', function ($convoy) {
                    return '<a href="' . route('convoy.id_redirect', $convoy->id) . '" target="blank"><i class="fa fa-external-link"></i></a>';
                })
                ->editColumn('route', function ($convoy) {
                    return "{$convoy->start_town->name} - {$convoy->finish_town->name}";
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Название'],
            ['data' => 'route', 'name' => 'route', 'title' => 'Маршрут', 'searchable' => false],
            ['data' => 'user.nickname', 'name' => 'user.nickname', 'title' => 'Автор'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Статус'],
        ];


        $html = $this->buildHtml($columns);

        return view('admin.dashboard.table', compact('html', 'background', 'icon', 'title'));
    }

}
