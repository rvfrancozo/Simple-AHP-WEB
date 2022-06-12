<?php

namespace App\Http\Controllers;

use App\Models\GroupDecision;
use Illuminate\Http\Request;
use App\Models\Node;
use App\Models\Judments;
use Illuminate\Support\Facades\Auth;

class AHPController extends Controller
{

	//Normalize a Matrix of judments in AHP Scope
	public static function Normalize($matrix)
	{
		$dim = count($matrix);

		$sum_cols = array();

		for ($i = 0; $i < $dim; $i++) {
			$tmp = 0;
			for ($j = 0; $j < $dim; $j++) {
				$tmp = $tmp + $matrix[$j][$i];
			}
			array_push($sum_cols, $tmp);
		}

		$n_matrix = $matrix;
		for ($i = 0; $i < $dim; $i++) {
			for ($j = 0; $j < $dim; $j++) {
				$n_matrix[$j][$i] = $matrix[$j][$i] / $sum_cols[$i];
			}
		}
		return ($n_matrix);
	}

	//Get an array of Priorities from a Criteria Matrix Judments
	public static function GetPriority($julgamentos)
	{
		$n_matrix = AHPController::normalize($julgamentos);
		$dim = count($n_matrix);
		$priority = array();

		for ($i = 0; $i < $dim; $i++) {
			$sum_line = 0;
			for ($j = 0; $j < $dim; $j++) {
				$sum_line += $n_matrix[$i][$j];
			}
			//array_push( $priority, round($sum_line/$dim,3));
			array_push($priority, $sum_line / $dim);
		}
		#print_r(array_values($priority));
		return ($priority);
	}

	//Get an array of Priorities from a Criteria Matrix Judments
	public static function GetGroupPriority($id)
	{
		//SELECT distinct(user_email) FROM judments WHERE id_node = 1 and judments.user_email NOT IN (SELECT email from groupdecision where node = 1);
		$mailowner = Judments::select('user_email')->where('id_node', $id)->whereNotIn('user_email', GroupDecision::select('email')->where('node', $id)->get())->get()->first();
		//echo $mailowner->user_email;

		/*
		select distinct(judments.user_email), groupdecision.weight from judments join groupdecision on judments.user_email = groupdecision.email  where judments.id_node = 1 and groupdecision.node =1 and judments.user_email <> 'admin@admin';
		*/
		// $users = Judments::leftjoin('groupdecision', 'judments.user_email', '=', 'groupdecision.email')
		// ->select('judments.user_email')
		// ->select('groupdecision.weight')
		// ->where('user_email', '<>', $mailowner->user_email)
		// ->where('id_node', $id)->distinct()->get();

		// $users = GroupDecision::leftJoin('users', 'groupdecision.email', '=', 'users.email')
		// 	->where('groupdecision.node', $id)
		// 	->select('groupdecision.id', 'groupdecision.node', 'groupdecision.email', 'groupdecision.weight', 'users.avatar')
		// 	->get();
		$users = GroupDecision::where('node',$id)->select('email','weight')->get();

		//dd($users);

		//$users = Judments::where('id_node', $id)->where('user_email', '<>', $mailowner->user_email)->select('user_email')->distinct()->get();
		$nusers = count($users);

		$group = array();

		$weight = array();

		//Julgamento do proprietário

		//$j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0, Auth::user()->email);
		$j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0, $mailowner->user_email);
		$w = 1 - GroupDecision::where('node', $id)->sum('weight');
		//echo "<hr><b>Criteria priorities for:</b>" . $user->user_email . "<br>";
		$userpriority = AHPController::GetPriority($j_criteria);
		for ($i = 0; $i < count($userpriority); $i++) {
			$userpriority[$i] = $userpriority[$i] * $w;
		}
		array_push($group, $userpriority);


		//echo "<br>" . $mailowner->user_email . ": " . $w;
		foreach ($users as $user) {
			$j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0, $user->email);
			//echo "<br>" . $user->email . ": " . $user->weight." = ";
			//echo "<hr><b>Criteria priorities for:</b>" . $user->user_email . "<br>";
			$userpriority = AHPController::GetPriority($j_criteria);
			//print_r($j_criteria);
			for ($i = 0; $i < count($userpriority); $i++) {
				$userpriority[$i] = $userpriority[$i] * $user->weight;
			}
			
			//echo "<BR>";
			array_push($group, $userpriority);

