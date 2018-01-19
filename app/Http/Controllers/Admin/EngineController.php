<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Class EngineController
 *
 * @package App\Http\Controllers\Admin
 */
class EngineController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear_opcache()
    {
        if (function_exists('opcache_reset')) {
            opcache_reset();

            return redirect()->back(302, [], route('index'))
                ->with('alert.type', 'success')
                ->with('alert.message', 'Opcache очищен');
        }

        return $this->no_opcache();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function no_opcache()
    {
        return redirect()->back(302, [], route('index'))
            ->with('alert.type', 'error')
            ->with('alert.message', 'Здесь нет Opcache');
    }

    /**
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function status_opcache()
    {
        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status(false);

            return $status ?: false;
        }

        return $this->no_opcache();
    }
}
