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

// Language switch (EN / AR). The frontend JS posts the chosen locale here,
// it is stored in the session, and TastyIgniter's Localization middleware
// (igniter.translation.locale session key) applies it on the next request.
Route::post('/locale/{locale}', function (string $locale) {
    $localization = app('translator.localization');

    if (! in_array($locale, $localization->supportedLocales())) {
        return redirect()->back();
    }

    $localization->setSessionLocale($locale);

    return redirect()->back();
});
