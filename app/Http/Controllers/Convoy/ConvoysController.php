<?php /** @noinspection PhpUndefinedNamespaceInspection */

/** @noinspection PhpUndefinedNamespaceInspection */

namespace App\Http\Controllers\Convoy;

use App\Events\ConvoyHasBeenPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditConvoyRequest;
use App\Http\Requests\NewConvoyRequest;
use App\Http\Requests\StoreConvoy;
use App\Models\City;
use App\Models\Convoy;
use App\Models\Country;
use App\Models\DLC;
use App\Models\Game;
use App\Models\Participation;
use App\Notifications\ConvoyHasBeenCancelled;
use App\Traits\dateLoc;
use Artesaos\SEOTools\Traits\SEOTools;
use Auth;
use Cache;
use Facades\{
    App\Helpers\Keywords
};
use Illuminate\Http\Request;
use Jenssegers\Date\Date;
use Log;
use Purifier;
use Slack;

/**
 * Class ConvoysController
 *
 * @package App\Http\Controllers\Convoy
 */
class ConvoysController extends Controller
{
    use SEOTools, dateLoc;

    /**
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $slug)
    {
        $convoy_id = Convoy::withTrashed()->where('slug', $slug)->firstOrFail(['id'])->id;

        if (app()->isLocal()) {
            Cache::forget('convoy_' . $convoy_id);
        }

        /** @var \App\Models\Convoy $convoy */
        $convoy = Cache::remember('convoy_' . $convoy_id, 10, function () use ($convoy_id) {
            return Convoy::withTrashed()
                ->with('comments', 'start_town', 'start_town.dlc', 'finish_town', 'finish_town.dlc', 'server', 'server.game', 'start_town.country', 'finish_town.country', 'user')
                ->where('id', $convoy_id)
                ->first();
        });

        if (Auth::check()) {
            $this->authorize('view', $convoy);
        } else {
            if ($convoy->status === 'draft' or $convoy->trashed()) {
                abort(403);
            }
        }

        if (Auth::check()) {
            $participations = Auth::user()
                ->participations()
                ->where('convoy_id', $convoy->id)
                ->get();
        } else {
            $participations = null;
        }

        if (count($participations)) {
            $user_participate = $participations->where('user_id', Auth::id())->first()->type;
        } else {
            $user_participate = 'nope';
        }
        $participations = $convoy->participations()->get()->groupBy('type');

        $dlcs = Cache::remember('dlcs', 24 * 60, function () {
            return DLC::get();
        });

        $convoy_dlcs = [];
        if (count($convoy->dlcs)) {
            foreach ($convoy->dlcs as $presented_dlc) {
                $convoy_dlcs[] = $dlcs->where('id', $presented_dlc)->first()->screen_name;
            }
        }

        if (Auth::guest()) {
            $this->seo()->setTitle($convoy->getNormTitle() . " | " . Date::parse($convoy->meeting_datetime)->format("d.m.Y г. H:i МСК"));
        } else {
            $this->seo()->setTitle($convoy->getNormTitle());
        }
        $desc = preg_replace('/\s+/', ' ', htmlentities(strip_tags($convoy->description)));
        $this->seo()->setDescription(
            trim(str_limit($desc, 1000))
        );
        $this->seo()->addImages([$convoy->background_url, $convoy->map_url]);

        $default_keywords = config('seotools.meta.defaults.keywords');
        $new_keywords     = explode(', ', Keywords::get(clean($convoy->description)));
        $this->seo()->metatags()->setKeywords(array_merge($default_keywords, $new_keywords));

        $background = $this->background($convoy);

