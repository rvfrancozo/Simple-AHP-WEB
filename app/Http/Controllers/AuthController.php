<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function githubredirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubcallback()
    {
        $userdata = Socialite::driver('github')->user();

        $user = User::where('email', $userdata->email)->where('auth_type', 'github')->first();
        if ($user) {
            $user->avatar = $userdata->avatar;
            Auth::login($user);
            //dd($userdata);
            return redirect('/');
        } else {

            $uuid = Str::uuid()->toString();

            $user = new User();
            $user->name = $userdata->name;
            $user->email = $userdata->email;
            $user->password = Hash::make($uuid . now());
            $user->auth_type = 'github';
            $user->avatar = $userdata->avatar;
            $user->save();
            Auth::login($user);
            //dd($userdata);
            return redirect('/');
        }
    }


    public function googleredirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googlecallback()
    {
        $userdata = Socialite::driver('google')->user();

        $user = User::where('email', $userdata->email)->where('auth_type', 'google')->first();
        if ($user) {
            $user->avatar = $userdata->avatar;
            Auth::login($user);
            //dd($userdata);
            return redirect('/');
        } else {

            $user = User::where('email', $userdata->email)->first();
            if (!$user) {
                $uuid = Str::uuid()->toString();

                $user = new User();
                $user->name = $userdata->name;
                $user->email = $userdata->email;
                $user->password = Hash::make($uuid . now());
                $user->auth_type = 'google';
                $user->avatar = $userdata->avatar;
                $user->save();
                Auth::login($user);
                //dd($userdata);
                return redirect('/');
            } else {
                "Conta de e-mail já registrada";
            }
        }
    }
}
