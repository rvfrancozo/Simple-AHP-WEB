<?php

namespace App\Http\Controllers;

use App\Models\GroupDecision;
use Illuminate\Support\Facades\Auth;
use App\Models\Node;
use App\Models\User;
use App\Http\Controllers\AHPController;
use App\Models\dmModel;
use App\Models\Judments;

use Illuminate\Http\Request;
use PHPUnit\TextUI\XmlConfiguration\Group;
use stdClass;

class dmController extends Controller
{
    public function dm($id)
    {
        $j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0, null);
        $j_alternatives = AHPController::GetAlternativesJudmentsMatrix($id, 0, null);

        if (count($j_criteria) == 0 || count($j_alternatives) == 0) {
            return redirect('/error'); //fazer uma view de erro
        }
        $node = Node::get()->where('id', $id)->first();
        $dms = GroupDecision::where('node', $id)->get();
        $users = array();
        foreach ($dms as $d) {
            array_push($users, $d->email);
        }

        $dms = GroupDecision::leftJoin('users', 'groupdecision.email', '=', 'users.email')
            ->where('groupdecision.node', $id)
            ->select('groupdecision.id', 'groupdecision.node', 'groupdecision.email', 'groupdecision.weight', 'users.avatar')
            ->get();
        
        $gw = GroupDecision::where('node',$id)->sum('weight');
        if($gw >= 1) $ow = 1;
        else $ow = 1 - $gw;

        //$dms = User::wherein('email',$users)->get();
        return view("objetivos.dmpanel")
            ->with('id', $node->id)
            ->with('descr', $node->descr)
            ->with('dms', $dms)
            ->with('ow',$ow);
    }

    public function createDM($id, Request $request)
    {
        $node = Node::get()->where('id', $id)->first();
        $dms = GroupDecision::where('node', $id)->get();

        $data = $request->all();

        if (GroupDecision::where('node', $id)->where('email', $data['descricao'])->get()->first()) {
            //echo "teste";
            return redirect('/error/user'); //fazer uma view de erro
        }

        $dm = new GroupDecision();
        $dm->node = $id;
        $dm->email = $data['descricao'];
        $dm->save();

        $neweight = 1/(GroupDecision::where('node',$id)->count() + 1);
        GroupDecision::where('node',$id)->update(['weight' => $neweight]);

        //adiciona os julgamentos do novo usuário

        $criteria = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('judments.id_node', $id)
            ->select('node.id', 'node.descr')
            ->distinct()
            ->get();

        $c_ids = array();

        foreach ($criteria as $c) {
            array_push($c_ids, $c->id);
        }

        $a_ids = array();

        $alternatives = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->whereIn('judments.id_node', $c_ids)
            ->select('node.id', 'node.descr')
            ->distinct()
            ->get();

        foreach ($alternatives as $a) {
            array_push($a_ids, $a->id);
        }

        for ($i = 0; $i < count($c_ids); $i++) {
            for ($j = $i + 1; $j < count($c_ids); $j++) {
                $judment = new Judments();
                $judment->user_email = $data['descricao'];
                $judment->id_node = $id;
                $judment->id_node1 = $c_ids[$i];
                $judment->id_node2 = $c_ids[$j];
                $judment->score = 1;
                $judment->save();
            }
        }

        foreach ($criteria as $c) {
            for ($i = 0; $i < count($a_ids); $i++) {
                for ($j = $i + 1; $j < count($a_ids); $j++) {
                    $judment = new Judments();
                    $judment->user_email = $data['descricao'];
                    $judment->id_node = $c->id;
                    $judment->id_node1 = $a_ids[$i];
                    $judment->id_node2 = $a_ids[$j];
                    $judment->score = 1;
                    $judment->save();
                }
            }
        }

        $gw = GroupDecision::where('node',$id)->sum('weight');
        if($gw >= 1) $ow = 1;
        else $ow = 1 - $gw;

        // return view("objetivos.dmpanel")
        //     ->with('dms', $dms)
        //     ->with('id', $node->id)
        //     ->with('descr', $node->descr)
        //     ->with('ow',$ow);
        return redirect('group/'.$id.'/dm');
    }

    public function compare($id, $proxy)
    {
        $results = new dmModel();

        $goal = Node::select('id', 'descr')->where('id', $id)->get()->first();

        $userproxy = GroupDecision::select('id', 'node', 'email')
            ->where('id', $proxy)->get()->first();

        $dms = GroupDecision::leftJoin('users', 'users.email', '=', 'groupdecision.email')
            ->where('groupdecision.node', $id)
            ->where('groupdecision.email', '<>', $userproxy->email)
            ->select('users.avatar', 'groupdecision.*')
            ->get();

        return view("objetivos.dmcomparisons")
            ->with('dms', $dms)
            ->with('goal', $goal)
            ->with('proxy', $userproxy);
    }

    public function dmweights($id, Request $request)
    {
        $data = $request->all();
        //dd($data);
        $nodes = array();
        $score = array(array());

        for ($i = 0; $i < $data['counter']; $i++) {
            $s = explode(";", $data['score' . $i]);
            // array_push($nodes, $s[1]);
            array_push($nodes, $s[2]);
            $score[$s[1]][$s[2]] = $s[3];
            $score[$s[2]][$s[1]] = 1 / $s[3];
            //echo "<br>" . $data['score' . $i];
            // echo "<br>" . $s[1] . " X " . $s[2] . " = " . $s[3];
            // echo "<br>" . $s[2] . " X " . $s[1] . " = " . 1 / $s[3];
        }

        
        for ($i = 0; $i < count($nodes); $i++) {
            for ($j = $i + 1; $j < count($nodes); $j++) {
                $score[$nodes[$i]][$nodes[$j]] = 1 / ($score[$s[1]][$nodes[$i]] / $score[$s[1]][$nodes[$j]]);
                $score[$nodes[$j]][$nodes[$i]] = ($score[$s[1]][$nodes[$i]] / $score[$s[1]][$nodes[$j]]);
                // echo "<br>" . $nodes[$i] . " X " . $nodes[$j] . " = " . 1 / ($score[$s[1]][$nodes[$i]] / $score[$s[1]][$nodes[$j]]);
                // echo "<br>" . $nodes[$j] . " X " . $nodes[$i] . " = " . ($score[$s[1]][$nodes[$i]] / $score[$s[1]][$nodes[$j]]);
            }
        }
        //Monta a matriz de julgamentos
        // echo "<hr>";
        array_push($nodes, $s[1]);
        // print_r($nodes);
        sort($nodes);
        // print_r($nodes);
        // print_r($nodes);
        // echo "<br>";
        $dmjudment = array(array());
        // echo "<table border='10'>";
        for ($i = 0; $i < count($nodes); $i++) {
            // echo "<tr>";
            for ($j = 0; $j < count($nodes); $j++) {
                if ($i == $j) {
                    // echo "<td>1</td>";
                    $score[$i][$j] = 1;
                    $dmjudment[$i][$j] = 1;
                } else {
                    $dmjudment[$i][$j] = $score[$nodes[$i]][$nodes[$j]];
                    // echo "<td>". $score[$nodes[$i]][$nodes[$j]]."</td>";
                }
            }
            // echo "</td>";
        }
        // echo "</table>";
        $p = AHPController::GetPriority($dmjudment);
        // print_r($p);
        // echo "<br>";
        // print_r($nodes);
        for($i = 1; $i < count($p); $i++) {
            GroupDecision::where('id',$nodes[$i])->update(['weight' => $p[$i]]);
        }
        return redirect('/group/'.$id.'/dm');
    }
}
