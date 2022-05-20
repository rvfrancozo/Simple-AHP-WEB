<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objetivo;
use App\Models\Node;
use App\Models\Judments;
use App\Models\Score;
use App\Http\Controllers\AHPController;
use Illuminate\Support\Facades\Auth;

class UpdateScore extends Controller
{
    public function UpdateScore($proxy, Request $request)
    {
        $score = new Score;
        $data = $request->all();
        $x = array(array(array())); //id_node - id_node1 - id_node2
        $n = array();
        $up = array();
        $scores = array();

        for ($i = 0; $i < $data['counter']; $i++) {
            $s = explode(";", $data['score' . $i]);
            if ($s[1] != $proxy) array_push($n, $s[1]);
            if ($s[2] != $proxy) array_push($n, $s[2]);
            array_push($up, $s[0]);
            if ($s[1] < $s[2]) {
                $id_node1 = $s[1];
                $id_node2 = $s[2];
            }
            if ($s[1] > $s[2]) {
                $id_node2 = $s[1];
                $id_node1 = $s[2];
            }
            echo $s[0] . " - " . $id_node1 . " - " . $id_node2 . " = " . $s[3] . "<hr>";
            if ($id_node1 == $proxy) {
                $x[$s[0]][$id_node1][$id_node2] = $s[3];
            }
            if ($id_node2 == $proxy) {
                $x[$s[0]][$id_node2][$id_node1] = $s[3];
            }
            Judments::where('id_node', $s[0])->where('id_node1', $id_node1)->where('id_node2', $id_node2)->update(['score' => $s[3]]);
        }

        $up = array_unique($up);
        $n = array_unique($n);

        foreach ($up as $p) {
            for ($i = 0; $i < count($n); $i++) {
                for ($j = $i + 1; $j < count($n); $j++) {
                    $z = 1 / ($x[$p][$proxy][$n[$i]] / $x[$p][$proxy][$n[$j]]);
                    echo ":" . $p . " - " . $n[$i] . " - " . $n[$j] . " == " . $z . "<br>";
                    Judments::where('id_node', $p)->where('id_node1', $n[$i])->where('id_node2', $n[$j])->update(['score' => $z]);
                }
            }
        }
        //return redirect("/nodes");
    }
}
