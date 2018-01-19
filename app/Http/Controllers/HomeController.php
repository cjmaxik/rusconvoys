<?php

namespace App\Http\Controllers;

use App\Models\Convoy;
use Artesaos\SEOTools\Traits\SEOTools;
use Cache;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    use SEOTools;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (config('app.env') === 'local') {
            Cache::tags('convoys')->flush();
        };

        $convoys = Cache::tags('convoys')->remember('last_convoys', 1, function () {
            return Convoy::with('server', 'server.game', 'start_town', 'start_town.dlc', 'finish_town', 'finish_town.dlc', 'user', 'participations')
                ->withCount('participations')
                ->whereIn('status', ['open', 'meeting'])
                ->where('pinned', 0)
                ->orderBy('meeting_datetime', 'asc')
                ->take(10)
                ->get();
        });

        $pinned = Cache::tags('convoys')->remember('pinned_convoys', 1, function () {
            return Convoy::with('server', 'server.game', 'start_town', 'start_town.dlc', 'finish_town', 'finish_town.dlc', 'user', 'participations')
                ->withCount('participations')
                ->whereIn('status', ['open', 'meeting'])
                ->where('pinned', 1)
                ->orderBy('meeting_datetime', 'asc')
                ->get();
        });

        $convoys_count = Cache::tags('convoys')->remember('convoys_count', 1, function () {
            return Convoy::whereIn('status', ['open', 'meeting'])
                ->count();
        });

        $background = url('/pics/back/may.jpg');

        return view('home.show', compact('options', 'convoys', 'convoys_count', 'pinned', 'background'));
    }
}
