<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function githubredirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubcalllback()
    {
        $user = Socialite::driver('github')->user();
        dd($user);
    }

}
