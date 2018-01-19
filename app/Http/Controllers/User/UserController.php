<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Auth;
use Facades\{
    App\Helpers\Keywords
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Jenssegers\Date\Date;
use Purifier;

class UserController extends Controller
{
    use SEOToolsTrait;

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profile_redirect()
    {
        return redirect()->route('profile_page', ['slug' => Auth::user()->slug]);
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function user_id_redirect(string $id)
    {
        $user = User::whereId($id)->first();

        return redirect()->route('profile_page', ['slug' => $user->slug]);
    }

    /**
     * @param $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(string $slug)
    {
        $user = User::whereSlug($slug)->firstOrFail();

        $this->seo()->setTitle($user->nickname);
        $this->seo()->setDescription(clean($user->about));
        $this->seo()->addImages([$user->truckersmp_avatar, $user->steam_avatar]);

        $default_keywords = config('seotools.meta.defaults.keywords');
        $new_keywords     = explode(', ', Keywords::get(clean($user->about)));
        $this->seo()->metatags()->setKeywords(array_merge($default_keywords, $new_keywords));

        $background = url($user->avatar);

        return view('user.profile', compact('user', 'background'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings()
    {
        $this->seo()->setTitle('Настройки профиля');
        $this->seo()->setDescription('');

        /** @var User $user */
        $user       = Auth::user();
        $subscribed = (!$user->subscribe) ? '' : 'checked=checked';

        $options['fluid']               = (!$user->getOption('fluid')) ? '' : 'checked=checked';
        $options['navbar']              = (!$user->getOption('navbar')) ? '' : 'checked=checked';
        $options['rus_names']           = (!$user->getOption('rus_names')) ? '' : 'checked=checked';
        $options['disabled_background'] = (!$user->getOption('disabled_background')) ? '' : 'checked=checked';
        $options['is_steam_avatar']     = (!$user->is_steam_avatar) ? '' : 'checked=checked';

        $now = Date::now();
        $now->setLocale(config('app.locale'));

        $regions   = [
            'Asia'    => \DateTimeZone::ASIA,
            'Europe'  => \DateTimeZone::EUROPE,
            'America' => \DateTimeZone::AMERICA,
            // 'Africa' => \DateTimeZone::AFRICA,
            // 'Antarctica' => \DateTimeZone::ANTARCTICA,
            // 'Atlantic' => \DateTimeZone::ATLANTIC,
            // 'Indian' => \DateTimeZone::INDIAN,
            // 'Pacific' => \DateTimeZone::PACIFIC
        ];
        $timezones = [];
        foreach ($regions as $name => $mask) {
            $zones = \DateTimeZone::listIdentifiers($mask);
            foreach ($zones as $timezone) {
                $time                        = new \DateTime(null, new \DateTimeZone($timezone));
                $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' (' . $time->format('H:i') . ')';
            }
        }

        $background = url($user->avatar);

        $tag_styles = config('site-vars.tag_styles');
        if ($user->isGroup(config('roles.admins'))) {
            $tag_styles[] = 'brand-gradient';
            $tag_styles[] = 'brand-gradient-rev';
        };
        // dd($timezones);

        // dd($user);
        return view('user.settings', compact('user', 'subscribed', 'options', 'timezones', 'now', 'background', 'tag_styles'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeSettings_post(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $tag_styles = config('site-vars.tag_styles');
        if ($user->isGroup(config('roles.admins'))) {
            $tag_styles[] = 'brand-gradient';
            $tag_styles[] = 'brand-gradient-rev';
        };

        $validator = Validator::make($request->all(), [
            'email'     => 'present|nullable|email|max:255|unique:users,email,' . $user->id,
            'subscribe' => 'sometimes',
            'nickname'  => 'required|max:255|min:3',
            'tag'       => 'nullable|max:15',
            'tag_color' => ['nullable', 'required_with:tag', Rule::in($tag_styles)],
            'options'   => 'sometimes|array',
            'timezone'  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('alert.type', 'error')
                ->with('alert.title', 'Что-то тут не так...')
                ->with('alert.message', 'Исправь ошибки в настройках.')
                ->withErrors($validator->errors())
                ->withInput();
        }

        $user->email = $request->email;

        $subscribe = $request->subscribe;
        if ($request->email === null) {
            $user->subscribe = false;
            $subscribe       = false;
        }

        if ($subscribe) {
            $user->subscribe = true;
        } else {
            $user->email     = null;
            $user->subscribe = false;
        }
        $user->nickname  = htmlentities($request->nickname);
        $user->tag       = htmlentities($request->tag);
        $user->tag_color = $request->tag_color;
        $user->timezone  = $request->timezone;
        $user->about     = Purifier::clean($request->about);

        $options               = $request->options;
        $user->is_steam_avatar = isset($options['is_steam_avatar']) ? true : false;

        $user->setOptions($options);
        $user->update();

        return redirect()->back()
            ->with('alert.type', 'success')
            ->with('alert.title', 'Врум-врум!')
            ->with('alert.message', 'Настройки сохранены');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function convoys(Request $request)
    {
        $section = $request->section ?? 'all';

        $this->seo()->setTitle('Мои конвои');
        $background = 'white';

        return view('user.convoys', compact('section', 'background'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notifications_show()
    {
        $this->seo()->setTitle('Все уведомления');
        $background = 'white';

        return view('user.notifications', compact('background'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifications_clearAll()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => Date::now()]);

        return redirect()->back()
            ->with('alert.type', 'success')
            ->with('alert.title', 'Врум-врум!')
            ->with('alert.message', 'Все уведомления прочитаны');
    }
}
