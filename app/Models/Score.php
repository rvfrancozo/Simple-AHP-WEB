<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{

    public $id_node;
    public $id_node1;
    public $id_node2;
    public $score;
    use HasFactory;
}
