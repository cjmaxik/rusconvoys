<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Cache;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ServerException;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use HttpOz\Roles\Models\Role;
use Illuminate\Http\Request;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Slack;
use TruckersMP\API\APIClient;
use TruckersMP\Exceptions\PlayerNotFoundException;

/**
 * Class SteamAuthController
 *
 * @package App\Http\Controllers\Auth
 */
class SteamAuthController extends Controller
{
    /**
     * @var SteamAuth
     */
    private $steam;

    /**
     * SteamAuthController constructor.
     *
     * @param \Invisnik\LaravelSteamAuth\SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function login()
    {
        // Checking auth
        try {
            $validate = $this->steam->validate();
        } catch (ServerException $e) {
            return redirect()->route('index')
                ->with('alert.type', 'error')
                ->with('alert.title', 'Упс...')
                ->with('alert.message', 'Судя по всему, Steam не может ответить на твой запрос.<br>Пожалуйста, попробуй снова через несколько минут.');
        }

        if ($validate) {
            // Getting user info from Steam
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();

                if (isset($info->steamID64)) {
                    $client = new APIClient();
                    try {
                        $tmp = $client->player($info->steamID64);
                    } catch (PlayerNotFoundException $e) {
                        return redirect()->route('index')
                            ->with('alert.type', 'info')
                            ->with('alert.title', 'Минуточку...')
                            ->with('alert.message', 'Судя по всему, ты не зарегистрирован в мультиплеере. Сайт \"' . config('app.name') . '\" создан для игроков TruckersMP, так что регистрация в мультиплеере обязательна!');
                    }
                } else {
                    throw new \RuntimeException;
                }
                $is_new = is_null($user);

                if (is_null($user)) {
                    $user = new User;
                    $user->nickname = htmlentities(trim($info->personaname));

                    $user->steam_username = htmlentities(trim($info->personaname));
                    $user->steam_avatar = $info->avatarfull;
                    $user->steamid = $info->steamID64;

                    $regexp = '/(\[.[^]]*\])/'; // [TAG] USERNAME
                    if (preg_match($regexp, $info->personaname, $matches)) {
                        $styles = config('site-vars.tag_styles');

                        $user->nickname = htmlentities(trim(str_replace($matches[0], '', $info->personaname)));
                        $user->tag = htmlentities(str_replace(['[', ']'], '', $matches[0]));
                        $user->tag_color = $styles[array_rand($styles)];
                    };
                } else {
                    $user->steam_username = htmlentities($info->personaname);
                    $user->steam_avatar = $info->avatarfull;
                }

                $user->truckersmpid = $tmp->id;
                $user->truckersmp_username = htmlentities($tmp->name);
                $user->truckersmp_avatar = $tmp->avatar;

                $user->setOptions($user->options);
                $user->save();

                if ($is_new) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $user->attachRole(Role::whereSlug('user')->first());
                    $user->addToHistory('reg');
                }

                Cache::forget('roles.user_' . $user->id);
                Auth::login($user, true);

                if ($is_new) {
                    $user_url = route('profile.id_redirect', $user->id);
                    /** @noinspection PhpUndefinedMethodInspection */
                    Slack::send("Новый пользователь: `<{$user_url}|{$user->nickname}>` <{$user->steam_avatar}|{$user->steamid}>");

                    $message = "Похоже, ты впервые на нашем сайте. Взгляни на имеющиеся у нас настройки. Если ты хочешь получать уведомления, сразу же введи свой E-mail.";
                    if ($user->tag and $user->tag_color) {
                        $message .= "<br><br>А еще мы нашли в твоем нике тэг и добавили его автоматически.";
                    }

                    return redirect()->route('user_settings')
                        ->with('alert.type', 'info')
                        ->with('alert.title', "Привет, {$user->nickname}!")
                        ->with('alert.message', $message);
                } else {
                    return redirect()->route('index')// redirect to site
                    ->with('alert.type', 'success')
                        ->with('alert.title', "Привет, {$user->nickname}!")
                        ->with('alert.message', " Рады видеть тебя снова!");
                }
            }
        }

        return $this->steam->redirect(); // redirect to Steam login page
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('index')
            ->with('alert.type', 'info')
            ->with('alert.title', 'Уже уходишь?')
            ->with('alert.message', "Ну пока... Еще увидимся!");
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

}
