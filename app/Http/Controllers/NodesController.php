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
        $objectives = Node::get()->where('level', 0)->where('user_id',Auth::user()->id);
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
            ->distinct()
            ->get();

        $objective = Node::get()->where('id', $id);
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
            ->whereIn('judments.id_node', $ids)
            ->select('node.id', 'node.descr')
            ->distinct()
            ->get();

        $target = Node::where('id', $id)->get();

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

            $query = Node::select('descr')->where('id', $up);
            $q = $query->first();
            // foreach($query as $q) { array_push($ids, $q->id_node1); array_push($ids, $q->id_node2); }
            // $ids = array_unique($ids);

            $descr = "Criteria for " . $q->descr . " Decision Problem";
            return view("objetivos.formCreateNode")->with('itens', $itens)->with('nodes', $data['nodes'])->with('descr', $descr)->with('up', $up)->with('level', 1);
        } elseif ($data['type'] == 2) {

            $query = Node::select('descr')->where('id', $up);
            $q = $query->first();
            $descr = "Alternatives for " . $q->descr . " Decision Problem";



            return view("objetivos.formCreateNode")->with('itens', $itens)->with('nodes', $data['nodes'])->with('descr', $descr)->with('up', $up)->with('level', 2);
            //     $query = Judments::
            //     join('node', function ($join) {
            //     $join->on('judments.id_node1', '=', 'node.id')
            //     ->orOn('judments.id_node2', '=', 'node.id');
            //     })
            //     ->where('node.id','=', $up)
            //     ->select('judments.id_node')
            //     ->distinct()
            //     ->get();

            // foreach($query as $q) 
            //     array_push($itens, $q->id_node);

            //     print_r($itens);

            //     return view("objetivos.formCreateNode")->with('itens', $itens)->with('nodes', $data['nodes'])->with('descr', $descr)->with('up',$up); 
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
            $id_node1 = Judments::select('id_node1')->distinct()->where('id_node', $up);
            $id_node2 = Judments::select('id_node2')->distinct()->where('id_node', $up)->union($id_node1)->get();


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
        Judments::whereIn('id_node', $v)->delete();
        Judments::whereIn('id_node1', $v)->delete();
        Judments::whereIn('id_node2', $v)->delete();
        return redirect("/nodes");
    }

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
        return redirect("/nodes");
    }
}
