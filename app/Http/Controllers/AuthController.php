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

        $user = User::where('email',$userdata->email)->where('auth_type','github')->first();
        if($user) {
            echo "Name: ".$userdata->name."<br>";
            echo "Name: ".$userdata->email."<br>";
            dd($userdata);
        } else {
            $uuid = Str::uuid()->toString();
       
        $user = new User();

        $user->name = $userdata->name;
        $user->email = $userdata->email;
        $user->password = Hash::make($uuid.now());
        $user->auth_type = 'github';
        $user->save();
        Auth::login($user);

        echo "Name: ".$userdata->name."<br>";
        echo "E-mail: ".$userdata->email."<br>";

        }
        
    }

}


