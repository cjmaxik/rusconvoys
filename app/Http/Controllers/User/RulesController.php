<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use Auth;

class RulesController extends Controller
{
    use SEOTools;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $this->seo()->setTitle('Правила Проекта');
        $background = 'white';

        return view('rules', compact('background'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept()
    {
        Auth::user()->update([
            'rules_accepted' => true,
        ]);

        return redirect()->route('index');
    }
}
