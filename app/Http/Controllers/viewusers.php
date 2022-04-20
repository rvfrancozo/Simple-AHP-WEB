<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class viewusers extends Controller
{
    public function view()
    {
        $users = User::get();
        foreach ($users as $user) {
            echo $user->email. "<br>";
        }
    }
}
