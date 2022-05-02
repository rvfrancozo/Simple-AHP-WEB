<?php

namespace App\Http\Controllers;

use App\Models\Objetivo;
use App\Models\Node;
use App\Models\Results;
use App\Models\Judments;
use Illuminate\Http\Request;
use App\Http\Controllers\AHPController;

class Testes extends Controller
{

    public function testes($id)
    {
        //Criamos um objeto do modelo de resultados 
        $results = new Results();

        $j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0);
        $j_alternatives = AHPController::GetAlternativesJudmentsMatrix($id, 0);

        $query = Node::find($id); //Busca pelo id da tabela node

        //Adicionamos o objetivo ao objeto de resultados
        $results->setObjective($query->descr);

        //$results->setCriteria(
        $query = Judments:: //Consulta na tabela Judments que será armazenada na variável $query

                //Aqui faz um join composto entre as duas tabelas (judments e node)
                join('node', function ($join) {
                    $join->on('judments.id_node1', '=', 'node.id')
                        ->orOn('judments.id_node2', '=', 'node.id');
                })

                //condição de consulta
                ->where('judments.id_node', $id)

                //Campo que seleciona da tabela
                ->select('node.descr')

                //Ordena pelo ID
                //->orderBy('node.id', 'asc')

                //Não aceita duplicados teste
                ->distinct()

                //Get ;)
                ->get();
        //);
        $results->setCriteria($query);
        $results->setPriority(AHPController::GetPriority($j_criteria));
        // foreach($query as $q) {
        //     echo "<br>".$q->descr;
        // }
        for($i = 0; $i < count($results->getCriteria()); $i++) {
            echo "<br>".$results->getPriority()[$i].": ".
            $results->getCriteria()[$i]->descr;
        }
        

/*
        //Para mostrar as alternativas é necessário pegar os ids dos critérios
        $alternatives = Judments::join('node', function ($join) {
            $join->on('judments.id_node1', '=', 'node.id')
                ->orOn('judments.id_node2', '=', 'node.id');
        })
            ->where('judments.id_node', $id)
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

        //Pega os scores
        $results->setScore(AHPController::FinalPriority($j_criteria, $j_alternatives));

        $results->setPriority(AHPController::GetPriority($j_criteria));

        $results->setBestCriteriaPriority(0);
        for ($i = 0; $i < count($results->getPriority()); $i++) {
            echo "<br>".$results->getCriteria()[$i]->descr.": ".$results->getPriority()[$i];
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
        }*/
    }
    
}
