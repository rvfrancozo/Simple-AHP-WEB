<?php

namespace App\Http\Controllers;

use App\Models\Objetivo;
use App\Models\Node;
use App\Models\Results;
use App\Models\Judments;
use Illuminate\Http\Request;
use App\Http\Controllers\AHPController;
use Illuminate\Support\Facades\Auth;

// private $objective;
// private $objectiveid;
// private $criteria;
// private $node_id;
// private $alternative;
// private $score;
// private $priority;
// private $bestAlternative;
// private $bestCriteria;
// private $bestAlternativeScore;
// private $bestCriteriaPriority;

class GroupReportController extends Controller
{
    public function report($id)
    {
        $results = new Results();

        $query = Node::find($id); //Busca pelo id da tabela node

        $results->setObjective($query->descr);

        $results->setCriteria(
            Judments:: //Consulta na tabela Judments que será armazenada na variável $query

                //Aqui faz um join composto entre as duas tabelas (judments e node)
                join('node', function ($join) {
                    $join->on('judments.id_node1', '=', 'node.id')
                        ->orOn('judments.id_node2', '=', 'node.id');
                })

                //condição de consulta
                ->where('judments.id_node', $id)

                //Apenas os julgamentos do usuário logado
                //->where('judments.user_id', Auth::user()->id)

                //Campo que seleciona da tabela
                //necessário consultar o ID para ordenar por ele.
                ->select('node.id', 'node.descr')

                //Não aceita duplicados
                ->distinct()

                //Get ;)
                ->get()
        );

        //Para mostrar as alternativas é necessário pegar os ids dos critérios
        $alternatives = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('judments.id_node', $id)
            //Apenas os julgamentos do usuário logado
            //->where('judments.user_id', Auth::user()->id)
            ->select('node.id')
            ->distinct()
            ->get();

        //Cria um array onde será armazenado os ids dos critérios
        $v = array();
        foreach ($alternatives as $a) {
            array_push($v, $a->id);
        }

        //agora faz a consulta com um join composto
        $results->setAlternatives(
            Judments::join('node', function ($join) {
                $join->on('judments.id_node1', '=', 'node.id')
                    ->orOn('judments.id_node2', '=', 'node.id');
            })
                //busca resultados que estejam dentro do array $v
                ->whereIn('judments.id_node', $v)
                ->select('node.id', 'node.descr')
                ->distinct()
                ->get()
        );


        /*Aqui vai ser para agregar os resultados */
        $results->setPriority(AHPController::GetGroupPriority($id));

        $results->setScore(AHPController::GroupFinalPriority($id));

        $results->setBestCriteriaPriority(0);
        for ($i = 0; $i < count($results->getPriority()); $i++) {
            //echo "<br>".$results->getCriteria()[$i]->descr.": ".$results->getPriority()[$i];
            if ($results->getPriority()[$i] > $results->getBestCriteriaPriority()) {
                $results->setBestCriteriaPriority($results->getPriority()[$i]);
                $results->setBestCriteria($results->getCriteria()[$i]->descr);
            }
        }

        $results->setBestAlternativeScore(0);
        for ($i = 0; $i < count($results->getScore()); $i++) {

            if ($results->getScore()[$i] > $results->getBestAlternativeScore()) {
                $results->setBestAlternativeScore($results->getScore()[$i]);
                $results->setBestAlternative($results->getAlternatives()[$i]->descr);
            }
        }        

        //echo Auth::user()->email;

        //dd($results->getCriteria());

        return view("objetivos.groupreport")->with('results', $results);        
        
    }
}
