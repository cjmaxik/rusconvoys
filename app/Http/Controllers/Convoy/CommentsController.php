<?php

namespace App\Http\Controllers\Convoy;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Convoy;
use App\Notifications\NewComment;
use Auth;
use Cache;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function new(Request $request)
    {
        $this->validate($request, [
            'convoy_id'    => 'required|exists:convoys,id',
            'comment_text' => 'required|max:200|min:10',
        ]);

        $comment = Comment::create([
            'user_id'   => Auth::id(),
            'convoy_id' => $request->convoy_id,
            'text'      => htmlentities($request->comment_text),
        ]);

        $convoy = Convoy::whereId($request->convoy_id)->first();

        $in_timeout = true;
        if (!Cache::has('convoy_comment_' . $request->convoy_id)) {
            Cache::put('convoy_comment_' . $request->convoy_id, true, 60);
            $in_timeout = false;
        }

        foreach ($convoy->participations as $player) {
            if ($player->user->id === Auth::id()) {
                continue;
            }

            $player->user->notify(new NewComment($player->user, $convoy, $in_timeout));
        }

        return redirect()->back()
            ->with('comment.id', $comment->id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:comments,id',
        ]);

        $comment = Comment::find($request->id);

        if (Auth::id() == $comment->user_id or Auth::user()->isGroup('administration')) {
            $comment->delete();

            $data['state'] = 'ok';

            return $data;
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
            'comment_id'   => 'required|exists:comments,id',
            'comment_text' => 'required|max:200|min:10',
        ]);

        $comment = Comment::find($request->input('comment_id'));

        if (Auth::id() === $comment->user_id) {
            $comment->update([
                'text' => htmlentities($request->comment_text),
            ]);

            $data['state'] = 'ok';
            return $data;
        }
    }
}
