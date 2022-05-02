<?php

namespace App\Http\Controllers;

use App\Models\Objetivo;
use App\Models\Node;
use App\Models\Judments;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Http\Controllers\AHPController;
use Illuminate\Support\Facades\Auth;

class NodesController extends Controller
{

    public function index()
    {
        $objectives = Node::get()->where('level', 0)->where('user_id', Auth::user()->id);
        return view("objetivos.nodes")->with('objectives', $objectives);
    }

    public function criteria($id)
    {
        # select DISTINCT node.descr from judments inner join node on (judments.id_node1 = node.id OR judments.id_node2 = node.id) where judments.id_node = $id;

        $criteria = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('judments.id_node', $id)
            ->where('node.user_id', Auth::user()->id)
            ->select('node.id', 'node.descr')
            ->distinct()
            ->get();
        $objective = Node::get()->where('id', $id);
        foreach ($objective as $o)
            $goal = $o;
        return view("objetivos.criteria")->with('criteria', $criteria)->with('goal', $goal);
    }

    public function alternatives($id)
    {
        $alternatives = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('judments.id_node', $id)
            ->where('node.user_id', Auth::user()->id)
            ->select('node.id')
            ->distinct()
            ->get();

        $v = array();

        foreach ($alternatives as $a) {
            array_push($v, $a->id);
        }

        $alternatives = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->whereIn('judments.id_node', $v)
            ->select('node.id', 'node.descr')
            ->where('node.user_id', Auth::user()->id)
            ->distinct()
            ->get();

        $objective = Node::get()->where('user_id', Auth::user()->id)->where('id', $id);
        $goal = $objective[$id - 1];

        return view("objetivos.alternatives")->with('alternatives', $alternatives)->with('goal', $goal);
    }

    public function comparisons($up, $id)
    {
        $ids = array();

        $query = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('node.id', '=', $id)
            ->where('node.user_id', Auth::user()->id)
            ->select('judments.id_node')
            ->distinct()
            ->get();

        foreach ($query as $q)
            array_push($ids, $q->id_node);

        $objective = Node::get()->whereIn('id', $ids);

        $criteria = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('node.id', '!=', $id)
            ->where('node.user_id', Auth::user()->id)
            ->whereIn('judments.id_node', $ids)
            ->select('node.id', 'node.descr')
            ->distinct()
            ->get();

        $target = Node::where('user_id', Auth::user()->id)->where('id', $id)->get();

        return view("objetivos.comparisons")->with('itens', $criteria)->with('goal', $objective)->with('target', $target)->with('id', $id);
    }

    public function formCreateNode($up, Request $request)
    {

        $data = $request->all();
        $descr = "Node";
        $itens = array();
        $ids = array();
        if ($up == 0) {
            array_push($itens, $up);
            $nodes = 1;
            $descr = "Decision Problem";
            return view("objetivos.formCreateNode")->with('itens', $itens)->with('nodes', $nodes)->with('descr', $descr)->with('up', $up)->with('level', 0);
        } elseif ($data['type'] == 1) {

            $query = Node::select('descr')->where('user_id', Auth::user()->id)->where('id', $up);
            $q = $query->first();

            $descr = "Criteria for " . $q->descr . " Decision Problem";
            return view("objetivos.formCreateNode")->with('itens', $itens)->with('nodes', $data['nodes'])->with('descr', $descr)->with('up', $up)->with('level', 1);
        } elseif ($data['type'] == 2) {

            $query = Node::select('descr')->where('user_id', Auth::user()->id)->where('id', $up);
            $q = $query->first();
            $descr = "Alternatives for " . $q->descr . " Decision Problem";

            return view("objetivos.formCreateNode")->with('itens', $itens)->with('nodes', $data['nodes'])->with('descr', $descr)->with('up', $up)->with('level', 2);
        }
    }

