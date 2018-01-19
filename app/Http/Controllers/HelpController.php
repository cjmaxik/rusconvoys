<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Convoy;
use App\Models\User;
use Cache;
use Jenssegers\Date\Date;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class HelpController extends Controller
{
    use SEOToolsTrait;

    public function show()
    {
        $background = 'white';

        return view('help.show', compact('background'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        if (config('app.env') === 'local') {
            Cache::tags('stats')->flush();
        };

        $users_count = Cache::tags('stats')->remember('users_count', 24 * 60, function () {
            return ceil(User::count() / 10) * 10;
        });

        $convoys_count = Cache::tags('stats')->remember('convoys_count', 24 * 60, function () {
            return ceil(Convoy::withTrashed()->count() / 10) * 10;
        });

        $comments_count = Cache::tags('stats')->remember('comments_count', 24 * 60, function () {
            return ceil(Comment::withTrashed()->count() / 10) * 10;
        });

        $timedate = Cache::tags('stats')->remember('timedate', 24 * 60, function () {
            return Date::now()->format('d.m.Y г.');
        });

        $this->seo()->setTitle('О проекте');

        $background = 'gradient';

        return view('home.about', compact('users_count', 'convoys_count', 'comments_count', 'timedate', 'background'));
    }
}
