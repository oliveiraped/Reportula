<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});

App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/
Route::filter('auth.admin', function () {
    if ( ! Sentry::check()) {
        return Redirect::route('admin.login');
    }
});

Route::filter('auth', function () {
    if ( ! Sentry::check()) {
        return Redirect::route('login');
    }
});

/*
Route::filter('auth.basic', function () {
    return Auth::basic();
});*/

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/index.php/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});



/*
sources :
    https://gist.github.com/garagesocial/6059962
    http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter
*/
App::after(function ($request, $response) {
    // HTML Minification
    if (App::Environment() != 'local') {
        if ($response instanceof Illuminate\Http\Response) {
            $output = $response->getOriginalContent();

            $filters = array(
                '/(?<!\S)\/\/\s*[^\r\n]*/'	=> '', // Remove comments in the form /* */
                '#(?ix)(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))#'	=> '',
            );
            $output = preg_replace(array_keys($filters), array_values($filters), $output);

            if ($output !== NULL) {
                $response->setContent($output);
            }
        }
    }
});