    public function createNode($up, Request $request)
    {

        $data = $request->all();
        $level = $data['level'];
        $ids = array();

        for ($i = 0; $i < count($data['descricao']); $i++) {
            $node = new Node();
            $node->level = $level;
            $node->user_id = Auth::user()->id;
            $node->descr = $data['descricao'][$i];
            $node->save();
            array_push($ids, $node->id);
        }

        if ($level == 1) {
            for ($i = 0; $i < count($ids); $i++) {
                for ($j = $i + 1; $j < count($ids); $j++) {
                    $judment = new Judments();
                    $judment->id_node = $up;
                    $judment->user_id = Auth::user()->id;
                    $judment->id_node1 = $ids[$i];
                    $judment->id_node2 = $ids[$j];
                    $judment->score = 1;
                    $judment->save();
                }
            }
        }

        if ($level == 2) {
            $id_node1 = Judments::select('id_node1')->distinct()->where('user_id', Auth::user()->id)->where('id_node', $up);
            $id_node2 = Judments::select('id_node2')->distinct()->where('user_id', Auth::user()->id)->where('id_node', $up)->union($id_node1)->get();


            foreach ($id_node2 as $node) {
                for ($i = 0; $i < count($ids); $i++) {
                    for ($j = $i + 1; $j < count($ids); $j++) {
                        $judment = new Judments();
                        $judment->id_node = $node->id_node2;
                        $judment->user_id = Auth::user()->id;
                        $judment->id_node1 = $ids[$i];
                        $judment->id_node2 = $ids[$j];
                        $judment->score = 1;
                        $judment->save();
                    }
                }
            }
        }

        return redirect('/nodes');
    }

    public function removeNode($id)
    {
        $v = array($id);
        $i = 0;

        do {
            $query = Judments::join('node', function ($join) {
                $join->on('judments.id_node1', '=', 'node.id')
                    ->orOn('judments.id_node2', '=', 'node.id');
            })
                ->where('judments.id_node', $v[$i])
                ->where('node.user_id', Auth::user()->id)
                ->select('node.id', 'node.descr')
                ->distinct()
                ->get();
            if (count($query) > 0) {
                foreach ($query as $q)
                    array_push($v, $q->id);
            }
            $i++;
        } while ($i < count($v));
        $v = array_unique($v);
        Node::whereIn('id', $v)->delete();
        Judments::where('user_id', Auth::user()->id)->whereIn('id_node', $v)->delete();
        Judments::where('user_id', Auth::user()->id)->whereIn('id_node1', $v)->delete();
        Judments::where('user_id', Auth::user()->id)->whereIn('id_node2', $v)->delete();
        return redirect("/nodes");
    }

    public function UpdateScore($proxy, Request $request)
    {
        echo $proxy;
        $data = $request->all();
        $s = explode(";", $data['score0']);
        $up = $s[0];
        $top = Judments::where('user_id', Auth::user()->id)->where('id_node', $s[0])->get();
        $v = array();
        foreach ($top as $t) {
            array_push($v, $t->id_node1);
            array_push($v, $t->id_node2);
        }
        $v = array_unique($v);
        $nodes = array();
        foreach ($v as $i) {
            array_push($nodes, $i);
        }
        print_r($v);
        echo max($v);
        $matrix = array(array());

        //Inicializa a matrix de julgamentos
        for ($i = 0; $i <= max($v); $i++) {
            for ($j = 0; $j <= max($v); $j++) {
                $matrix[$i][$j] = 0;
            }
        }
        //Preenche com a linha e faz a recíproca
        for ($i = 0; $i < $data['counter']; $i++) {
            $s = explode(";", $data['score' . $i]);
            $matrix[$s[1]][$s[2]] = $s[3]; //julgamento do decisor
            $matrix[$s[2]][$s[1]] = (1 / $s[3]); //recíproca
        }

        //Completa o que falta
        for ($i = 0; $i < count($nodes); $i++) {
            for ($j = $i + 1; $j < count($nodes); $j++) {
                if ($matrix[$nodes[$i]][$nodes[$j]] == 0) {
                    $matrix[$nodes[$i]][$nodes[$j]] = 1 / ($matrix[$proxy][$nodes[$i]] / $matrix[$proxy][$nodes[$j]]);
                    $matrix[$nodes[$j]][$nodes[$i]] = ($matrix[$proxy][$nodes[$i]] / $matrix[$proxy][$nodes[$j]]);
                }
                echo "<br>" . $up . " - " . $nodes[$i] . " - " . $nodes[$j] . " - " . $matrix[$nodes[$i]][$nodes[$j]];
                Judments::where('id_node', $up)
                    ->where('id_node1', $nodes[$i])
                    ->where('id_node2', $nodes[$j])
                    ->where('user_id', Auth::user()->id)
                    ->update(['score' => $matrix[$nodes[$i]][$nodes[$j]]]);
            }
        }

        return redirect("/nodes");
    }
}
