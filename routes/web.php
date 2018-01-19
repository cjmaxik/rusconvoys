<?php

# Main
Route::get('/', 'HomeController@index')->name('index');
Route::get('about', 'HelpController@about')->name('about');
Route::get('help', 'HelpController@show')->name('help');
Route::get('rules', 'User\RulesController@show')->name('rules');
Route::post('rules', 'User\RulesController@accept')->name('accept_rules')->middleware('auth');

## Auth Routes
Route::get('login', 'Auth\SteamAuthController@login')->name('login');
Route::post('logout', 'Auth\SteamAuthController@logout')->name('logout')->middleware('auth');

## Profile Routes
Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function () {
    Route::get('/', 'User\UserController@profile_redirect');
    Route::get('id/{id}', 'User\UserController@user_id_redirect')->name('profile.id_redirect')->middleware(['auth', 'group:administration']);

    Route::get('{slug}', 'User\UserController@profile')->name('profile_page');
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', 'User\UserController@profile_redirect');

    Route::get('settings', 'User\UserController@settings')->name('user_settings')->middleware('auth');
    Route::post('changeSettings', 'User\UserController@changeSettings_post')->name('user_changeSettings')->middleware('auth');

    Route::get('notifications', 'User\UserController@notifications_show')->name('notifications_show')->middleware('auth');
    Route::get('notifications_clearAll', 'User\UserController@notifications_clearAll')->name('notifications_clearAll')->middleware('auth');

    Route::get('convoys', 'User\UserController@convoys')->name('user_convoys')->middleware('auth');
});

## Convoy Routes
Route::group(['prefix' => 'convoy'], function () {
    Route::get('/', 'Convoy\ConvoysController@all')->name('convoy_all');
    Route::get('archive', 'Convoy\ConvoysController@archive')->name('convoy_archive');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('new', 'Convoy\ConvoysController@new_show')->middleware('can:create,App\Models\Convoy')->name('convoy.new.show');
        Route::post('newPost', 'Convoy\ConvoysController@new_post')->middleware('can:create,App\Models\Convoy')->name('convoy.new.post');

        // Policies is in functions!
        Route::get('{id}/edit', 'Convoy\ConvoysController@edit_show')->name('convoy.edit.show');
        Route::post('editPost', 'Convoy\ConvoysController@edit_post')->name('convoy.edit.post');
        Route::post('cancelPost', 'Convoy\ConvoysController@cancel_post')->name('convoy.cancel.post');
        Route::get('{id}/pin', 'Convoy\ConvoysController@pin')->middleware('can:pin,App\Models\Convoy')->name('convoy.pin');
    });

    Route::post('participation', 'Convoy\ConvoysController@participation_post')->name('convoy_participationPost')->middleware('auth');
    Route::get('id/{id}', 'Convoy\ConvoysController@convoy_id_redirect')->name('convoy.id_redirect')->middleware(['auth', 'group:administration']);

    Route::get('{slug}', 'Convoy\ConvoysController@show')->name('convoy_show'); // Policy is in function!
});

/**
 * Comment Routes
 */
Route::group(['prefix' => 'comment', 'middleware' => ['auth', 'throttle:5,1']], function () {
    Route::post('new', 'Convoy\CommentsController@new')->name('comments_newPost');
    Route::post('edit', 'Convoy\CommentsController@edit')->name('comment_editPost');
    Route::post('delete', 'Convoy\CommentsController@delete')->name('comment_deletePost');
});

/**
 * Admin Routes
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'group:administration']], function () {
    Route::group(['prefix' => 'constant'], function () {
        Route::get('/', 'Admin\ConstantController@index')->name('admin.constant.index');
        Route::post('addCountry', 'Admin\ConstantController@store_country')->name('admin.constant.addCountry');
        Route::post('addCity', 'Admin\ConstantController@store_city')->name('admin.constant.addCity');
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
        Route::get('all_users', 'Admin\DashboardController@all_users')->name('admin.dashboard.all_users');
        Route::get('all_convoys', 'Admin\DashboardController@all_convoys')->name('admin.dashboard.all_convoys');
        Route::get('all_comments', 'Admin\DashboardController@all_comments')->name('admin.dashboard.all_comments');
    });

    Route::group(['prefix' => 'opcache'], function () {
        Route::get('clear', 'Admin\EngineController@clear_opcache')->name('admin.opcache.clear');
        Route::get('status', 'Admin\EngineController@status_opcache')->name('admin.opcache.status');
    });

    Route::post('role_change', 'Admin\InlineAdminController@role_change')->name('admin.role_change');
    Route::post('ban_user', 'Admin\InlineAdminController@ban_user')->name('admin.ban_user');
    Route::post('change_user', 'Admin\InlineAdminController@change_user')->name('admin.change_user');
});

if (app()->isLocal()) {
    Route::get('loginAs/{id?}', function ($id = null) {
        Auth::loginUsingId(($id) ?: 1, true);

        return redirect()->back();
    })->name('testassert');

    Route::get('abort/{id}', function ($id) {
        \Config::set('app.debug', false);
        abort($id);
    });

    Route::get('swal', function () {
        return redirect()->route('index')
            ->with('alert.type', 'success')
            ->with('alert.message', 'Привет');
    });
}
