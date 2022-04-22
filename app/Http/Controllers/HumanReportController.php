<?php

namespace App\Http\Controllers;

use App\Models\Objetivo;
use App\Models\Node;
use App\Models\Results;
use App\Models\Judments;
use Illuminate\Http\Request;
use App\Http\Controllers\AHPController;


class HumanReportController extends Controller
{
    // public function report($id)
    // {
    //     echo "santo fregues";
    // }
    public function Report($id)
    {
        //Criamos um objeto do modelo de resultados 
        $results = new Results();

        $j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0);
        $j_alternatives = AHPController::GetAlternativesJudmentsMatrix($id, 0);

        $query = Node::find($id); //Busca pelo id da tabela node
        
        //Adicionamos o objetivo ao objeto de resultados
        $results->setObjective($query->descr);

        $results->setCriteria( Judments:: //Consulta na tabela Judments que será armazenada na variável $query

            //Aqui faz um join composto entre as duas tabelas (judments e node)
            join('node', function ($join) {
                $join->on('judments.id_node1', '=', 'node.id')
                    ->orOn('judments.id_node2', '=', 'node.id');
            })

            //condição de consulta
            ->where('judments.id_node', $id)

            //Campo que seleciona da tabela
            ->select('node.descr')

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
            ->select('node.id')
            ->distinct()
            ->get();

        //Cria um array onde será armazenado os ids dos critérios
        $v = array();
        foreach ($alternatives as $a) {
            array_push($v, $a->id);
        }

        //agora faz a consulta com um join composto
        $results->setAlternatives( Judments::join('node', function ($join) {
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

        $results->setPriority( AHPController::GetPriority($j_criteria) );
        
        
        // foreach($results->getPriority() as $pf) {
        //     if($pf > $temp){
        //         $temp = $pf;
        //     }
        // }

        //$temp = 10;

         $results->setBestCriteriaPriority(0);
         for($i = 0; $i < count($results->getPriority()); $i++){
            if($results->getPriority()[$i] > $results->getBestCriteriaPriority()){
                 $results->setBestCriteriaPriority($results->getPriority()[$i]);
                 $results->setBestCriteria($results->getCriteria()[$i]->descr);
             }    
        }

         $results->setBestAlternativeScore(0);
         for($i = 0; $i < count($results->getScore()); $i++){
            //echo $results->getScore()[$i]."<br>";

            if($results->getScore()[$i] > $results->getBestAlternativeScore()){
                $results->setBestAlternativeScore($results->getScore()[$i]);
                $results->setBestAlternative($results->getAlternatives()[$i]->descr);
            }    
        }

//dd($j_criteria);
//dd($j_alternatives);

        return view("objetivos.humanReport")->with('results', $results)
        ->with('j_criteria', $j_criteria)
        ->with('j_alternatives', $j_alternatives);
     
        //AHPController::Normalize($j_criteria);
        
        AHPController::CheckConsistency($j_criteria);

        echo "<hr><b>Matrix of Criteria Judments:</b><br>"; //Mostra os critérios do objetivo
        foreach ($j_criteria as $c) {
            foreach ($c as $score) {
                printf("%.2f&nbsp;&nbsp;&nbsp;&nbsp;", $score);
            }
            echo "<br>";
        }
        
        echo "<hr><b>Matrix of Alternatives Judments:</b><br>";

            print_r($j_alternatives);

        echo "<hr><b>Normalized Matrix of Criteria Judments:</b><br>";
        print_r(AHPController::Normalize($j_criteria));

        for ($i = 0; $i < count($j_alternatives); $i++) {
            echo "<hr><b>Normalized Matrix of Alternatives Judments for Criterion " . ($i + 1) . ":</b><br>";
            print_r(AHPController::Normalize($j_alternatives[$i]));
        }

        echo "<hr><b>Consistency of Criteria Judments:</b><br>" .
            AHPController::CheckConsistency($j_criteria);

        for ($i = 0; $i < count($j_alternatives); $i++) {
            echo "<hr><b>Consistency of Alternatives Judments for Criterion " . ($i + 1) . ":</b><br>";
            echo AHPController::CheckConsistency($j_alternatives[$i]);
        }

        echo "<hr><b>Final Priorities:</b><br>";
        print_r(AHPController::FinalPriority($j_criteria, $j_alternatives));
    }
}