        return view('convoy.show', compact('convoy', 'slug', 'participations', 'user_participate', 'background', 'convoy_dlcs'));
    }

    /**
     * @param $convoy
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function background($convoy)
    {
        if ($convoy->pinned or $convoy->user->can('privileged', \App\Models\Convoy::class)) {
            if ($convoy->background_url_safe) {
                return $convoy->background_url_safe;
            } else if ($convoy->background_url) {
                return $convoy->background_url;
            } else if ($convoy->map_url_safe) {
                return $convoy->map_url_safe;
            } else if ($convoy->map_url) {
                return $convoy->map_url;
            } else {
                return url('/pics/008.jpg');
            }
        }
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convoy_id_redirect(string $id)
    {
        $convoy = Convoy::withTrashed()->whereId($id)->first();

        return redirect()->route('convoy_show', ['slug' => $convoy->slug]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function all(Request $request)
    {
        $page    = $request->input('page', '1');
        $convoys = Cache::remember('convoys_' . $page, 1, function () {
            return Convoy::with('server', 'server.game', 'start_town', 'finish_town', 'user')
                ->whereIn('status', ['open', 'meeting', 'on_way'])
                ->orderBy('pinned', 'desc')
                ->orderBy('meeting_datetime', 'asc')
                ->paginate(15);
        });

        $this->seo()->setTitle('Все конвои');

        $background = 'white';

        return view('convoy.showAll', compact('convoys', 'background'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function archive(Request $request)
    {
        $page    = $request->input('page', '1');
        $convoys = Cache::remember('archive_' . $page, 60, function () {
            return Convoy::withCount('participations')
                ->with('server', 'server.game', 'start_town', 'finish_town', 'user')
                ->whereIn('status', ['voting', 'closed'])
                ->orderBy('pinned', 'desc')
                ->orderBy('meeting_datetime', 'asc')
                ->paginate(15);
        });

        if ($convoys->count() === 0) {
            return redirect()->route('index');
        }

        $title   = 'Архив конвоев';
        $archive = true;
        $this->seo()->setTitle($title);

        $background = 'white';

        return view('convoy.showAll', compact('convoys', 'title', 'archive', 'background'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function new_show()
    {
        if (Cache::has('convoy_timeout_' . Auth::id())) {
            return redirect()->route('index')
                ->with('alert.type', 'error')
                ->with('alert.title', 'Не так быстро!')
                ->with('alert.message', 'Ты создал конвой совсем недавно. Приходи позже.');
        }

        $games     = Cache::remember('games', 24 * 60, function () {
            return Game::with('servers')->get();
        });
        $countries = Cache::remember('countries', 24 * 60, function () {
            return Country::with('cities', 'game')->get();
        });
        $dlcs      = Cache::remember('dlcs', 24 * 60, function () {
            return DLC::get();
        });

        $now = Date::make('now', Auth::user()->timezone)->addHours(3);

        $this->seo()->setTitle('Создание конвоя');
        $background = 'white';

        return view('convoy.new', compact('games', 'countries', 'dlcs', 'now', 'background'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|void
     */
    public function edit_show(int $id)
    {
        $convoy = Convoy::withTrashed()->where('id', $id)->firstOrFail();

        if (Auth::user()->cannot('update', $convoy)) {
            return abort(503);
        }

        if (!in_array($convoy->status, ['draft', 'open'])) {
            return redirect()->route('convoy_show', ['slug' => $convoy->slug])
                ->with('alert.type', 'error')
                ->with('alert.title', 'Куда?!')
                ->with('alert.message', 'Нельзя редактировать конвой в данном статусе.');
        }

        $games     = Cache::remember('games', 24 * 60, function () {
            return Game::with('servers')->get();
        });
        $countries = Cache::remember('countries', 24 * 60, function () {
            return Country::with('cities')->get();
        });
        $dlcs      = Cache::remember('dlcs', 24 * 60, function () {
            return DLC::get();
        });

        $this->seo()->setTitle('Редактирование конвоя');

        $background = $this->background($convoy);

        return view('convoy.edit', compact('convoy', 'games', 'dlcs', 'countries', 'background'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function cancel_post(Request $request)
    {
        $convoy = Convoy::whereId($request->id)->firstOrFail();

        if (Auth::user()->cannot('cancel', $convoy)) {
            return 'NOPE';
        }

        $convoy->status            = 'cancelled';
        $convoy->cancelled_message = htmlentities($request->message);
        $convoy->update();
        Cache::forget('convoy_' . $convoy->id);

        foreach ($convoy->participations as $player) {
            if ($player->user->id === $convoy->user->id) {
                continue;
            }

            $player->user->notify(new ConvoyHasBeenCancelled($player->user, $convoy));
        }

        return 'OK';
    }

    /**
     * @param \App\Http\Requests\NewConvoyRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function new_post(NewConvoyRequest $request)
    {
        // Validator and policy has been moved to Requests/NewConvoyRequest
        if (Auth::user()->cannot('create_within_timeout', Convoy::class)) {
            return redirect()->back()
                ->with('alert.type', 'error')
                ->with('alert.title', 'Не так быстро!')
                ->with('alert.message', 'Ты уже создавал конвой совсем недавно. Приходи позже.');
        }

        $dlcs = $this->check_dlcs($request);

        $privileged = Auth::user()->can('privileged', Convoy::class);
        $convoy     = Convoy::create([
            'user_id'           => Auth::id(), //
            'title'             => htmlentities($request->title),
            'meeting_datetime'  => $this->dateTz($request->meeting_datetime),
            'leaving_datetime'  => $this->dateTz($request->leaving_datetime),
            'server_id'         => $request->server_id,
            'start_town_id'     => $request->start_town_id,
            'start_place'       => htmlentities($request->start_place),
            'finish_town_id'    => $request->finish_town_id,
            'finish_place'      => htmlentities($request->finish_place),
            'dlcs'              => array_values($dlcs), //
            'stops'             => htmlentities($request->stops),
            'route_length'      => $request->route_length ?? 0,
            'voice_description' => htmlentities($request->voice_description),
            'map_url'           => $request->map_url,
            'background_url'    => $privileged ? $request->background_url : null,
            'status'            => 'draft', //
            'description'       => $privileged ? Purifier::clean($request->description) : Purifier::clean(strip_tags($request->description)),
        ]);

        $this->bake_participation($convoy);

        $this->post_in_slack($convoy);

        if ($convoy->user->cannot('privileged', $convoy)) {
            Cache::put('convoy_timeout_' . Auth::id(), true, 10 * 60);
        }

        return redirect()->route('convoy_show', ['slug' => $convoy->slug])
            ->with('alert.type', 'success')
            ->with('alert.title', 'Врум-врум!')
            ->with('alert.message', 'Конвой успешно создан.');
    }

    /**
     * @param \App\Http\Requests\NewConvoyRequest $request
     *
     * @return array|mixed
     */
    private function check_dlcs(NewConvoyRequest $request)
    {
        $dlcs           = $request->dlc ?? [];
        $presented_dlcs = [
            (int)City::DlcID($request->start_town_id),
            (int)City::DlcID($request->finish_town_id),
        ];
        $dlcs           = array_values(array_unique(array_merge(array_keys($dlcs), $presented_dlcs)));
        if (($key = array_search(1, $dlcs)) !== false) {
            unset($dlcs[$key]);
        }

        return $dlcs;
    }

    /**
     * @param $convoy
     */
    private function bake_participation($convoy): void
    {
        Participation::create([
            'type'      => 'yep',
            'user_id'   => $convoy->user_id,
            'convoy_id' => $convoy->id,
        ]);
    }

    /**
     * @param $convoy
     */
    private function post_in_slack($convoy): void
    {
        $title    = html_entity_decode($convoy->getNormTitle());
        $nickname = html_entity_decode($convoy->user->nickname);
        $link     = route('convoy.id_redirect', $convoy->id);

        $text = "Опубликован черновик конвоя: <{$link}|`{$title}`> от {$nickname}";

        try {
            Slack::send($text);
        } catch (\RuntimeException $e) {
            Log::error($e);
        }
    }

    /**
     * @param \App\Http\Requests\EditConvoyRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit_post(EditConvoyRequest $request)
    {
        // Validator and policy has been moved to Requests/EditConvoyRequest
        $convoy = Convoy::findOrFail($request->id);

        if (!in_array($convoy->status, ['draft', 'open'])) {
            return redirect()->back()
                ->with('alert.type', 'error')
                ->with('alert.title', 'Куда?!')
                ->with('alert.message', 'Нельзя редактировать конвой в данном статусе.');
        }

        /**
         * For the observer
         */
        if ($convoy->background_url !== $request->background_url) {
            $convoy->background_url_safe = null;
        }

        if ($convoy->map_url !== $request->map_url) {
            $convoy->map_url_safe = null;
        }

        $dlcs           = $request->dlc ?? [];
        $presented_dlcs = [
            (int)City::DlcID($convoy->start_town_id),
            (int)City::DlcID($convoy->finish_town_id),
        ];
        $dlcs           = array_values(array_unique(array_merge(array_keys($dlcs), $presented_dlcs)));
        if (($key = array_search(1, $dlcs)) !== false) {
            unset($dlcs[$key]);
        }

        $last_status   = $convoy->status;
        $convoy_pinned = $convoy->pinned ? true : false;

        if ($request->draft === 'on') {
            $convoy_status = 'draft';
        } else if ($convoy->status !== 'draft') {
            $convoy_status = $convoy->status;
        } else {
            $convoy_status = 'open';
            $convoy->slug  = null;

            if ($convoy->user->can('privileged', $convoy) and $convoy_status === 'open') {
                if (isset($convoy->background_url) or isset($convoy->map_url)) {
                    $convoy_pinned = true;
                }
            }
        }

        $description = $convoy->user->can('privileged', $convoy) ? $request->description : strip_tags($request->description);

        $convoy->fill([
            'title'             => htmlentities($request->title),
            'meeting_datetime'  => $this->dateTz($request->meeting_datetime),
            'leaving_datetime'  => $this->dateTz($request->leaving_datetime),
            'start_place'       => htmlentities($request->start_place),
            'finish_place'      => htmlentities($request->finish_place),
            'voice_description' => htmlentities($request->voice_description),
            'map_url'           => $request->map_url,
            'background_url'    => Auth::user()->can('privileged', $convoy) ? $request->background_url : null,
            'stops'             => htmlentities($request->stops),
            'dlcs'              => array_values($dlcs), //
            'route_length'      => $request->route_length ?? 0,
            'status'            => $convoy_status,
            'description'       => Purifier::clean($description),
            'pinned'            => $convoy_pinned,
        ])->save();

        Cache::forget('convoy_' . $convoy->id);

        if ($last_status === 'draft' and $convoy->status === 'open') {
            $message = 'Конвой успешно опубликован!<br><strong>Ни гвоздя, ни жезла!</strong>';

            if ($convoy_pinned) {
                $message .= '<hr>Кстати, конвой был автоматически закреплен на главной странице!';
            }
            event(new ConvoyHasBeenPublished($convoy));
        } else {
            $message = 'Конвой успешно отредактирован!';
            if ($convoy->status === 'draft') {
                $message .= "<hr><strong>ВНИМАНИЕ! Конвой находится в черновиках!</strong><br>Для публикации сними галочку 'Оставить в черновиках' при редактировании.";
            }
        }

        return redirect()->route('convoy_show', ['slug' => $convoy->slug])
            ->with('alert.type', 'success')
            ->with('alert.title', 'Врум-врум!')
            ->with('alert.message', $message);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function participation_post(Request $request)
    {
        $this->validate($request, [
            'type'      => 'in:yep,thinking,nope',
            'convoy_id' => 'required|exists:convoys,id',
        ]);

        $convoy_id = $request->convoy_id;
        $new_type  = $request->type;

        $convoy = Convoy::find($convoy_id);
        if (!$convoy or !in_array($convoy->status, ['meeting', 'open']) or Auth::id() === $convoy->user_id) {
            return "fail";
        }

        $participation = Auth::user()->participations()->where('convoy_id', $convoy_id)->first();
        if ($participation) {
            if ($new_type === 'nope') {
                $participation->delete();

                return "nope";
            } else {
                $participation->type = $new_type;
                $participation->save();
            }
        } else {
            $participation = Participation::create([
                'type'      => $new_type,
                'user_id'   => Auth::id(),
                'convoy_id' => $convoy->id,
            ]);
        }

        return $participation->type;
    }

    public function pin(int $id)
    {
        $convoy = Convoy::findOrFail($id);

        $convoy->pinned = !$convoy->pinned;
        $convoy->update();

        $message = $convoy->pinned ? 'Конвой был успешно закреплен' : 'Конвой был успешно откреплен';

        Cache::forget('convoy_' . $convoy->id);

        return redirect()->back()
            ->with('alert.type', 'success')
            ->with('alert.title', 'Врум-врум!')
            ->with('alert.message', $message);
    }
}
