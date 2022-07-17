<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objetivo;
use App\Models\Node;
use App\Models\Judments;
use App\Models\Score;
use App\Http\Controllers\AHPController;
use Illuminate\Support\Facades\Auth;

class UpdateSingleScore extends Controller
{
    public function UpdateSingleScore(Request $request)
    {
        $data = $request->all();
        $s = explode(";", $data['newjudment']);
        $v = $data['newscore'];
        if ($v < 0)
            $v = 1 / ($v * (-1));
        if ($v == 0)
            $v = 1;
        array_push($s, $v);

        if ($s[0] == $s[1]) {
            if ($s[2] < $s[3]) {
                Judments::where('user_email', Auth::user()->email)->where('id_node', $s[0])->where('id_node1', $s[2])->where('id_node2', $s[3])->update(['score' => 1 / $s[4]]);
            } else {
                Judments::where('user_email', Auth::user()->email)->where('id_node', $s[0])->where('id_node1', $s[3])->where('id_node2', $s[2])->update(['score' => $s[4]]);
            }
            return redirect("/nodes/" . $s[0] . "/HumanReport");
        } else {
            if ($s[2] < $s[3]) {
                Judments::where('user_email', Auth::user()->email)->where('id_node', $s[1])->where('id_node1', $s[2])->where('id_node2', $s[3])->update(['score' => 1 / $s[4]]);
            } else {
                Judments::where('user_email', Auth::user()->email)->where('id_node', $s[1])->where('id_node1', $s[3])->where('id_node2', $s[2])->update(['score' => $s[4]]);
            }
            return redirect("/nodes/" . $s[0] . "/HumanReport#" . $s[1]);
        }
    }
}
