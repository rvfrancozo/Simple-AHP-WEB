<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//Model Results
class Results extends Model
{
    use HasFactory;

    private $objective;
    private $objectiveid;
    private $criteria;
    private $node_id;
    private $alternative;
    private $score;
    private $priority;
    private $bestAlternative;
    private $bestCriteria;
    private $bestAlternativeScore;
    private $bestCriteriaPriority;

    public function setNodeId($node_id) {
        $this->node_id = $node_id;
    }
    public function getNodeId(){
        return $this->node_id;
    }
    
    public function setBestCriteriaPriority($bestCriteriaPriority) {
        $this->bestCriteriaPriority = $bestCriteriaPriority;
    }
    public function getBestCriteriaPriority(){
        return $this->bestCriteriaPriority;
    }

    public function setBestAlternative($bestAlternative){
        $this->bestAlternative = $bestAlternative;
    }

    public function getBestAlternative(){
        return $this->bestAlternative;
    }

    public function setBestCriteria($bestCriteria){
        $this->bestCriteria = $bestCriteria;
    }

    public function getBestCriteria(){
        return $this->bestCriteria;
    }
    
    public function setBestAlternativeScore( $bestAlternativeScore){
        $this->bestAlternativeScore = $bestAlternativeScore;
    }

    public function getBestAlternativeScore(){
        return $this->bestAlternativeScore;
    }

    public function setObjective($objective) {
        $this->objective = $objective;
    }

    public function getObjective() {
        return $this->objective;
    }

    public function setObjectiveId($objectiveid) {
        $this->objectiveid = $objectiveid;
    }

    public function getObjectiveId() {
        return $this->objectiveid;
    }

    public function setCriteria($criteria) {
        $this->criteria = $criteria;
    }

    public function getCriteria() {
        return $this->criteria;
    }

    public function setAlternatives($alternative) {
        $this->alternative = $alternative;
    }

    public function getAlternatives() {
        return $this->alternative;
    }

    public function setScore($score) {
        $this->score = $score;
    }
    
    public function getScore() {
        return $this->score;
    }

    public function setPriority($priority){
        $this->priority = $priority;
    }

    public function getPriority(){
        return $this->priority;
    }
}