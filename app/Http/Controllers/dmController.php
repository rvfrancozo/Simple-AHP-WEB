<?php

namespace App\Http\Controllers;

use App\Models\GroupDecision;
use App\Models\Node;
use App\Models\User;
use App\Http\Controllers\AHPController;
use App\Models\Judments;

use Illuminate\Http\Request;

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
        foreach($dms as $d) {
            array_push($users, $d->email);
        }

        $dms = GroupDecision::
            join('users', 'users.email', '=', 'groupdecision.email')
            ->where('groupdecision.node', $id)
            ->select('users.*', 'groupdecision.*')
            ->get();

        //$dms = User::wherein('email',$users)->get();
        return view("objetivos.dmpanel")->with('id', $node->id)->with('descr', $node->descr)->with('dms', $dms);
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

        //print_r($data);
        return view("objetivos.dmpanel")->with('dms', $dms)->with('id', $node->id)->with('descr', $node->descr);
    }

    public function compare($id, $proxy)
    {
        $dms = GroupDecision::
        join('users', 'users.email', '=', 'groupdecision.email')
        ->where('groupdecision.node', $id)
        ->select('users.*', 'groupdecision.*')
        ->get();

        

        foreach($dms as $dm) {
            echo "<br>".$dm->email;
        }
    }
}
