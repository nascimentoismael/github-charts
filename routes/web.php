<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/auth/redirect', function (){
    return \Laravel\Socialite\Facades\Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function (){
    $githubUser = \Laravel\Socialite\Facades\Socialite::driver('github')->user();

    $username = \App\Models\User::updateOrCreate([
        'github_id'=>$githubUser->id
    ],[
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'repos' => $githubUser->repos
    ]);

    \Illuminate\Support\Facades\Auth::login($username);

    return redirect('/dashboard');
});

require __DIR__.'/auth.php';
