<?php

namespace App\Http\Controllers;
use App\Models\Node;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $objectives = Node::get()->where('level', 0)->where('user_id',Auth::user()->id);
        return view('objetivos.nodes')->with('objectives', $objectives);
        //return view("objetivos.nodes")
    }
}
