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
        
        if($s[1] < $s[2]) {
            Judments::where('id_node',$s[0])->where('id_node1',$s[1])->where('id_node2',$s[2])->update(['score' => 1/$s[3]]);
        } else {
            Judments::where('id_node',$s[0])->where('id_node1',$s[2])->where('id_node2',$s[1])->update(['score' => $s[3]]);
        }

        return redirect("/nodes/".$s[0]."/HumanReport");
    }
}
