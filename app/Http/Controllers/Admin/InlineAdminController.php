<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class InlineAdminController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function role_change(Request $request)
    {
        $user     = User::findOrFail($request->input('user_id'));
        $new_role = $request->input('new_role');

        if ($user->is_banned) {
            $user->setOptions(['ban' => false]);
        }

        $user->assignNewRole($new_role);

        return "ok";
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function ban_user(Request $request)
    {
        $user        = User::findOrFail($request->input('user_id'));
        $ban_message = $request->input('message');

        $user->ban($ban_message);

        return "ok";
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function unban_user(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        $user->unban();

        return "ok";
    }

    public function change_user(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        $user->update([
            'nickname' => htmlentities($request->input('nickname')),
            'tag'      => htmlentities($request->input('tag')),
        ]);

        return $user->slug;
    }
}