			//print_r(AHPController::GetPriority($j_criteria));
		}
		$dim = count($j_criteria);
		$gpriority = array();
		$b = true;

		//print_r($group);

		foreach ($group as $g) {
			for ($i = 0; $i < count($g); $i++) {
				if ($b) {
					array_push($gpriority, $g[$i]);
				} else {
					$gpriority[$i] += $g[$i];
				}
			}
			$b = false;
		}

		//print_r($gpriority);

		return ($gpriority);
	}


	//Return a float with the consistency of the judments matrix
	public static function CheckConsistency($julgamentos)
	{
		//$saaty = array(0, 0, 0.00001, 0.5247, 0.8816, 1.1086, 1.2479, 1.3417, 1.4057, 1.4499, 1.4854, 1.51, 1.48, 1.56, 1.57, 1.59);
		//Saaty 1980 p. 34
		$saaty = array(0, 0, 0.00001, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57, 1.59);
		$priority = AHPController::GetPriority($julgamentos);
		$dim = count($julgamentos);

		$vector = array();
		for ($i = 0; $i < $dim; $i++) {
			$tmp = 0;
			for ($j = 0; $j < $dim; $j++) {
				$tmp = $tmp + ($julgamentos[$i][$j] * $priority[$j]);
			}
			array_push($vector, $tmp / $priority[$i]);
		}
		$tmp = array_sum($vector) / count($vector);

		$ci = ($tmp - $dim) / ($dim - 1);
		$cr = $ci / $saaty[$dim];

		return $cr;
	}

	public static function GetConsistencyIndex($julgamentos)
	{
		//$saaty = array(0, 0, 0.00001, 0.5247, 0.8816, 1.1086, 1.2479, 1.3417, 1.4057, 1.4499, 1.4854, 1.51, 1.48, 1.56, 1.57, 1.59);
		//Saaty 1980 p. 34
		$saaty = array(0, 0, 0.00001, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57, 1.59);
		$priority = AHPController::GetPriority($julgamentos);
		$dim = count($julgamentos);

		$vector = array();
		for ($i = 0; $i < $dim; $i++) {
			$tmp = 0;
			for ($j = 0; $j < $dim; $j++) {
				$tmp = $tmp + ($julgamentos[$i][$j] * $priority[$j]);
			}
			array_push($vector, $tmp / $priority[$i]);
		}
		$tmp = array_sum($vector) / count($vector);

		$ci = ($tmp - $dim) / ($dim - 1);
		$cr = $ci / $saaty[$dim];

		return $ci;
	}

	public static function GetLambdaMax($julgamentos)
	{
		//$saaty = array(0, 0, 0.00001, 0.5247, 0.8816, 1.1086, 1.2479, 1.3417, 1.4057, 1.4499, 1.4854, 1.51, 1.48, 1.56, 1.57, 1.59);
		//Saaty 1980 p. 34
		$saaty = array(0, 0, 0.00001, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49, 1.51, 1.48, 1.56, 1.57, 1.59);
		$priority = AHPController::GetPriority($julgamentos);
		$dim = count($julgamentos);

		$vector = array();
		for ($i = 0; $i < $dim; $i++) {
			$tmp = 0;
			for ($j = 0; $j < $dim; $j++) {
				$tmp = $tmp + ($julgamentos[$i][$j] * $priority[$j]);
			}
			array_push($vector, $tmp / $priority[$i]);
		}
		$tmp = array_sum($vector) / count($vector);

		$ci = ($tmp - $dim) / ($dim - 1);
		$cr = $ci / $saaty[$dim];
		$lambda = $tmp;

		return $lambda;
	}


	//Given a alternatives and criteria judments matrix return the final priorities
	//from alternatives
	public static function FinalPriority($j_criteria, $j_alternatives)
	{

		$c = count($j_alternatives); //quantidade de critérios
		$a = count($j_alternatives[0]); //quantidade de alternativas

		$final = array_fill(0, $a, 0);

		for ($i = 0; $i < $a; $i++) {
			for ($j = 0; $j < $c; $j++) {
				$final[$i] += AHPController::GetPriority($j_alternatives[$j])[$i] * AHPController::GetPriority($j_criteria)[$j];
				//$final[$i] = round($final[$i],3);
				//$final[$i] = $final[$i];
			}
		}

		return ($final);
	}

	public static function GroupFinalPriority($id)
	{
		$mailowner = Judments::select('user_email')->where('id_node', $id)->whereNotIn('user_email', GroupDecision::select('email')->where('node', $id)->get())->get()->first();

		//$users = Judments::where('id_node', $id)->where('user_email', '<>', $mailowner->user_email)->select('user_email')->distinct()->get();
		$users = GroupDecision::where('node',$id)->select('email','weight')->get();

		$nusers = count($users);

		$group = array();

		$j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0, $mailowner->user_email);
		$j_alternatives = AHPController::GetAlternativesJudmentsMatrix($id, 0, $mailowner->user_email);
		$w = 1 - GroupDecision::where('node', $id)->sum('weight');
		//echo "<hr><b>Criteria priorities for:</b>" . $user->user_email . "<br>";
		$userpriority = AHPController::FinalPriority($j_criteria, $j_alternatives);
		for ($i = 0; $i < count($userpriority); $i++) {
			$userpriority[$i] = $userpriority[$i] * $w;
		}
		array_push($group, $userpriority);

		foreach ($users as $user) {
			$j_criteria = AHPController::GetCriteriaJudmentsMatrix($id, 0, $user->email);
			$j_alternatives = AHPController::GetAlternativesJudmentsMatrix($id, 0, $user->email);
			//echo "<hr><b>Criteria priorities for:</b>" . $user->user_email . "<br>";
			//array_push($group, AHPController::FinalPriority($j_criteria, $j_alternatives));
			//print_r(AHPController::GetPriority($j_criteria));
			$userpriority = AHPController::FinalPriority($j_criteria, $j_alternatives);
			for ($i = 0; $i < count($userpriority); $i++) {
				$userpriority[$i] = $userpriority[$i] * $user->weight;
			}
			array_push($group, $userpriority);
		}
		$dim = count($j_alternatives);
		$gpriority = array();
		$b = true;

		foreach ($group as $g) {
			for ($i = 0; $i < count($g); $i++) {
				if ($b) {
					array_push($gpriority, $g[$i]);
				} else {
					$gpriority[$i] += $g[$i];
				}
			}
			$b = false;
		}

		$g_p = $gpriority;
		for ($i = 0; $i < count($gpriority); $i++) {
			$g_p[$i] = $gpriority[$i] / array_sum($gpriority);
		}
		return ($g_p);
	}

	//
	public static function GetMatrix($objective, $level)
	{
		$nodes = Node::get()->where('level', 1);

		foreach ($nodes as $node) {
			echo $node->level;
		}
	}

	public static function GetCriteriaJudmentsMatrix($objective, $level, $user)
	{
		//$judments = Judments::orderBy('id', 'DESC')->get()->where('id_node', 1)->where('id_node1', 2);
		if (is_null($user)) {
			$user = Auth::user()->email;
		}
		$query = Judments::orderBy('id_node1', 'ASC')
			->orderBy('id_node2', 'ASC')
			->get()
			->where('id_node', $objective)
			->where('user_email', $user);
		$judments = array();
		$k = 0;
		foreach ($query as $q) {
			$judments[$k] = $q;
			$k++;
		}

		$order = intval(sqrt(2 * count($judments))) + 1;

		$criteria = array(array());

		//preenche a matrix de julgamentos com 1
		for ($i = 0; $i < $order; $i++)
			for ($j = 0; $j < $order; $j++)
				$criteria[$i][$j] = 1;

		$k = 0;
		for ($i = 0; $i < $order; $i++) {
			for ($j = $i + 1; $j < $order; $j++) {
				$criteria[$i][$j] = $judments[$k]->score;
				$criteria[$j][$i] = 1 / $judments[$k]->score;
				$k++;
			}
		}
		return ($criteria);
	}

	public static function GetAlternativesJudmentsMatrix($objective, $level, $user)
	{
		//$judments = Judments::orderBy('id', 'DESC')->get()->where('id_node', 1)->where('id_node1', 2);
		if (is_null($user)) {
			$user = Auth::user()->email;
		}
		$query = Judments::orderBy('id_node1', 'ASC')
			->where('id_node', $objective)
			->where('user_email', $user)
			->orderBy('id_node2', 'ASC')
			->get();

		$criteria = array();

		foreach ($query as $q) {
			array_push($criteria, $q->id_node1);
			array_push($criteria, $q->id_node2);
		}
		$id_criteria = array_unique($criteria);

		$hierarchy = array();

		foreach ($id_criteria as $id)

			array_push($hierarchy, AHPController::GetCriteriaJudmentsMatrix($id, 0, $user));

		return ($hierarchy);
	}


	public function AHP()
	{

		$j_criteria = AHPController::GetCriteriaJudmentsMatrix(7, 0, null);
		$j_alternatives = AHPController::GetAlternativesJudmentsMatrix(7, 0, null);

		AHPController::Normalize($j_criteria);
		AHPController::GetPriority($j_criteria);
		AHPController::CheckConsistency($j_criteria);

		print_r(AHPController::FinalPriority($j_criteria, $j_alternatives));
		//echo "<hr>".AHPController::CheckConsistency($j_criteria)."<hr>";
	}
}
