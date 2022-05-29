<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function notes()
    {
        return view("objetivos.notes");
    }

    public function error()
    {
        return view("objetivos.error");
    }

    public function erroruser()
    {
        return view("objetivos.erroruser");
    }
}
