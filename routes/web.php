<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/timetable', function () {
    $response = response()->view('timetable');

    $referer = Request::server('HTTP_REFERER');
    \Illuminate\Support\Facades\Log::debug($referer);
    if ($referer) {
        $domain = getDomainName($referer);
        if ($domain === "messenger.com") {
            $response->header('X-Frame-Options', 'www.messenger.com');
        } else if ($domain === "facebook.com") {
            $response->header('X-Frame-Options', 'www.facebook.com');
        }
    }

    return $response;
});

Route::get('/admin-panel', function () {
    return view('admin_index');
});
Route::post('/messages', ['as' => 'originating.messages.send', 'uses' => 'MessageController@sendMessage']);

Route::match(['get', 'post'], '/ucsy', 'BotManController@handle');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
